<script>
    // Theme management
    function getTheme() {
        if (localStorage.getItem('tyro-dashboard-theme')) {
            return localStorage.getItem('tyro-dashboard-theme');
        }
        return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
    }

    function setTheme(theme) {
        localStorage.setItem('tyro-dashboard-theme', theme);
        document.documentElement.classList.remove('light', 'dark');
        document.documentElement.classList.add(theme);
        updateThemeIcons(theme);
    }

    function toggleTheme() {
        const currentTheme = getTheme();
        setTheme(currentTheme === 'dark' ? 'light' : 'dark');
    }

    function updateThemeIcons(theme) {
        const sunIcons = document.querySelectorAll('.sun-icon');
        const moonIcons = document.querySelectorAll('.moon-icon');
        
        sunIcons.forEach(icon => {
            icon.style.display = theme === 'dark' ? 'block' : 'none';
        });
        moonIcons.forEach(icon => {
            icon.style.display = theme === 'dark' ? 'none' : 'block';
        });
    }

    // Sidebar toggle
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.querySelector('.sidebar-overlay');
        sidebar.classList.toggle('active');
        overlay.classList.toggle('active');
    }

    // User dropdown
    function toggleUserDropdown() {
        const dropdown = document.getElementById('userDropdown');
        dropdown.classList.toggle('active');
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
        const dropdown = document.getElementById('userDropdown');
        if (dropdown && !dropdown.contains(event.target)) {
            dropdown.classList.remove('active');
        }
    });

    // Apply theme on load
    setTheme(getTheme());

    // Listen for system theme changes
    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
        if (!localStorage.getItem('tyro-dashboard-theme')) {
            setTheme(e.matches ? 'dark' : 'light');
        }
    });

    // Auto-dismiss flash messages
    document.addEventListener('DOMContentLoaded', function() {
        const alerts = document.querySelectorAll('.alert');
        const dismissTime = {{ config('tyro-dashboard.notifications.auto_dismiss_seconds', 5) * 1000 }};
        
        alerts.forEach(alert => {
            setTimeout(() => {
                alert.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                alert.style.opacity = '0';
                alert.style.transform = 'translateY(-10px)';
                setTimeout(() => alert.remove(), 300);
            }, dismissTime);
        });
    });

    // Confirm delete
    function confirmDelete(message = 'Are you sure you want to delete this item?') {
        return confirm(message);
    }

    // Modal functions
    function openModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
    }

    function closeModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.remove('active');
            document.body.style.overflow = '';
        }
    }

    // Close modal on overlay click
    document.addEventListener('click', function(event) {
        if (event.target.classList.contains('modal-overlay')) {
            event.target.classList.remove('active');
            document.body.style.overflow = '';
        }
    });

    // Close modal on escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            const activeModal = document.querySelector('.modal-overlay.active');
            if (activeModal) {
                activeModal.classList.remove('active');
                document.body.style.overflow = '';
            }
        }
    });
</script>
