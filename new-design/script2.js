const themeToggle = document.getElementById('themeToggle');
const spotlight = document.getElementById('spotlight');
const mobileMenuBtn = document.getElementById('mobileMenuBtn');
const navbarLinks = document.getElementById('navbarLinks');

const savedTheme = localStorage.getItem('tyro-landing-theme');
if (savedTheme === 'dark') {
    document.documentElement.setAttribute('data-theme', 'dark');
}

themeToggle?.addEventListener('click', () => {
    const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
    if (isDark) {
        document.documentElement.removeAttribute('data-theme');
        localStorage.setItem('tyro-landing-theme', 'light');
        return;
    }

    document.documentElement.setAttribute('data-theme', 'dark');
    localStorage.setItem('tyro-landing-theme', 'dark');
});

document.addEventListener('mousemove', (event) => {
    if (!spotlight) {
        return;
    }

    spotlight.style.display = 'block';
    spotlight.style.left = `${event.clientX}px`;
    spotlight.style.top = `${event.clientY}px`;
});

mobileMenuBtn?.addEventListener('click', () => {
    navbarLinks?.classList.toggle('open');
});

navbarLinks?.querySelectorAll('a').forEach((link) => {
    link.addEventListener('click', () => {
        navbarLinks.classList.remove('open');
    });
});

// FAQ Accordion
document.querySelectorAll('.faq-q').forEach(button => {
    button.addEventListener('click', () => {
        const row = button.parentElement;
        const isOpen = row.classList.contains('open');
        
        // Close all other FAQs
        document.querySelectorAll('.faq-row').forEach(r => r.classList.remove('open'));
        
        if (!isOpen) {
            row.classList.add('open');
        }
    });
});

// Copy to Clipboard
function copyToClipboard(btn, text) {
    navigator.clipboard.writeText(text).then(() => {
        const originalContent = btn.innerHTML;
        btn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>';
        btn.style.color = '#22c55e';
        
        setTimeout(() => {
            btn.innerHTML = originalContent;
            btn.style.color = '';
        }, 2000);
    });
}

// Lightbox
const lbOverlay = document.getElementById('lbOverlay');
const lbImg = document.getElementById('lbImg');
const lbClose = document.getElementById('lbClose');

document.querySelectorAll('.ss-card img').forEach(img => {
    img.addEventListener('click', () => {
        lbImg.src = img.src;
        lbOverlay.classList.add('open');
    });
});

lbClose?.addEventListener('click', () => {
    lbOverlay.classList.remove('open');
});

lbOverlay?.addEventListener('click', (e) => {
    if (e.target === lbOverlay) {
        lbOverlay.classList.remove('open');
    }
});