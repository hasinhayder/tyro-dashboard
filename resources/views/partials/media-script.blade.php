@php
$mediaPickerUrl = route(\HasinHayder\TyroDashboard\Support\DashboardRoute::name('media.picker'));
$mediaUploadUrl = route(\HasinHayder\TyroDashboard\Support\DashboardRoute::name('media.upload'));
$storageBaseUrl = rtrim(\Illuminate\Support\Facades\Storage::disk('public')->url(''), '/');
@endphp

<div class="tyro-media-modal-overlay" id="tyroDashboardMediaPickerModal" aria-hidden="true">
    <div class="tyro-media-modal" role="dialog" aria-modal="true" aria-labelledby="tyroDashboardMediaPickerTitle">
        <div class="tyro-media-modal-header">
            <div class="tyro-media-modal-copy">
                <span class="tyro-media-modal-eyebrow">Media Library</span>
                <h2 class="tyro-media-modal-title" id="tyroDashboardMediaPickerTitle">Choose media</h2>
                <p class="tyro-media-modal-subtitle">Pick an image from your library. The selected URL will be inserted into the active field.</p>
            </div>

            <div class="tyro-media-modal-header-actions">
                <button type="button" class="tyro-media-modal-close" data-tyro-media-picker-close aria-label="Close media picker">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <div class="tyro-media-modal-toolbar">
            <div class="tyro-media-modal-toolbar-left">
                <label class="tyro-media-modal-search" for="tyroDashboardMediaPickerSearch">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.35-4.35m1.85-5.15a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z" />
                    </svg>
                    <input type="text" id="tyroDashboardMediaPickerSearch" class="form-input" placeholder="Search images or filenames" autocomplete="off">
                </label>

            </div>

            <div class="tyro-media-modal-toolbar-right">
                <label class="tyro-media-output-select-wrap" id="tyroDashboardMediaOutputWrap" hidden>
                    <select class="form-select tyro-media-output-select" id="tyroDashboardMediaOutputSelect">
                        <option value="webp" selected>WebP</option>
                        <option value="original">Original</option>
                        <option value="thumb">Thumb</option>
                    </select>
                </label>
            </div>
        </div>

        <div class="tyro-media-modal-body">
            <div class="tyro-media-grid" id="tyroDashboardMediaPickerGrid">
                <div class="tyro-media-modal-state">
                    <div><strong>Loading media</strong><span>Fetching your latest uploads.</span></div>
                </div>
            </div>

            <div id="tyroDashboardMediaPickerLoadMore" class="tyro-media-modal-load-more" style="display:none;">
                <button type="button" class="btn btn-secondary" data-tyro-media-picker-load-more>Load more</button>
            </div>
        </div>

        <div class="tyro-media-modal-upload">
            <label class="btn btn-primary tyro-media-upload-button">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:16px;height:16px;" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 0 0 3 3h10a3 3 0 0 0 3-3v-1m-4-8-4-4m0 0L8 8m4-4v12" />
                </svg>
                Upload New
                <input type="file" id="tyroDashboardMediaPickerUpload" accept="image/*" hidden>
            </label>

            <span class="tyro-media-upload-status" id="tyroDashboardMediaPickerUploadStatus"></span>

            <div class="tyro-media-upload-progress" id="tyroDashboardMediaPickerUploadProgress">
                <div class="tyro-media-upload-progress-track">
                    <div class="tyro-media-upload-progress-fill" id="tyroDashboardMediaPickerUploadProgressFill"></div>
                </div>
                <span class="tyro-media-upload-progress-text" id="tyroDashboardMediaPickerUploadProgressText">0%</span>
            </div>
        </div>
    </div>
</div>

<script>
    (function () {
        if (window.TyroDashboardMediaPicker) {
            return;
        }

        const mediaPickerUrl = @json($mediaPickerUrl);
        const mediaUploadUrl = @json($mediaUploadUrl);
        const storageBaseUrl = @json($storageBaseUrl);
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        const modal = document.getElementById('tyroDashboardMediaPickerModal');
        const grid = document.getElementById('tyroDashboardMediaPickerGrid');
        const searchInput = document.getElementById('tyroDashboardMediaPickerSearch');
        const loadMoreWrap = document.getElementById('tyroDashboardMediaPickerLoadMore');
        const uploadInput = document.getElementById('tyroDashboardMediaPickerUpload');
        const uploadStatus = document.getElementById('tyroDashboardMediaPickerUploadStatus');
        const uploadProgress = document.getElementById('tyroDashboardMediaPickerUploadProgress');
        const uploadProgressFill = document.getElementById('tyroDashboardMediaPickerUploadProgressFill');
        const uploadProgressText = document.getElementById('tyroDashboardMediaPickerUploadProgressText');
        const outputWrap = document.getElementById('tyroDashboardMediaOutputWrap');
        const outputSelect = document.getElementById('tyroDashboardMediaOutputSelect');

        let activeInput = null;
        let nextPageUrl = null;
        let searchTimer = null;

        function stateMarkup(title, text) {
            return `<div class="tyro-media-modal-state"><div><strong>${escapeHtml(title)}</strong><span>${escapeHtml(text)}</span></div></div>`;
        }

        function escapeHtml(value) {
            return String(value ?? '').replace(/[&<>"']/g, (char) => ({
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;',
            }[char]));
        }

        function getExtension(filename) {
            const parts = String(filename || '').split('.');
            return parts.length > 1 ? parts.pop().toUpperCase() : 'IMAGE';
        }

        function storageUrl(path) {
            if (!path) return '';
            if (path.startsWith('http://') || path.startsWith('https://')) return path;
            return storageBaseUrl + '/' + path.replace(/^\//, '');
        }

        function normalizeUrl(url) {
            return String(url || '').trim();
        }

        function outputUrl(item, outputMode) {
            if (outputMode === 'thumb') {
                return item.thumbnail_url || item.webp_url || item.url || '';
            }

            if (outputMode === 'webp') {
                return item.webp_url || item.url || item.thumbnail_url || '';
            }

            return item.url || item.webp_url || item.thumbnail_url || '';
        }

        function itemMatchesCurrentValue(item) {
            if (!activeInput) {
                return false;
            }

            const currentValue = normalizeUrl(activeInput.value);
            if (!currentValue) {
                return false;
            }

            return [item.url, item.thumbnail_url, item.webp_url]
                .map(normalizeUrl)
                .filter(Boolean)
                .includes(currentValue);
        }

        function currentOutputMode() {
            const outputMode = activeInput?.dataset.tyroMediaOutput || 'original';

            if (outputMode === 'select') {
                return outputSelect?.value || 'webp';
            }

            return outputMode;
        }

        function updateActivePreview(item) {
            if (!activeInput) {
                return;
            }

            const field = activeInput.closest('[data-tyro-media-picker-field]');
            const preview = field?.querySelector('[data-tyro-media-picker-preview]');
            const previewImg = field?.querySelector('[data-tyro-media-picker-preview-img]');
            const previewEmpty = field?.querySelector('[data-tyro-media-picker-preview-empty]');

            if (!preview || !previewImg) {
                return;
            }

            const previewUrl = storageUrl(item.thumbnail_url || item.webp_url || item.url || activeInput.value || '');

            if (previewUrl) {
                previewImg.src = previewUrl;
                previewImg.style.display = '';
                preview.classList.add('has-image');
                if (previewEmpty) {
                    previewEmpty.style.display = 'none';
                }
                previewImg.onerror = function () {
                    this.style.display = 'none';
                    this.parentElement.classList.remove('has-image');
                    var pe = this.parentElement.querySelector('[data-tyro-media-picker-preview-empty]');
                    if (pe) pe.style.display = '';
                };
            }
        }

        function syncOutputSelector() {
            const shouldShow = activeInput?.dataset.tyroMediaOutput === 'select';

            if (outputWrap) {
                outputWrap.hidden = !shouldShow;
            }

            if (outputSelect) {
                outputSelect.value = 'webp';
            }
        }

        function openForInput(input) {
            activeInput = input;
            searchInput.value = '';
            syncOutputSelector();
            modal.classList.add('open');
            modal.setAttribute('aria-hidden', 'false');
            loadMedia(false);
            window.setTimeout(() => searchInput.focus(), 80);
        }

        function close() {
            modal.classList.remove('open');
            modal.setAttribute('aria-hidden', 'true');
            activeInput = null;
            syncOutputSelector();
        }

        async function loadMedia(append) {
            const params = new URLSearchParams({
                type: 'image',
                search: searchInput.value || '',
                page: '1',
            });

            if (!append) {
                grid.innerHTML = stateMarkup('Loading media', 'Fetching your latest uploads.');
                nextPageUrl = null;
            }

            try {
                const url = append && nextPageUrl ? nextPageUrl : `${mediaPickerUrl}?${params}`;
                const fetchUrl = new URL(url, window.location.origin);
                fetchUrl.searchParams.set('type', 'image');
                fetchUrl.searchParams.set('search', searchInput.value || '');

                const response = await fetch(fetchUrl.toString(), {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                });
                const json = await response.json();

                renderItems(Array.isArray(json.data) ? json.data : [], append);
                nextPageUrl = json.next_page_url || null;
                loadMoreWrap.style.display = nextPageUrl ? '' : 'none';
            } catch (error) {
                grid.innerHTML = stateMarkup('Could not load media', 'Please try again in a moment.');
                loadMoreWrap.style.display = 'none';
            }
        }

        function renderItems(items, append) {
            if (!append) {
                grid.innerHTML = '';
            }

            if (!items.length && !append) {
                grid.innerHTML = stateMarkup('Nothing matched your search', 'Try a different keyword or upload a new image.');
                return;
            }

            items.forEach((item) => {
                const card = document.createElement('button');
                card.type = 'button';
                card.className = 'tyro-media-item';
                card.dataset.mediaId = String(item.id || '');
                card.title = item.filename || 'Media';

                if (itemMatchesCurrentValue(item)) {
                    card.classList.add('is-selected');
                }

                const previewUrl = storageUrl(item.thumbnail_url || item.webp_url || item.url || '');
                const actionLabel = itemMatchesCurrentValue(item) ? 'Selected' : 'Use this image';
                const metaText = item.webp_size || item.size || item.original_size || getExtension(item.filename);

                card.innerHTML = `
                    <div class="tyro-media-item-preview">
                        <img src="${escapeHtml(previewUrl)}" alt="${escapeHtml(item.alt_text || item.filename || 'Media image')}" loading="lazy">
                        <div class="tyro-media-item-overlay">
                            <span class="tyro-media-item-badge">Thumb</span>
                            <span class="tyro-media-item-action">${escapeHtml(actionLabel)}</span>
                        </div>
                    </div>
                    <div class="tyro-media-item-body">
                        <div class="tyro-media-item-name">${escapeHtml(item.filename || 'Untitled media')}</div>
                        <div class="tyro-media-item-meta">${escapeHtml(metaText)}</div>
                    </div>
                `;

                card.addEventListener('click', () => selectItem(item));
                grid.appendChild(card);
            });
        }

        function selectItem(item) {
            if (!activeInput) {
                return;
            }

            const url = outputUrl(item, currentOutputMode());
            const useFullUrl = activeInput.dataset.tyroMediaFullUrl === 'true';
            activeInput.value = useFullUrl ? storageUrl(url) : url;
            updateActivePreview(item);
            activeInput.dispatchEvent(new Event('input', { bubbles: true }));
            activeInput.dispatchEvent(new Event('change', { bubbles: true }));

            const field = activeInput.closest('[data-tyro-media-picker-field]');
            const deleteBtn = field?.querySelector('[data-tyro-media-picker-delete]');
            if (deleteBtn) deleteBtn.style.display = '';

            close();
        }

        function resetUploadProgress() {
            uploadProgress.style.display = 'none';
            uploadProgressFill.style.width = '0%';
            uploadProgressText.textContent = '0%';
        }

        async function uploadFile() {
            const file = uploadInput.files?.[0];
            if (!file) {
                return;
            }

            uploadStatus.textContent = 'Uploading...';
            uploadProgress.style.display = 'flex';
            uploadProgressFill.style.width = '0%';
            uploadProgressText.textContent = '0%';

            const formData = new FormData();
            formData.append('file', file);
            formData.append('_token', csrfToken);

            const result = await new Promise((resolve) => {
                const xhr = new XMLHttpRequest();
                xhr.open('POST', mediaUploadUrl);
                xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                xhr.upload.addEventListener('progress', (event) => {
                    if (!event.lengthComputable) {
                        return;
                    }

                    const pct = Math.round((event.loaded / event.total) * 100);
                    uploadProgressFill.style.width = `${pct}%`;
                    uploadProgressText.textContent = `${pct}%`;
                });
                xhr.addEventListener('load', () => {
                    try {
                        resolve(JSON.parse(xhr.responseText));
                    } catch (error) {
                        resolve(null);
                    }
                });
                xhr.addEventListener('error', () => resolve(null));
                xhr.addEventListener('abort', () => resolve(null));
                xhr.send(formData);
            });

            uploadInput.value = '';

            if (result && result.url) {
                uploadProgressFill.style.width = '100%';
                uploadProgressText.textContent = '100%';
                uploadStatus.textContent = 'Uploaded.';
                selectItem(result);
            } else {
                uploadStatus.textContent = 'Upload failed.';
            }

            window.setTimeout(() => {
                uploadStatus.textContent = '';
                resetUploadProgress();
            }, 1400);
        }

        document.addEventListener('click', (event) => {
            const deleteBtn = event.target.closest('[data-tyro-media-picker-delete]');
            if (deleteBtn) {
                const input = document.getElementById(deleteBtn.dataset.inputId);
                if (input) {
                    input.value = '';
                    input.dispatchEvent(new Event('input', { bubbles: true }));
                    input.dispatchEvent(new Event('change', { bubbles: true }));

                    const field = input.closest('[data-tyro-media-picker-field]');
                    const preview = field?.querySelector('[data-tyro-media-picker-preview]');
                    const previewImg = field?.querySelector('[data-tyro-media-picker-preview-img]');
                    const previewEmpty = field?.querySelector('[data-tyro-media-picker-preview-empty]');

                    if (preview) preview.classList.remove('has-image');
                    if (previewImg) { previewImg.src = ''; previewImg.style.display = 'none'; }
                    if (previewEmpty) previewEmpty.style.display = '';
                    deleteBtn.style.display = 'none';
                }
                return;
            }

            const trigger = event.target.closest('[data-tyro-media-picker-trigger]');
            if (trigger) {
                const input = document.getElementById(trigger.dataset.inputId);
                if (input) {
                    openForInput(input);
                }
                return;
            }

            if (event.target.closest('[data-tyro-media-picker-close]')) {
                close();
            }

            if (event.target.closest('[data-tyro-media-picker-load-more]')) {
                loadMedia(true);
            }
        });

        modal.addEventListener('click', (event) => {
            if (event.target === modal) {
                close();
            }
        });

        document.addEventListener('keydown', (event) => {
            const trigger = event.target.closest?.('[data-tyro-media-picker-trigger]');
            if (trigger && (event.key === 'Enter' || event.key === ' ' || event.key === 'Spacebar')) {
                event.preventDefault();
                const input = document.getElementById(trigger.dataset.inputId);
                if (input) {
                    openForInput(input);
                }
                return;
            }

            if (event.key === 'Escape' && modal.classList.contains('open')) {
                close();
            }
        });

        searchInput.addEventListener('input', () => {
            window.clearTimeout(searchTimer);
            searchTimer = window.setTimeout(() => loadMedia(false), 350);
        });

        uploadInput.addEventListener('change', uploadFile);

        window.TyroDashboardMediaPicker = {
            openForInput,
            close,
            reload: () => loadMedia(false),
        };
    })();
</script>
