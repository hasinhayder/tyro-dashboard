<script src="https://cdn.jsdelivr.net/npm/quill@1.3.7/dist/quill.min.js"></script>
<script>
    (function () {
        function initQuill() {
            var editorEl = document.getElementById('td-quill-editor');
            if (!editorEl || typeof window.Quill === 'undefined') return;

            var quill = new window.Quill(editorEl, {
                theme: 'snow',
                modules: {
                    toolbar: [
                        ['bold', 'italic', 'underline'],
                        [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                        ['link'],
                        ['clean']
                    ]
                }
            });

            // Seed initial content
            try {
                var initialNode = document.getElementById('td-quill-initial');
                var initialHtml = '';
                if (initialNode && initialNode.textContent) {
                    initialHtml = JSON.parse(initialNode.textContent);
                }
                if (initialHtml) {
                    quill.clipboard.dangerouslyPasteHTML(initialHtml);
                }
            } catch (e) {
                // no-op
            }

            var output = document.getElementById('td-quill-html');
            function sync() {
                if (!output) return;
                output.value = quill.root.innerHTML;
            }
            quill.on('text-change', sync);
            sync();
        }

        function initTabs() {
            document.querySelectorAll('[data-td-tabset]').forEach(function (tabset) {
                var links = tabset.querySelectorAll('[data-td-tab]');
                var panels = tabset.querySelectorAll('[data-td-tab-panel]');

                function activate(name) {
                    links.forEach(function (link) {
                        link.classList.toggle('active', link.getAttribute('data-td-tab') === name);
                    });
                    panels.forEach(function (panel) {
                        panel.style.display = panel.getAttribute('data-td-tab-panel') === name ? '' : 'none';
                    });
                }

                links.forEach(function (link) {
                    link.addEventListener('click', function (e) {
                        e.preventDefault();
                        activate(link.getAttribute('data-td-tab'));
                    });
                });

                var initial = (links[0] && links[0].getAttribute('data-td-tab')) || 'overview';
                activate(initial);
            });
        }

        function initDropdowns() {
            document.querySelectorAll('[data-td-dropdown]').forEach(function (dropdown) {
                var btn = dropdown.querySelector('[data-td-dropdown-btn]');
                if (!btn) return;

                btn.addEventListener('click', function (e) {
                    e.preventDefault();
                    e.stopPropagation();

                    document.querySelectorAll('[data-td-dropdown].active').forEach(function (open) {
                        if (open !== dropdown) open.classList.remove('active');
                    });
                    dropdown.classList.toggle('active');
                });
            });

            document.addEventListener('click', function (event) {
                document.querySelectorAll('[data-td-dropdown].active').forEach(function (open) {
                    if (!open.contains(event.target)) {
                        open.classList.remove('active');
                    }
                });
            });
        }

        document.addEventListener('DOMContentLoaded', function () {
            initTabs();
            initDropdowns();
            initQuill();
        });
    })();
</script>
