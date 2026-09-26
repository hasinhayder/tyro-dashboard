@php
$mediaPickerUrl = route(\HasinHayder\TyroDashboard\Support\DashboardRoute::name('media.picker'));
$mediaUploadUrl = route(\HasinHayder\TyroDashboard\Support\DashboardRoute::name('media.upload'));
$mediaCategoriesUrl = route(\HasinHayder\TyroDashboard\Support\DashboardRoute::name('media.categories.list'));
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
                <button type="button" class="tyro-media-modal-fav-toggle" id="tyroDashboardMediaPickerFavToggle" aria-pressed="false" title="Filter favorites only" aria-label="Filter favorites only">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                    </svg>
                    <span>Favorites</span>
                </button>
                <button type="button" class="tyro-media-modal-close" data-tyro-media-picker-close aria-label="Close media picker">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <div class="tyro-media-modal-toolbar">
            <div class="tyro-media-modal-toolbar-left" id="tyroDashboardMediaPickerToolbarLeft">
                <label class="tyro-media-modal-search" for="tyroDashboardMediaPickerSearch">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.35-4.35m1.85-5.15a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z" />
                    </svg>
                    <input type="text" id="tyroDashboardMediaPickerSearch" class="form-input" placeholder="Search images or filenames" autocomplete="off">
                </label>

                <label class="tyro-media-modal-category" for="tyroDashboardMediaPickerCategory" id="tyroDashboardMediaCategoryWrap" hidden>
                    <select id="tyroDashboardMediaPickerCategory" class="form-select tyro-media-category-select" aria-label="Filter by category">
                        <option value="">All Categories</option>
                    </select>
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

        <div class="tyro-media-picker-multi-bar" id="tyroDashboardMediaPickerMultiBar" style="display:none;">
            <div class="tyro-media-picker-multi-info">
                <span class="tyro-media-picker-multi-count" id="tyroDashboardMediaPickerMultiCount">0 items selected</span>
            </div>
            <div class="tyro-media-picker-multi-actions">
                <button type="button" class="btn btn-secondary btn-sm" id="tyroDashboardMediaPickerClearSelection">Clear</button>
                <button type="button" class="btn btn-primary btn-sm" id="tyroDashboardMediaPickerConfirmBtn" disabled>
                    Add Selected Media
                </button>
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
        const mediaCategoriesUrl = @json($mediaCategoriesUrl);
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

        const toolbarLeft = document.getElementById('tyroDashboardMediaPickerToolbarLeft');
        const categoryWrap = document.getElementById('tyroDashboardMediaCategoryWrap');
        const categorySelect = document.getElementById('tyroDashboardMediaPickerCategory');

        const favToggle = document.getElementById('tyroDashboardMediaPickerFavToggle');
        const modalTitle = document.getElementById('tyroDashboardMediaPickerTitle');
        const modalSubtitle = modal?.querySelector('.tyro-media-modal-subtitle');
        const multiBar = document.getElementById('tyroDashboardMediaPickerMultiBar');
        const multiCount = document.getElementById('tyroDashboardMediaPickerMultiCount');
        const clearSelectionBtn = document.getElementById('tyroDashboardMediaPickerClearSelection');
        const confirmBtn = document.getElementById('tyroDashboardMediaPickerConfirmBtn');

        let activeInput = null;
        let nextPageUrl = null;
        let searchTimer = null;
        let onlyFavorites = false;
        let activeCategoryId = '';
        let isMultiSelect = false;
        let selectedMediaIds = new Set();
        let onMultiSelectConfirm = null;
        let multiConfirmLabel = 'Add Selected Media';
        const defaultTitle = modalTitle ? modalTitle.textContent : 'Choose media';
        const defaultSubtitle = modalSubtitle ? modalSubtitle.textContent : '';

        function updateMultiSelectBar() {
            if (!multiBar) return;
            const count = selectedMediaIds.size;
            if (multiCount) {
                multiCount.textContent = count === 1 ? '1 item selected' : `${count} items selected`;
            }
            if (confirmBtn) {
                confirmBtn.disabled = count === 0;
                confirmBtn.textContent = count > 0 ? `${multiConfirmLabel} (${count})` : multiConfirmLabel;
            }
        }

        function toggleMultiSelectItem(item, card) {
            const id = Number(item.id);
            if (selectedMediaIds.has(id)) {
                selectedMediaIds.delete(id);
                card.classList.remove('is-selected');
                const actionEl = card.querySelector('.tyro-media-item-action');
                if (actionEl) actionEl.textContent = 'Click to select';
            } else {
                selectedMediaIds.add(id);
                card.classList.add('is-selected');
                const actionEl = card.querySelector('.tyro-media-item-action');
                if (actionEl) actionEl.textContent = 'Selected';
            }
            updateMultiSelectBar();
        }

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

        function syncFavToggleUI() {
            if (!favToggle) return;
            favToggle.classList.toggle('is-active', onlyFavorites);
            favToggle.setAttribute('aria-pressed', onlyFavorites ? 'true' : 'false');
            favToggle.title = onlyFavorites ? 'Showing favorites only (click to show all)' : 'Filter favorites only';
        }

        function syncCategoryFilterUI(hasCategories) {
            if (!categoryWrap || !toolbarLeft) return;
            categoryWrap.hidden = !hasCategories;
            toolbarLeft.classList.toggle('has-category-filter', hasCategories);
        }

        async function loadCategories() {
            if (!categoryWrap || !categorySelect) {
                return;
            }

            try {
                const response = await fetch(mediaCategoriesUrl, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                });
                const json = await response.json();
                const categories = Array.isArray(json.data) ? json.data : [];

                if (!categories.length) {
                    activeCategoryId = '';
                    categorySelect.value = '';
                    syncCategoryFilterUI(false);
                    return;
                }

                categorySelect.innerHTML = '';

                const allOption = document.createElement('option');
                allOption.value = '';
                allOption.textContent = 'All Categories';
                categorySelect.appendChild(allOption);

                const noneOption = document.createElement('option');
                noneOption.value = 'none';
                noneOption.textContent = 'Uncategorized';
                categorySelect.appendChild(noneOption);

                categories.forEach((category) => {
                    const option = document.createElement('option');
                    option.value = String(category.id);
                    option.textContent = `${category.name} (${category.media_count ?? 0})`;
                    categorySelect.appendChild(option);
                });

                categorySelect.value = activeCategoryId;
                syncCategoryFilterUI(true);
            } catch (error) {
                syncCategoryFilterUI(false);
            }
        }

        function openForInput(input) {
            isMultiSelect = false;
            selectedMediaIds.clear();
            onMultiSelectConfirm = null;
            if (multiBar) multiBar.style.display = 'none';
            if (modalTitle) modalTitle.textContent = defaultTitle;
            if (modalSubtitle) modalSubtitle.textContent = defaultSubtitle;

            activeInput = input;
            searchInput.value = '';
            onlyFavorites = false;
            activeCategoryId = '';
            if (categorySelect) categorySelect.value = '';
            syncFavToggleUI();
            syncOutputSelector();
            loadCategories();

            const customCols = input?.dataset.tyroMediaColumns;
            if (grid) {
                if (customCols && parseInt(customCols, 10) > 0) {
                    grid.style.setProperty('--picker-grid-columns', parseInt(customCols, 10));
                } else {
                    grid.style.removeProperty('--picker-grid-columns');
                }
            }

            modal.classList.add('open');
            modal.setAttribute('aria-hidden', 'false');
            loadMedia(false);
            window.setTimeout(() => searchInput.focus(), 80);
        }

        function openMultiSelect(options = {}) {
            isMultiSelect = true;
            activeInput = null;
            selectedMediaIds = new Set((options.initialIds || []).map(Number));
            onMultiSelectConfirm = options.onConfirm || null;
            multiConfirmLabel = options.confirmLabel || 'Add Selected Media';

            if (modalTitle) modalTitle.textContent = options.title || 'Choose media';
            if (modalSubtitle) modalSubtitle.textContent = options.subtitle || 'Select images from your library and click confirm.';
            if (outputWrap) outputWrap.hidden = true;
            if (multiBar) multiBar.style.display = 'flex';

            searchInput.value = '';
            onlyFavorites = false;
            activeCategoryId = '';
            if (categorySelect) categorySelect.value = '';
            syncFavToggleUI();
            loadCategories();
            updateMultiSelectBar();

            modal.classList.add('open');
            modal.setAttribute('aria-hidden', 'false');
            loadMedia(false);
            window.setTimeout(() => searchInput.focus(), 80);
        }

        function close() {
            modal.classList.remove('open');
            modal.setAttribute('aria-hidden', 'true');
            activeInput = null;
            onlyFavorites = false;
            activeCategoryId = '';
            if (categorySelect) categorySelect.value = '';
            isMultiSelect = false;
            selectedMediaIds.clear();
            onMultiSelectConfirm = null;
            if (multiBar) multiBar.style.display = 'none';
            if (modalTitle) modalTitle.textContent = defaultTitle;
            if (modalSubtitle) modalSubtitle.textContent = defaultSubtitle;
            syncFavToggleUI();
            syncOutputSelector();
            if (grid) {
                grid.style.removeProperty('--picker-grid-columns');
            }
        }

        async function loadMedia(append) {
            const params = new URLSearchParams({
                type: 'image',
                search: searchInput.value || '',
                page: '1',
            });

            if (onlyFavorites) {
                params.set('favorite', '1');
            }

            if (activeCategoryId) {
                params.set('category', activeCategoryId);
            }

            if (!append) {
                grid.innerHTML = stateMarkup('Loading media', 'Fetching your latest uploads.');
                nextPageUrl = null;
            }

            try {
                const url = append && nextPageUrl ? nextPageUrl : `${mediaPickerUrl}?${params}`;
                const fetchUrl = new URL(url, window.location.origin);
                fetchUrl.searchParams.set('type', 'image');
                fetchUrl.searchParams.set('search', searchInput.value || '');
                if (onlyFavorites) {
                    fetchUrl.searchParams.set('favorite', '1');
                }
                if (activeCategoryId) {
                    fetchUrl.searchParams.set('category', activeCategoryId);
                }

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
                grid.innerHTML = stateMarkup(
                    onlyFavorites ? 'No favorite images found' : 'Nothing matched your search',
                    onlyFavorites ? 'Star favorite images in the Media Library to quickly find them here.' : 'Try a different keyword or upload a new image.'
                );
                return;
            }

            items.forEach((item) => {
                const card = document.createElement('button');
                card.type = 'button';
                card.className = 'tyro-media-item';
                card.dataset.mediaId = String(item.id || '');
                card.title = item.filename || 'Media';

                const isItemMultiSelected = isMultiSelect && selectedMediaIds.has(Number(item.id));
                const isSingleSelected = !isMultiSelect && itemMatchesCurrentValue(item);

                if (isItemMultiSelected || isSingleSelected) {
                    card.classList.add('is-selected');
                }

                const previewUrl = storageUrl(item.thumbnail_url || item.webp_url || item.url || '');
                const actionLabel = isMultiSelect
                    ? (isItemMultiSelected ? 'Selected' : 'Click to select')
                    : (isSingleSelected ? 'Selected' : 'Use this image');
                const metaText = item.webp_size || item.size || item.original_size || getExtension(item.filename);
                const favBadgeHtml = item.is_favorite ? `
                    <span class="tyro-media-item-badge tyro-media-item-fav" title="Favorite">
                        <svg viewBox="0 0 24 24" fill="currentColor" stroke="none"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                        Fav
                    </span>
                ` : '<span class="tyro-media-item-badge">Thumb</span>';

                const checkboxHtml = isMultiSelect ? `
                    <div class="tyro-media-item-checkbox">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/></svg>
                    </div>
                ` : '';

                card.innerHTML = `
                    <div class="tyro-media-item-preview">
                        ${checkboxHtml}
                        <img src="${escapeHtml(previewUrl)}" alt="${escapeHtml(item.alt_text || item.filename || 'Media image')}" loading="lazy">
                        <div class="tyro-media-item-overlay">
                            ${favBadgeHtml}
                            <span class="tyro-media-item-action">${escapeHtml(actionLabel)}</span>
                        </div>
                    </div>
                    <div class="tyro-media-item-body">
                        <div class="tyro-media-item-name">${escapeHtml(item.filename || 'Untitled media')}</div>
                        <div class="tyro-media-item-meta">${escapeHtml(metaText)}</div>
                    </div>
                `;
                card.addEventListener('click', () => {
                    if (isMultiSelect) {
                        toggleMultiSelectItem(item, card);
                    } else {
                        selectItem(item);
                    }
                });
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
                if (isMultiSelect) {
                    selectedMediaIds.add(Number(result.id));
                    updateMultiSelectBar();
                    loadMedia(false);
                } else {
                    selectItem(result);
                }
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

            if (event.target.closest('#tyroDashboardMediaPickerFavToggle')) {
                onlyFavorites = !onlyFavorites;
                syncFavToggleUI();
                loadMedia(false);
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

        categorySelect?.addEventListener('change', () => {
            activeCategoryId = categorySelect.value;
            loadMedia(false);
        });

        uploadInput.addEventListener('change', uploadFile);

        clearSelectionBtn?.addEventListener('click', () => {
            selectedMediaIds.clear();
            grid.querySelectorAll('.tyro-media-item.is-selected').forEach(c => {
                c.classList.remove('is-selected');
                const a = c.querySelector('.tyro-media-item-action');
                if (a) a.textContent = 'Click to select';
            });
            updateMultiSelectBar();
        });

        confirmBtn?.addEventListener('click', async () => {
            if (onMultiSelectConfirm && selectedMediaIds.size > 0) {
                confirmBtn.disabled = true;
                const originalText = confirmBtn.textContent;
                confirmBtn.textContent = 'Adding...';
                try {
                    await onMultiSelectConfirm(Array.from(selectedMediaIds), window.TyroDashboardMediaPicker);
                } catch (e) {
                    console.error(e);
                } finally {
                    confirmBtn.disabled = false;
                    confirmBtn.textContent = originalText;
                }
            }
        });

        window.TyroDashboardMediaPicker = {
            openForInput,
            openMultiSelect,
            close,
            reload: () => loadMedia(false),
        };
    })();
</script>
