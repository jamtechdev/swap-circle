import '../css/landing.css';
import './cookie-consent.js';
import AOS from 'aos';
import 'aos/dist/aos.css';

document.addEventListener('DOMContentLoaded', () => {
    AOS.init({
        duration: 800,
        easing: 'ease-out-cubic',
        once: true,
        offset: 60,
    });

    initNavbar();
    initMobileMenu();
    initCounters();
    initSmoothScroll();
    initImageFallbacks();
});

function initNavbar() {
    const nav = document.getElementById('scNav');
    if (!nav) return;

    const scrolled = [
        'bg-white/95',
        'backdrop-blur-md',
        'shadow-md',
    ];

    const onScroll = () => {
        const isScrolled = window.scrollY > 40;
        scrolled.forEach((cls) => nav.classList.toggle(cls, isScrolled));
        nav.classList.toggle('is-scrolled', isScrolled);

        nav.querySelectorAll('[data-nav-link]').forEach((link) => {
            link.classList.toggle('md:text-gray-800', isScrolled);
            link.classList.toggle('md:text-white/90', !isScrolled);
        });

        nav.querySelectorAll('[data-nav-login]').forEach((btn) => {
            btn.classList.toggle('border-forest', isScrolled);
            btn.classList.toggle('text-forest', isScrolled);
            btn.classList.toggle('hover:bg-lime-soft', isScrolled);
            btn.classList.toggle('border-white/80', !isScrolled);
            btn.classList.toggle('text-white', !isScrolled);
            btn.classList.toggle('hover:bg-white/10', !isScrolled);
        });

        nav.querySelectorAll('[data-nav-user]').forEach((btn) => {
            btn.classList.toggle('border-forest', isScrolled);
            btn.classList.toggle('text-forest', isScrolled);
            btn.classList.toggle('bg-lime-soft/60', isScrolled);
            btn.classList.toggle('hover:bg-lime-soft', isScrolled);
            btn.classList.toggle('border-white/80', !isScrolled);
            btn.classList.toggle('text-white', !isScrolled);
            btn.classList.toggle('hover:bg-white/10', !isScrolled);
        });

        nav.querySelectorAll('[data-nav-brand]').forEach((el) => {
            el.classList.toggle('text-forest', isScrolled);
            el.classList.toggle('text-white', !isScrolled);
        });

        const toggle = nav.querySelector('[data-nav-toggle]');
        if (toggle) {
            toggle.querySelectorAll('span').forEach((bar) => {
                bar.classList.toggle('bg-forest', isScrolled);
                bar.classList.toggle('bg-white', !isScrolled);
            });
        }
    };

    onScroll();
    window.addEventListener('scroll', onScroll, { passive: true });
}

function initMobileMenu() {
    const toggle = document.getElementById('scNavToggle');
    const menu = document.getElementById('scNavMenu');
    if (!toggle || !menu) return;

    toggle.addEventListener('click', () => {
        const open = menu.classList.toggle('max-h-96');
        menu.classList.toggle('max-h-0', !open);
        menu.classList.toggle('opacity-100', open);
        menu.classList.toggle('opacity-0', !open);
        menu.classList.toggle('pointer-events-none', !open);
        toggle.classList.toggle('is-active');
        document.body.style.overflow = open ? 'hidden' : '';
    });

    menu.querySelectorAll('a').forEach((link) => {
        link.addEventListener('click', () => {
            menu.classList.add('max-h-0', 'opacity-0', 'pointer-events-none');
            menu.classList.remove('max-h-96', 'opacity-100');
            toggle.classList.remove('is-active');
            document.body.style.overflow = '';
        });
    });
}

function initCounters() {
    const counters = document.querySelectorAll('[data-count]');
    if (!counters.length) return;

    const animate = (el) => {
        const target = parseInt(el.dataset.count, 10);
        const suffix = el.dataset.suffix || '';
        const prefix = el.dataset.prefix || '';
        const duration = 1800;
        const start = performance.now();

        const step = (now) => {
            const progress = Math.min((now - start) / duration, 1);
            const eased = 1 - Math.pow(1 - progress, 3);
            el.textContent = prefix + Math.floor(eased * target).toLocaleString() + suffix;
            if (progress < 1) requestAnimationFrame(step);
        };

        requestAnimationFrame(step);
    };

    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    animate(entry.target);
                    observer.unobserve(entry.target);
                }
            });
        },
        { threshold: 0.4 }
    );

    counters.forEach((counter) => observer.observe(counter));
}

function initImageFallbacks() {
    document.querySelectorAll('[data-fallback-image]').forEach((img) => {
        img.addEventListener('error', () => {
            img.remove();
            const wrap = img.closest('.relative, .overflow-hidden');
            if (wrap) {
                wrap.classList.add('bg-linear-to-br', 'from-forest/20', 'via-lime/20', 'to-forest/10');
            }
        }, { once: true });
    });
}

function initSmoothScroll() {
    document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
        anchor.addEventListener('click', (e) => {
            const id = anchor.getAttribute('href');
            if (!id || id === '#') return;
            const target = document.querySelector(id);
            if (!target) return;
            e.preventDefault();
            window.scrollTo({
                top: target.getBoundingClientRect().top + window.scrollY - 80,
                behavior: 'smooth',
            });
        });
    });
}
