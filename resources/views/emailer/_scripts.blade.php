<script src="https://cdn.jsdelivr.net/npm/quill@1.3.7/dist/quill.min.js"></script>
<script>
    (function() {
        const STORAGE_KEY = 'tyro_email_preset_design';
        const presets = @json($presets);

        let quillInstance = null;

        // Initialize Quill Editor
        function initEditor() {
            const editorEl = document.getElementById('emailerEditor');
            if (!editorEl || typeof window.Quill === 'undefined') return;

            quillInstance = new window.Quill(editorEl, {
                theme: 'snow',
                placeholder: 'Type your email message here... Supports rich text formatting, bullet points, links, and bold text.',
                modules: {
                    toolbar: [
                        [{ 'header': [1, 2, 3, false] }],
                        ['bold', 'italic', 'underline', 'strike'],
                        [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                        ['link', 'blockquote'],
                        ['clean']
                    ]
                }
            });

            // Sync editor HTML to hidden form input on text change
            quillInstance.on('text-change', function() {
                const hiddenInput = document.getElementById('emailBody');
                if (hiddenInput) {
                    hiddenInput.value = quillInstance.root.innerHTML;
                }
            });
        }

        // Initialize and restore saved preset design
        function initPresetState() {
            let savedDesign = localStorage.getItem(STORAGE_KEY);
            if (!savedDesign || !presets[savedDesign]) {
                savedDesign = 'modern';
            }
            applyPresetDesign(savedDesign, false);
        }

        window.applyPresetDesign = function(designKey, persist = true) {
            if (!presets[designKey]) designKey = 'modern';

            const preset = presets[designKey];

            // Update hidden form input
            const designInput = document.getElementById('emailDesignInput');
            if (designInput) designInput.value = designKey;

            // Update UI Badges & Summary on side card
            const activeName = document.getElementById('activePresetName');
            const activeDesc = document.getElementById('activePresetDesc');
            const activeBadge = document.getElementById('activePresetBadge');

            if (activeName) activeName.textContent = preset.name;
            if (activeDesc) activeDesc.textContent = preset.description;
            if (activeBadge) activeBadge.textContent = preset.badge;

            // Highlight chosen item in modal
            document.querySelectorAll('.emailer-preset-item-card').forEach(function(card) {
                const isCurrent = card.dataset.designKey === designKey;
                card.classList.toggle('is-selected', isCurrent);
            });

            if (persist) {
                try {
                    localStorage.setItem(STORAGE_KEY, designKey);
                } catch(e) {}
                if (typeof showToast === 'function') {
                    showToast('Preset design changed to "' + preset.name + '"', 'info');
                }
            }
        };

        // Modal Controls
        window.openPresetModal = function() {
            const modal = document.getElementById('emailerPresetModal');
            if (modal) modal.classList.add('active');
        };

        window.closePresetModal = function() {
            const modal = document.getElementById('emailerPresetModal');
            if (modal) modal.classList.remove('active');
        };

        window.selectPresetFromModal = function(designKey) {
            applyPresetDesign(designKey, true);
            closePresetModal();
        };

        // CC / BCC Toggles
        window.toggleCarbonFields = function() {
            const container = document.getElementById('emailerCarbonFields');
            const toggleBtn = document.getElementById('emailerCarbonToggleBtn');
            if (!container) return;

            const isHidden = container.style.display === 'none' || !container.style.display;
            container.style.display = isHidden ? 'grid' : 'none';
            if (toggleBtn) {
                toggleBtn.textContent = isHidden ? '- Hide CC & BCC' : '+ Add CC & BCC';
            }
        };

        // Live Preview Modal
        window.openEmailPreview = function() {
            const modal = document.getElementById('emailerPreviewModal');
            const iframe = document.getElementById('emailerPreviewIframe');
            const subject = document.getElementById('emailSubject')?.value || 'Subject Preview';
            const body = quillInstance ? quillInstance.root.innerHTML : '';
            const design = document.getElementById('emailDesignInput')?.value || 'modern';

            if (!modal || !iframe) return;

            // Set loading state
            iframe.srcdoc = '<div style="font-family:sans-serif;padding:2rem;text-align:center;color:#64748b;">Rendering preview...</div>';
            modal.classList.add('active');

            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            fetch("{{ route($dashboardRoute::name('emailer.preview')) }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'text/html'
                },
                body: JSON.stringify({
                    design: design,
                    subject: subject,
                    body: body
                })
            })
            .then(res => res.text())
            .then(html => {
                iframe.srcdoc = html;
            })
            .catch(() => {
                iframe.srcdoc = '<div style="font-family:sans-serif;padding:2rem;text-align:center;color:#ef4444;">Failed to generate preview.</div>';
            });
        };

        // Preview specific preset from modal with dummy content
        window.previewPresetFromModal = function(designKey) {
            const modal = document.getElementById('emailerPreviewModal');
            const iframe = document.getElementById('emailerPreviewIframe');

            if (!modal || !iframe) return;

            const preset = presets[designKey] || { name: 'Email Preview' };
            const dummySubject = 'Sample Preview: ' + preset.name;
            const dummyBody = `
                <p>Hello there,</p>
                <p>This is a demonstration preview for the <strong>${preset.name}</strong> preset design.</p>
                <p>Our responsive templates adapt seamlessly across mobile, tablet, and desktop email clients while maintaining your branding aesthetics.</p>
                <ul>
                    <li>Crisp, accessible typography</li>
                    <li>Lightweight and mobile-first markup</li>
                    <li>Pre-configured container widths and padding</li>
                </ul>
                <p>You can customize the recipient, subject, and content directly from the Tyro Dashboard Emailer composer before queueing.</p>
                <p>Warm regards,<br><strong>The Tyro Team</strong></p>
            `;

            iframe.srcdoc = '<div style="font-family:sans-serif;padding:2rem;text-align:center;color:#64748b;">Rendering preview...</div>';
            modal.classList.add('active');

            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            fetch("{{ route($dashboardRoute::name('emailer.preview')) }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'text/html'
                },
                body: JSON.stringify({
                    design: designKey,
                    subject: dummySubject,
                    body: dummyBody
                })
            })
            .then(res => res.text())
            .then(html => {
                iframe.srcdoc = html;
            })
            .catch(() => {
                iframe.srcdoc = '<div style="font-family:sans-serif;padding:2rem;text-align:center;color:#ef4444;">Failed to generate preview.</div>';
            });
        };

        window.closeEmailPreview = function() {
            const modal = document.getElementById('emailerPreviewModal');
            if (modal) modal.classList.remove('active');
        };

        // Send Email Handler
        window.sendEmail = function() {
            const sendBtns = [
                document.getElementById('emailerSendBtn'),
                document.getElementById('emailerBottomSendBtn')
            ].filter(Boolean);

            const to = document.getElementById('emailTo')?.value?.trim();
            const subject = document.getElementById('emailSubject')?.value?.trim();
            const cc = document.getElementById('emailCc')?.value?.trim();
            const bcc = document.getElementById('emailBcc')?.value?.trim();
            const design = document.getElementById('emailDesignInput')?.value || 'modern';

            const rawBody = quillInstance ? quillInstance.root.innerHTML : '';
            const textContent = quillInstance ? quillInstance.getText().trim() : '';

            if (!to) {
                if (typeof showToast === 'function') showToast('Please enter at least one recipient email address.', 'warning');
                document.getElementById('emailTo')?.focus();
                return;
            }

            if (!subject) {
                if (typeof showToast === 'function') showToast('Please enter an email subject.', 'warning');
                document.getElementById('emailSubject')?.focus();
                return;
            }

            if (!textContent) {
                if (typeof showToast === 'function') showToast('Please compose an email message body.', 'warning');
                quillInstance?.focus();
                return;
            }

            // Lock buttons & show spinner
            const originalBtnContents = sendBtns.map(btn => btn.innerHTML);
            sendBtns.forEach(btn => {
                btn.disabled = true;
                btn.innerHTML = `
                    <svg style="animation:spin 1s linear infinite;width:18px;height:18px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10" stroke-opacity="0.25"></circle>
                        <path d="M12 2a10 10 0 0 1 10 10" stroke-linecap="round"></path>
                    </svg>
                    Queuing Email...
                `;
            });

            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            fetch("{{ route($dashboardRoute::name('emailer.send')) }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    to: to,
                    subject: subject,
                    body: rawBody,
                    design: design,
                    cc: cc,
                    bcc: bcc
                })
            })
            .then(async (res) => {
                const data = await res.json();
                if (!res.ok) {
                    throw new Error(data.message || 'Failed to dispatch email.');
                }
                return data;
            })
            .then(data => {
                if (typeof showToast === 'function') {
                    showToast(data.message || 'Email queued in background!', 'success');
                } else {
                    alert(data.message || 'Email queued successfully.');
                }

                // Reset composer inputs
                document.getElementById('emailSubject').value = '';
                if (quillInstance) quillInstance.setContents([]);
                const ccContainer = document.getElementById('emailerCarbonFields');
                if (ccContainer && ccContainer.style.display !== 'none') {
                    document.getElementById('emailCc').value = '';
                    document.getElementById('emailBcc').value = '';
                }
            })
            .catch(err => {
                if (typeof showToast === 'function') {
                    showToast(err.message || 'Error queuing email.', 'error');
                } else {
                    alert(err.message || 'Error queuing email.');
                }
            })
            .finally(() => {
                sendBtns.forEach((btn, index) => {
                    btn.disabled = false;
                    btn.innerHTML = originalBtnContents[index];
                });
            });
        };

        // Bootstrap on DOM ready
        document.addEventListener('DOMContentLoaded', function() {
            initEditor();
            initPresetState();
        });
    })();
</script>
<style>
    @keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
</style>
