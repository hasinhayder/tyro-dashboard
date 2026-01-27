/**
 * Tyro Dashboard Landing Page - Interactive JavaScript
 * 
 * Features:
 * - Theme toggle (dark/light mode)
 * - Mobile menu
 * - FAQ accordion
 * - Screenshots slider with dots navigation
 * - Lightbox for images
 * - Copy to clipboard
 * - Smooth scrolling
 * - Intersection observer animations
 * - Parallax effects
 * - Navbar scroll effects
 */

(function () {
    'use strict';

    // ===== Theme Toggle =====
    const themeToggle = document.getElementById('themeToggle');
    const prefersDarkScheme = window.matchMedia('(prefers-color-scheme: dark)');

    function getStoredTheme() {
        return localStorage.getItem('theme');
    }

    function setTheme(theme) {
        document.documentElement.setAttribute('data-theme', theme);
        localStorage.setItem('theme', theme);
    }

    function initTheme() {
        const storedTheme = getStoredTheme();
        if (storedTheme) {
            setTheme(storedTheme);
        } else if (prefersDarkScheme.matches) {
            setTheme('dark');
        } else {
            setTheme('light');
        }
    }

    if (themeToggle) {
        themeToggle.addEventListener('click', () => {
            const currentTheme = document.documentElement.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            setTheme(newTheme);
        });
    }

    // Listen for system theme changes
    prefersDarkScheme.addEventListener('change', (e) => {
        if (!getStoredTheme()) {
            setTheme(e.matches ? 'dark' : 'light');
        }
    });

    initTheme();

    // ===== Mobile Menu =====
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const navbarLinks = document.querySelector('.navbar-links');

    if (mobileMenuBtn && navbarLinks) {
        mobileMenuBtn.addEventListener('click', () => {
            mobileMenuBtn.classList.toggle('active');
            navbarLinks.classList.toggle('active');
        });

        // Close mobile menu when clicking on a link
        navbarLinks.querySelectorAll('.navbar-link').forEach(link => {
            link.addEventListener('click', () => {
                mobileMenuBtn.classList.remove('active');
                navbarLinks.classList.remove('active');
            });
        });

        // Close mobile menu when clicking outside
        document.addEventListener('click', (e) => {
            if (!mobileMenuBtn.contains(e.target) && !navbarLinks.contains(e.target)) {
                mobileMenuBtn.classList.remove('active');
                navbarLinks.classList.remove('active');
            }
        });
    }

    // ===== Navbar Scroll Effect =====
    const navbar = document.querySelector('.navbar');

    if (navbar) {
        let lastScrollY = window.scrollY;

        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }

            // Hide/show navbar on scroll (skip on documentation pages)
            if (!document.body.classList.contains('doc-page')) {
                if (window.scrollY > lastScrollY && window.scrollY > 200) {
                    navbar.style.transform = 'translateY(-100%)';
                } else {
                    navbar.style.transform = 'translateY(0)';
                }
            }
            lastScrollY = window.scrollY;
        });
    }

    // ===== FAQ Accordion =====
    const faqItems = document.querySelectorAll('.faq-item');

    faqItems.forEach(item => {
        const question = item.querySelector('.faq-question');

        if (question) {
            question.addEventListener('click', () => {
                const isActive = item.classList.contains('active');

                // Close all other items
                faqItems.forEach(otherItem => {
                    if (otherItem !== item) {
                        otherItem.classList.remove('active');
                        const otherQuestion = otherItem.querySelector('.faq-question');
                        if (otherQuestion) {
                            otherQuestion.setAttribute('aria-expanded', 'false');
                        }
                    }
                });

                // Toggle current item
                item.classList.toggle('active', !isActive);
                question.setAttribute('aria-expanded', !isActive ? 'true' : 'false');
            });
        }
    });

    // ===== Screenshots Slider =====
    const slider = document.querySelector('.screenshots-slider');
    const slides = document.querySelectorAll('.screenshot-card');
    const sliderNav = document.querySelector('.slider-nav');
    const prevBtn = document.querySelector('.slider-prev');
    const nextBtn = document.querySelector('.slider-next');
    const sliderDots = document.querySelector('.slider-dots');

    if (slider && slides.length > 0) {
        let currentSlide = 0;
        const totalSlides = slides.length;
        let slidesPerView = getSlidesPerView();
        let maxSlide = Math.max(0, totalSlides - slidesPerView);
        let autoSlideInterval;
        let isAutoSliding = true;

        function getSlidesPerView() {
            if (window.innerWidth < 768) return 1;
            if (window.innerWidth < 1024) return 2;
            return 3;
        }

        function createDots() {
            if (!sliderDots) return;

            sliderDots.innerHTML = '';
            const dotsCount = Math.min(totalSlides, maxSlide + 1);

            for (let i = 0; i < dotsCount; i++) {
                const dot = document.createElement('button');
                dot.className = 'slider-dot';
                dot.setAttribute('aria-label', `Go to slide ${i + 1}`);
                if (i === 0) dot.classList.add('active');
                dot.addEventListener('click', () => goToSlide(i));
                sliderDots.appendChild(dot);
            }
        }

        function updateDots() {
            if (!sliderDots) return;

            const dots = sliderDots.querySelectorAll('.slider-dot');
            dots.forEach((dot, index) => {
                dot.classList.toggle('active', index === currentSlide);
            });
        }

        function goToSlide(index) {
            currentSlide = Math.max(0, Math.min(index, maxSlide));
            const offset = currentSlide * (slides[0].offsetWidth + 32); // 32px gap
            slider.style.transform = `translateX(-${offset}px)`;
            updateDots();
            updateNavButtons();
        }

        function updateNavButtons() {
            if (prevBtn) {
                prevBtn.style.opacity = currentSlide === 0 ? '0.5' : '1';
            }
            if (nextBtn) {
                nextBtn.style.opacity = currentSlide >= maxSlide ? '0.5' : '1';
            }
        }

        function nextSlide() {
            if (currentSlide < maxSlide) {
                goToSlide(currentSlide + 1);
            } else {
                goToSlide(0); // Loop back to start
            }
        }

        function prevSlide() {
            if (currentSlide > 0) {
                goToSlide(currentSlide - 1);
            } else {
                goToSlide(maxSlide); // Loop to end
            }
        }

        function startAutoSlide() {
            if (!isAutoSliding) return;
            autoSlideInterval = setInterval(nextSlide, 5000);
        }

        function stopAutoSlide() {
            clearInterval(autoSlideInterval);
        }

        // Event listeners
        if (prevBtn) {
            prevBtn.addEventListener('click', () => {
                stopAutoSlide();
                prevSlide();
                startAutoSlide();
            });
        }

        if (nextBtn) {
            nextBtn.addEventListener('click', () => {
                stopAutoSlide();
                nextSlide();
                startAutoSlide();
            });
        }

        // Pause auto-slide on hover
        slider.addEventListener('mouseenter', stopAutoSlide);
        slider.addEventListener('mouseleave', startAutoSlide);

        // Handle window resize
        window.addEventListener('resize', () => {
            slidesPerView = getSlidesPerView();
            maxSlide = Math.max(0, totalSlides - slidesPerView);
            createDots();
            goToSlide(Math.min(currentSlide, maxSlide));
        });

        // Touch/swipe support
        let touchStartX = 0;
        let touchEndX = 0;

        slider.addEventListener('touchstart', (e) => {
            touchStartX = e.changedTouches[0].screenX;
            stopAutoSlide();
        }, { passive: true });

        slider.addEventListener('touchend', (e) => {
            touchEndX = e.changedTouches[0].screenX;
            const swipeThreshold = 50;

            if (touchStartX - touchEndX > swipeThreshold) {
                nextSlide();
            } else if (touchEndX - touchStartX > swipeThreshold) {
                prevSlide();
            }
            startAutoSlide();
        }, { passive: true });

        // Initialize
        createDots();
        updateNavButtons();
        startAutoSlide();
    }

    // ===== Lightbox =====
    const lightbox = document.getElementById('lightbox');
    const lightboxImage = document.getElementById('lightboxImage');
    const lightboxCaption = document.getElementById('lightboxCaption');
    const lightboxClose = document.querySelector('.lightbox-close');
    const lightboxPrev = document.querySelector('.lightbox-prev');
    const lightboxNext = document.querySelector('.lightbox-next');
    const lightboxTriggers = document.querySelectorAll('[data-lightbox]');

    if (lightbox && lightboxImage) {
        let currentImageIndex = 0;
        let lightboxImages = [];

        function openLightbox(index) {
            currentImageIndex = index;
            lightboxImage.src = lightboxImages[currentImageIndex].src;
            lightboxCaption.textContent = lightboxImages[currentImageIndex].caption || '';
            lightbox.classList.add('active');
            document.body.style.overflow = 'hidden';
            updateLightboxNav();
        }

        function closeLightbox() {
            lightbox.classList.remove('active');
            document.body.style.overflow = '';
        }

        function nextImage() {
            currentImageIndex = (currentImageIndex + 1) % lightboxImages.length;
            lightboxImage.src = lightboxImages[currentImageIndex].src;
            lightboxCaption.textContent = lightboxImages[currentImageIndex].caption || '';
            updateLightboxNav();
        }

        function prevImage() {
            currentImageIndex = (currentImageIndex - 1 + lightboxImages.length) % lightboxImages.length;
            lightboxImage.src = lightboxImages[currentImageIndex].src;
            lightboxCaption.textContent = lightboxImages[currentImageIndex].caption || '';
            updateLightboxNav();
        }

        function updateLightboxNav() {
            if (lightboxPrev) {
                lightboxPrev.style.display = lightboxImages.length > 1 ? 'flex' : 'none';
            }
            if (lightboxNext) {
                lightboxNext.style.display = lightboxImages.length > 1 ? 'flex' : 'none';
            }
        }

        // Collect all lightbox images (prefer anchor href, then data-lightbox, then image src)
        lightboxTriggers.forEach((trigger, index) => {
            const img = trigger.querySelector ? trigger.querySelector('img') : null;
            let src = null;

            // If trigger is an anchor, use its href (most documentation/gallery anchors use href)
            if (trigger.tagName && trigger.tagName.toLowerCase() === 'a') {
                src = trigger.getAttribute('href');
            }

            if (!src) {
                src = trigger.getAttribute('data-lightbox') || (img && img.src) || trigger.src || null;
            }

            lightboxImages.push({
                src: src,
                caption: trigger.getAttribute('data-caption') || (img && img.alt) || ''
            });

            trigger.addEventListener('click', (e) => {
                e.preventDefault();
                openLightbox(index);
            });
        });

        // Event listeners
        if (lightboxClose) {
            lightboxClose.addEventListener('click', closeLightbox);
        }

        if (lightboxPrev) {
            lightboxPrev.addEventListener('click', prevImage);
        }

        if (lightboxNext) {
            lightboxNext.addEventListener('click', nextImage);
        }

        // Close on overlay click
        lightbox.addEventListener('click', (e) => {
            if (e.target === lightbox) {
                closeLightbox();
            }
        });

        // Keyboard navigation
        document.addEventListener('keydown', (e) => {
            if (!lightbox.classList.contains('active')) return;

            switch (e.key) {
                case 'Escape':
                    closeLightbox();
                    break;
                case 'ArrowLeft':
                    prevImage();
                    break;
                case 'ArrowRight':
                    nextImage();
                    break;
            }
        });
    }

    // ===== Copy to Clipboard =====
    window.copyToClipboard = function (button, text) {
        navigator.clipboard.writeText(text).then(() => {
            const originalText = button.textContent;
            button.textContent = 'Copied!';
            button.classList.add('copied');

            setTimeout(() => {
                button.textContent = originalText;
                button.classList.remove('copied');
            }, 2000);
        }).catch(err => {
            console.error('Failed to copy:', err);
        });
    };

    // Initialize copy buttons for code blocks
    document.querySelectorAll('.doc-code-copy').forEach(button => {
        button.addEventListener('click', function () {
            const codeBlock = this.closest('.doc-code-block');
            if (codeBlock) {
                const codeElement = codeBlock.querySelector('code');
                if (codeElement) {
                    const text = codeElement.textContent;
                    window.copyToClipboard(this, text);
                }
            }
        });
    });

    // ===== Smooth Scrolling =====
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const href = this.getAttribute('href');
            if (href === '#') return;

            const target = document.querySelector(href);
            if (target) {
                e.preventDefault();
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // ===== Intersection Observer Animations =====
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const fadeInObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                fadeInObserver.unobserve(entry.target);
            }
        });
    }, observerOptions);

    // Observe elements with animation classes
    document.querySelectorAll('.feature-card, .step-item, .faq-item, .security-card').forEach(el => {
        el.classList.add('fade-in');
        fadeInObserver.observe(el);
    });

    // ===== Parallax Effects =====
    const orbs = document.querySelectorAll('.orb');

    if (orbs.length > 0) {
        let ticking = false;

        window.addEventListener('scroll', () => {
            if (!ticking) {
                window.requestAnimationFrame(() => {
                    const scrollY = window.scrollY;
                    orbs.forEach((orb, index) => {
                        const speed = (index + 1) * 0.1;
                        orb.style.transform = `translateY(${scrollY * speed}px)`;
                    });
                    ticking = false;
                });
                ticking = true;
            }
        });
    }

    // ===== Counter Animation =====
    function animateCounter(element, start, end, duration) {
        let startTimestamp = null;
        const step = (timestamp) => {
            if (!startTimestamp) startTimestamp = timestamp;
            const progress = Math.min((timestamp - startTimestamp) / duration, 1);
            const value = Math.floor(progress * (end - start) + start);
            element.textContent = value.toLocaleString();
            if (progress < 1) {
                window.requestAnimationFrame(step);
            }
        };
        window.requestAnimationFrame(step);
    }

    // Observe counter elements
    const counters = document.querySelectorAll('[data-counter]');
    const counterObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const target = parseInt(entry.target.getAttribute('data-counter'));
                animateCounter(entry.target, 0, target, 2000);
                counterObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.5 });

    counters.forEach(counter => counterObserver.observe(counter));

    // ===== Konami Code Easter Egg =====
    const konamiCode = ['ArrowUp', 'ArrowUp', 'ArrowDown', 'ArrowDown', 'ArrowLeft', 'ArrowRight', 'ArrowLeft', 'ArrowRight', 'b', 'a'];
    let konamiIndex = 0;

    document.addEventListener('keydown', (e) => {
        if (e.key === konamiCode[konamiIndex]) {
            konamiIndex++;
            if (konamiIndex === konamiCode.length) {
                document.body.style.transition = 'transform 1s ease';
                document.body.style.transform = 'rotate(360deg)';
                setTimeout(() => {
                    document.body.style.transform = '';
                }, 1000);
                konamiIndex = 0;
            }
        } else {
            konamiIndex = 0;
        }
    });

    // ===== Social Share =====
    window.shareOnTwitter = function () {
        const text = 'Check out Tyro Dashboard - A beautiful admin dashboard for Laravel 12!';
        const url = window.location.href;
        window.open(`https://twitter.com/intent/tweet?text=${encodeURIComponent(text)}&url=${encodeURIComponent(url)}`, '_blank');
    };

    window.shareOnLinkedIn = function () {
        const url = window.location.href;
        window.open(`https://www.linkedin.com/sharing/share-offsite/?url=${encodeURIComponent(url)}`, '_blank');
    };

    window.shareOnFacebook = function () {
        const url = window.location.href;
        window.open(`https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(url)}`, '_blank');
    };

    // ===== Preloader =====
    window.addEventListener('load', () => {
        const preloader = document.querySelector('.preloader');
        if (preloader) {
            preloader.classList.add('hidden');
            setTimeout(() => {
                preloader.remove();
            }, 500);
        }
    });

    // ===== Console Easter Egg =====
    console.log(
        '%c Tyro Dashboard %c Made with ❤️ for Laravel ',
        'background: linear-gradient(135deg, #2563eb, #3b82f6); color: white; padding: 10px 20px; border-radius: 5px 0 0 5px; font-size: 14px; font-weight: bold;',
        'background: #1e293b; color: #94a3b8; padding: 10px 20px; border-radius: 0 5px 5px 0; font-size: 14px;'
    );

})();

// ===== Additional Styles for Animations =====
const animationStyles = document.createElement('style');
animationStyles.textContent = `
    .fade-in {
        opacity: 0;
        transform: translateY(30px);
        transition: opacity 0.6s ease, transform 0.6s ease;
    }

    .fade-in.visible {
        opacity: 1;
        transform: translateY(0);
    }

    .navbar {
        transition: transform 0.3s ease, background 0.3s ease, box-shadow 0.3s ease;
    }

    .navbar.scrolled {
        background: var(--color-surface);
        box-shadow: var(--shadow-lg);
    }

    .copy-btn.copied {
        background: #22c55e !important;
        color: white !important;
    }

    /* Mobile Menu Styles */
    @media (max-width: 768px) {
        .navbar-links {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: var(--color-surface);
            flex-direction: column;
            padding: var(--spacing-lg);
            gap: var(--spacing-md);
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s ease;
            border-top: 1px solid var(--color-border);
            box-shadow: var(--shadow-lg);
        }

        .navbar-links.active {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .mobile-menu-btn {
            display: flex;
            flex-direction: column;
            justify-content: center;
            gap: 5px;
            width: 30px;
            height: 30px;
            background: transparent;
            border: none;
            cursor: pointer;
            z-index: 1001;
        }

        .mobile-menu-btn span {
            display: block;
            width: 100%;
            height: 2px;
            background: var(--color-text);
            transition: all 0.3s ease;
        }

        .mobile-menu-btn.active span:nth-child(1) {
            transform: rotate(45deg) translate(5px, 5px);
        }

        .mobile-menu-btn.active span:nth-child(2) {
            opacity: 0;
        }

        .mobile-menu-btn.active span:nth-child(3) {
            transform: rotate(-45deg) translate(5px, -5px);
        }
    }

    @media (min-width: 769px) {
        .mobile-menu-btn {
            display: none;
        }
    }
`;
document.head.appendChild(animationStyles);
