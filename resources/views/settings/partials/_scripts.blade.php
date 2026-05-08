<script>
function syncSbPicker(textInput, pickerId) {
    var picker = document.getElementById(pickerId);
    if (picker) picker.value = textInput.value;
}

function updateSbPreview() {
    var bg = document.getElementById('sb_bg_picker').value;
    var text = document.getElementById('sb_text_picker').value;
    var primary = document.getElementById('sb_primary_picker').value;
    var accent = document.getElementById('sb_accent_picker').value;
    var accentFg = document.getElementById('sb_accent_fg_picker').value;
    var headerBorder = document.getElementById('sb_header_border_picker').value;

    var preview = document.getElementById('sidebarPreview');
    if (!preview) return;

    var header = preview.querySelector('div:first-child');
    if (header) {
        header.style.background = bg;
        header.style.borderBottomColor = headerBorder;
        var iconBox = header.querySelector('div:first-child');
        if (iconBox) {
            iconBox.style.background = text;
            var svg = iconBox.querySelector('svg');
            if (svg) svg.setAttribute('stroke', bg);
        }
        var title = header.querySelector('span');
        if (title) title.style.color = text;
    }

    var body = preview.querySelector('div:last-child');
    if (body) {
        body.style.background = bg;
        var items = body.querySelectorAll('div');
        if (items.length >= 3) {
            items[0].style.color = text;
            items[1].style.background = primary;
            items[1].style.color = text;
            items[2].style.background = accent;
            items[2].style.color = accentFg;
        }
    }
}

function updateAbPreview() {
    var bg = document.getElementById('ab_bg_picker').value;
    var text = document.getElementById('ab_text_picker').value;
    var align = document.getElementById('ab_align').value;
    var height = document.getElementById('ab_height').value;
    var msg = document.getElementById('TYRO_DASHBOARD_ADMIN_BAR_MESSAGE').value;

    var bar = document.getElementById('ab_preview_bar');
    if (!bar) return;

    bar.style.background = bg;
    bar.style.color = text;
    bar.style.height = height;
    bar.style.justifyContent = align === 'center' ? 'center' : (align === 'right' ? 'flex-end' : 'flex-start');

    var textEl = document.getElementById('ab_preview_text');
    if (textEl) textEl.textContent = msg || 'Admin notice bar message';
}

// Sticky sidebar save button visibility
(function() {
    var topBtn = document.getElementById('systemSettingsSaveButton');
    var sideBar = document.querySelector('.vtabs-save-bar');
    if (!topBtn || !sideBar) return;

    var ticking = false;

    function updateSideSaveBtn() {
        var rect = topBtn.getBoundingClientRect();
        var isVisible = rect.top < window.innerHeight && rect.bottom > 0;
        sideBar.classList.toggle('visible', !isVisible);
        ticking = false;
    }

    window.addEventListener('scroll', function() {
        if (!ticking) {
            requestAnimationFrame(updateSideSaveBtn);
            ticking = true;
        }
    });
    updateSideSaveBtn();
})();

// ── Sidebar Colors Reset ──────────────────────────────────────
function resetSingleSbColor(btn) {
    var card = btn.closest('.branding-theme-color');
    if (!card) return;
    var colorIn = card.querySelector('input[type="color"]');
    var textIn = card.querySelector('input[type="text"]');
    var def = colorIn ? colorIn.dataset.default : null;
    if (def && colorIn) colorIn.value = def;
    if (def && textIn) textIn.value = def;
    updateSbPreview();
}

function resetSbColors() {
    var defaults = {
        sb_bg_picker: '#0e0e0e',
        sb_text_picker: '#f8fafc',
        sb_primary_picker: '#333333',
        sb_accent_picker: '#f5f5f5',
        sb_accent_fg_picker: '#171717',
        sb_header_border_picker: '#333c56'
    };
    Object.keys(defaults).forEach(function(id) {
        var picker = document.getElementById(id);
        if (!picker) return;
        var def = picker.dataset.default || defaults[id];
        picker.value = def;
        var textId = id.replace('_picker', '_text');
        var textInput = document.getElementById(textId);
        if (textInput) textInput.value = def;
    });
    updateSbPreview();
}

function saveForm() {
    var f = document.getElementById('systemSettingsForm');
    if (f) f.requestSubmit();
}

function confirmResetSbColors() {
    showDanger(
        'Reset sidebar colours?',
        'This will revert the sidebar to its default colours.',
        { confirmText: 'Reset to Default' }
    ).then(function(confirmed) {
        if (confirmed) { resetSbColors(); saveForm(); }
    });
}

// ── Admin Bar Colors Reset ────────────────────────────────────
function resetSingleAbColor(btn) {
    var card = btn.closest('.branding-theme-color');
    if (!card) return;
    var colorIn = card.querySelector('input[type="color"]');
    var textIn = card.querySelector('input[type="text"]');
    var def = colorIn ? colorIn.dataset.default : null;
    if (def && colorIn) colorIn.value = def;
    if (def && textIn) textIn.value = def;
    updateAbPreview();
}

function resetAbColors() {
    var defaults = {
        ab_bg_picker: '#000000',
        ab_text_picker: '#ffffff'
    };
    Object.keys(defaults).forEach(function(id) {
        var picker = document.getElementById(id);
        if (!picker) return;
        var def = picker.dataset.default || defaults[id];
        picker.value = def;
        var textId = id.replace('_picker', '_text');
        var textInput = document.getElementById(textId);
        if (textInput) textInput.value = def;
    });
    updateAbPreview();
}

function confirmResetAbColors() {
    showDanger(
        'Reset admin bar colours?',
        'This will revert the admin bar to its default colours.',
        { confirmText: 'Reset to Default' }
    ).then(function(confirmed) {
        if (confirmed) { resetAbColors(); saveForm(); }
    });
}

// ── Dashboard Colors ─────────────────────────────────────────
function switchDcTab(mode) {
    var lightTab = document.getElementById('dcTabLight');
    var darkTab = document.getElementById('dcTabDark');
    var lightPanel = document.getElementById('dcPanelLight');
    var darkPanel = document.getElementById('dcPanelDark');
    if (!lightTab || !darkTab || !lightPanel || !darkPanel) return;

    if (mode === 'light') {
        lightTab.style.borderBottomColor = 'var(--primary)';
        lightTab.style.color = 'var(--foreground)';
        darkTab.style.borderBottomColor = 'transparent';
        darkTab.style.color = 'var(--muted-foreground)';
        lightPanel.style.display = '';
        darkPanel.style.display = 'none';
    } else {
        darkTab.style.borderBottomColor = 'var(--primary)';
        darkTab.style.color = 'var(--foreground)';
        lightTab.style.borderBottomColor = 'transparent';
        lightTab.style.color = 'var(--muted-foreground)';
        darkPanel.style.display = '';
        lightPanel.style.display = 'none';
    }
}

function dcSyncPicker(colorInput, uid) {
    var textInput = document.getElementById('dcHex_' + uid);
    if (textInput) textInput.value = colorInput.value;
}

function dcSyncHex(textInput, mode, varName) {
    if (!textInput.value.match(/^#[0-9a-fA-F]{6}$/)) return;
    var card = textInput.closest('.branding-theme-color');
    if (!card) return;
    var colorPicker = card.querySelector('input[type="color"]');
    if (colorPicker) colorPicker.value = textInput.value;
}

function dcSyncAlpha(rangeInput, mode, varName) {
    var card = rangeInput.closest('.branding-theme-color');
    if (!card) return;
    var numInput = card.querySelector('input[type="number"]');
    if (numInput) numInput.value = rangeInput.value;
}

function resetSingleDcColor(btn) {
    var card = btn.closest('.branding-theme-color');
    if (!card) return;
    var colorPicker = card.querySelector('input[type="color"]');
    var hexText = card.querySelector('input[type="text"]');
    var rangeInput = card.querySelector('input[type="range"]');
    var numInput = card.querySelector('input[type="number"]');

    if (colorPicker && colorPicker.dataset.defaultHex) colorPicker.value = colorPicker.dataset.defaultHex;
    if (hexText && hexText.dataset.defaultHex) hexText.value = hexText.dataset.defaultHex;
    if (rangeInput && rangeInput.dataset.defaultAlpha) rangeInput.value = rangeInput.dataset.defaultAlpha;
    if (numInput && numInput.dataset.defaultAlpha) numInput.value = numInput.dataset.defaultAlpha;
}

function resetAllDcColors() {
    document.querySelectorAll('#vtab-dashboard-colors .branding-theme-color').forEach(function(card) {
        var colorPicker = card.querySelector('input[type="color"]');
        var hexText = card.querySelector('input[type="text"]');
        var rangeInput = card.querySelector('input[type="range"]');
        var numInput = card.querySelector('input[type="number"]');

        if (colorPicker && colorPicker.dataset.defaultHex) colorPicker.value = colorPicker.dataset.defaultHex;
        if (hexText && hexText.dataset.defaultHex) hexText.value = hexText.dataset.defaultHex;
        if (rangeInput && rangeInput.dataset.defaultAlpha) rangeInput.value = rangeInput.dataset.defaultAlpha;
        if (numInput && numInput.dataset.defaultAlpha) numInput.value = numInput.dataset.defaultAlpha;
    });
}

function confirmResetAllDcColors() {
    showDanger(
        'Reset dashboard colours?',
        'This will revert all shadcn UI colour variables to their defaults.',
        { confirmText: 'Reset to Default' }
    ).then(function(confirmed) {
        if (confirmed) { resetAllDcColors(); saveForm(); }
    });
}

(function() {
    var form = document.getElementById('systemSettingsForm');
    if (!form) return;

    var submitting = false;
    var mainBtn = document.getElementById('systemSettingsSaveButton');
    var sectionBtns = form.querySelectorAll('button[type="submit"]');

    form.addEventListener('submit', function(e) {
        if (submitting) {
            e.preventDefault();
            return;
        }
        e.preventDefault();
        submitting = true;

        sectionBtns.forEach(function(b) { b.disabled = true; });
        var origHTML = mainBtn ? mainBtn.innerHTML : '';
        if (mainBtn) mainBtn.innerHTML = 'Saving...';

        fetch(form.getAttribute('action'), {
            method: 'POST',
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            body: new FormData(form)
        }).then(function(r) {
            if (r.ok) {
                return r.json().then(function(d) {
                    showToast(d.message, 'success');
                    setTimeout(function() {
                        fetch('{{ route($dashboardRoute::name('settings.system.clear-config-cache')) }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            }
                        }).catch(function() {});
                    }, 500);
                });
            }
            if (r.status === 422) {
                return r.json().then(function(d) {
                    var errs = Object.values(d.errors || {});
                    showToast(errs.length ? errs[0][0] : 'Validation failed.', 'error');
                });
            }
            return r.json().then(function(d) {
                showToast(d.message || 'Server error.', 'error');
            });
        }).catch(function() {
            showToast('Network error. Please try again.', 'error');
        }).finally(function() {
            submitting = false;
            sectionBtns.forEach(function(b) { b.disabled = false; });
            if (mainBtn) mainBtn.innerHTML = origHTML;
        });
    });
})();

// ── Conditional Field Visibility ──────────────────────────────
(function() {
    var pairs = [
        { toggle: 'TYRO_LOGIN_OTP_ENABLED', target: 'otp-details-surface' },
        { toggle: 'TYRO_LOGIN_2FA_ENABLED', target: 'twofa-details-surface' },
        { toggle: 'TYRO_LOGIN_SOCIAL_ENABLED', target: 'social-details-surface' },
        { toggle: 'TYRO_LOGIN_LOCKOUT_ENABLED', target: 'lockout-details-surface' },
        { toggle: 'TYRO_AUDIT_ENABLED', target: 'tyro_audit_retention_group' },
    ];

    function updateConditionalVisibility() {
        pairs.forEach(function(p) {
            var checkbox = document.getElementById(p.toggle);
            var target = document.getElementById(p.target);
            if (!checkbox || !target) return;
            target.style.display = checkbox.checked ? '' : 'none';
        });
    }

    pairs.forEach(function(p) {
        var checkbox = document.getElementById(p.toggle);
        if (checkbox) {
            checkbox.addEventListener('change', updateConditionalVisibility);
        }
    });

    updateConditionalVisibility();

    // Captcha details: show if either captcha login or captcha register is enabled
    var captchaSurface = document.getElementById('captcha-details-surface');
    if (captchaSurface) {
        var cl = document.getElementById('TYRO_LOGIN_CAPTCHA_LOGIN');
        var cr = document.getElementById('TYRO_LOGIN_CAPTCHA_REGISTER');
        function updateCaptcha() {
            captchaSurface.style.display = (cl && cl.checked) || (cr && cr.checked) ? '' : 'none';
        }
        if (cl) cl.addEventListener('change', updateCaptcha);
        if (cr) cr.addEventListener('change', updateCaptcha);
        updateCaptcha();
    }
})();
</script>
