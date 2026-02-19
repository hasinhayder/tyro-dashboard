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