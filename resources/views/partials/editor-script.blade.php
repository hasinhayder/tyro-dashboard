{{--
    Markdown editor (EasyMDE) bootstrap for <x-tyro-dashboard-editor>.
    - Auto-initializes every [data-tyro-editor] textarea on DOMContentLoaded.
    - When window.TyroDashboardMediaPicker exists, the toolbar "image" button opens
      the Tyro media picker and inserts ![alt](url) at the caret; otherwise EasyMDE's
      stock image behavior is left untouched.
    - Public API (documented consumer surface): window.TyroDashboardEditor.init(el)
      re-initializes a dynamically added editor. Internals are not public API.
    - EasyMDE's Ctrl+Alt+I image shortcut is intentionally not intercepted (only the
      toolbar button is); consumers can disable it via :options="['shortcuts' => ['image' => null]]".
--}}
<script>
    (function () {
        if (window.TyroDashboardEditor) {
            return;
        }

        var instances = [];

        function isPlainObject(value) {
            return value !== null && typeof value === 'object' && !Array.isArray(value);
        }

        function deepMerge(target, source) {
            Object.keys(source).forEach(function (key) {
                var value = source[key];
                if (isPlainObject(value) && isPlainObject(target[key])) {
                    deepMerge(target[key], value);
                } else {
                    target[key] = value;
                }
            });
            return target;
        }

        function parseJson(value, fallback) {
            if (value === undefined || value === null || value === '') {
                return fallback;
            }
            try {
                return JSON.parse(value);
            } catch (error) {
                return fallback;
            }
        }

        function openMediaPicker(editor, textarea) {
            if (!window.TyroDashboardMediaPicker) {
                return;
            }

            var cm = editor && editor.codemirror;
            if (!cm) {
                return;
            }

            var probe = document.createElement('input');
            probe.type = 'hidden';
            probe.dataset.tyroMediaOutput = (textarea && textarea.dataset.output) || 'select';
            probe.dataset.tyroMediaFullUrl = (textarea && textarea.dataset.fullUrl) === 'false' ? 'false' : 'true';
            if (textarea && textarea.dataset.columns) {
                probe.dataset.tyroMediaColumns = textarea.dataset.columns;
            }

            var observer = null;
            var finished = false;

            function cleanup() {
                if (observer) {
                    observer.disconnect();
                    observer = null;
                }
            }

            probe.addEventListener('change', function () {
                finished = true;

                var url = (probe.value || '').trim();
                if (url) {
                    var selection = cm.getSelection();
                    var alt = selection && selection.indexOf('\n') === -1 ? selection : '';
                    cm.focus();
                    cm.replaceSelection('![' + alt + '](' + url + ')');
                }

                cleanup();
            });

            var modal = document.getElementById('tyroDashboardMediaPickerModal');
            if (modal && typeof MutationObserver !== 'undefined') {
                observer = new MutationObserver(function () {
                    if (!finished && !modal.classList.contains('open')) {
                        cleanup();
                    }
                });
                observer.observe(modal, { attributes: true, attributeFilter: ['class'] });
            }

            window.TyroDashboardMediaPicker.openForInput(probe);
        }

        function makeImageAction(textarea) {
            return {
                name: 'image',
                className: 'fa fa-image',
                title: 'Insert Image',
                action: function (editor) {
                    openMediaPicker(editor, textarea);
                },
            };
        }

        function injectImageAction(toolbar, textarea) {
            if (!Array.isArray(toolbar)) {
                return toolbar;
            }

            return toolbar.map(function (item) {
                if (item === 'image') {
                    return makeImageAction(textarea);
                }
                if (item && typeof item === 'object' && item.name === 'image') {
                    return Object.assign({}, item, {
                        className: item.className || 'fa fa-image',
                        title: item.title || 'Insert Image',
                        action: function (editor) {
                            openMediaPicker(editor, textarea);
                        },
                    });
                }
                return item;
            });
        }

        function buildConfig(textarea) {
            var ds = textarea.dataset;
            var config = {
                element: textarea,
                spellChecker: ds.spellchecker === 'true',
                status: parseJson(ds.status, false),
            };

            if (ds.height) {
                config.minHeight = ds.height;
            }

            var toolbar = parseJson(ds.toolbar, null);
            if (Array.isArray(toolbar)) {
                config.toolbar = toolbar;
            }

            var options = parseJson(ds.options, null);
            if (isPlainObject(options)) {
                config = deepMerge(config, options);
            }

            // element is never overridable.
            config.element = textarea;

            // Only replace the image button when the media picker is available;
            // otherwise leave EasyMDE's stock behavior so the editor never breaks.
            if (window.TyroDashboardMediaPicker) {
                config.toolbar = injectImageAction(config.toolbar, textarea);
            }

            return config;
        }

        function init(element) {
            var textarea = typeof element === 'string' ? document.querySelector(element) : element;

            if (!textarea || textarea.dataset.tyroEditorInitialized === 'true') {
                return null;
            }

            if (typeof window.EasyMDE === 'undefined') {
                return null;
            }

            var editor = new window.EasyMDE(buildConfig(textarea));
            textarea.dataset.tyroEditorInitialized = 'true';
            instances.push(editor);

            // Defensive sync: keep the underlying textarea current even when the
            // consumer did not opt into EasyMDE's forceSync option.
            if (editor.codemirror && typeof editor.codemirror.on === 'function') {
                editor.codemirror.on('change', function () {
                    textarea.value = editor.codemirror.getValue();
                });
            }

            return editor;
        }

        function autoInit() {
            var nodes = document.querySelectorAll('[data-tyro-editor]');
            for (var i = 0; i < nodes.length; i++) {
                init(nodes[i]);
            }
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', autoInit);
        } else {
            autoInit();
        }

        window.TyroDashboardEditor = {
            init: init,
            instances: instances,
        };
    })();
</script>
