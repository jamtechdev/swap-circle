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

    initMobileMenu();
    initCounters();
    initSmoothScroll();
    initImageFallbacks();
    initInsightsCarousel();
});

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

function initInsightsCarousel() {
    const root = document.querySelector('[data-insights-carousel]');
    if (!root) return;

    const track = root.querySelector('[data-carousel-track]');
    const prevBtn = root.querySelector('[data-carousel-prev]');
    const nextBtn = root.querySelector('[data-carousel-next]');
    const dotsWrap = root.querySelector('[data-carousel-dots]');
    const slides = [...root.querySelectorAll('.sc-insights-carousel__slide')];

    if (!track || slides.length === 0) return;

    let activeIndex = 0;

    const getSlidesPerView = () => {
        if (window.matchMedia('(min-width: 1024px)').matches) return 3;
        if (window.matchMedia('(min-width: 768px)').matches) return 2;
        return 1;
    };

    const getMaxIndex = () => Math.max(0, slides.length - getSlidesPerView());

    const scrollToIndex = (index) => {
        const maxIndex = getMaxIndex();
        activeIndex = Math.max(0, Math.min(index, maxIndex));
        const slide = slides[activeIndex];
        if (!slide) return;

        track.scrollTo({
            left: slide.offsetLeft - track.offsetLeft,
            behavior: window.matchMedia('(prefers-reduced-motion: reduce)').matches ? 'auto' : 'smooth',
        });

        updateUi();
    };

    const updateUi = () => {
        const maxIndex = getMaxIndex();

        if (prevBtn) prevBtn.disabled = activeIndex <= 0;
        if (nextBtn) nextBtn.disabled = activeIndex >= maxIndex;

        dotsWrap?.querySelectorAll('[data-carousel-dot]').forEach((dot, index) => {
            dot.classList.toggle('is-active', index === activeIndex);
            dot.setAttribute('aria-selected', index === activeIndex ? 'true' : 'false');
        });
    };

    const buildDots = () => {
        if (!dotsWrap) return;

        dotsWrap.innerHTML = '';
        const dotCount = getMaxIndex() + 1;

        for (let i = 0; i < dotCount; i += 1) {
            const dot = document.createElement('button');
            dot.type = 'button';
            dot.className = 'sc-insights-carousel__dot';
            dot.dataset.carouselDot = String(i);
            dot.setAttribute('role', 'tab');
            dot.setAttribute('aria-label', `Go to insight ${i + 1}`);
            dot.addEventListener('click', () => scrollToIndex(i));
            dotsWrap.appendChild(dot);
        }
    };

    const syncFromScroll = () => {
        const trackLeft = track.scrollLeft;
        let nearestIndex = 0;
        let nearestDistance = Number.POSITIVE_INFINITY;

        slides.forEach((slide, index) => {
            const distance = Math.abs((slide.offsetLeft - track.offsetLeft) - trackLeft);
            if (distance < nearestDistance) {
                nearestDistance = distance;
                nearestIndex = index;
            }
        });

        activeIndex = Math.min(nearestIndex, getMaxIndex());
        updateUi();
    };

    prevBtn?.addEventListener('click', () => scrollToIndex(activeIndex - 1));
    nextBtn?.addEventListener('click', () => scrollToIndex(activeIndex + 1));

    track.addEventListener('scroll', () => {
        window.clearTimeout(track._insightsScrollTimer);
        track._insightsScrollTimer = window.setTimeout(syncFromScroll, 80);
    }, { passive: true });

    track.addEventListener('keydown', (event) => {
        if (event.key === 'ArrowLeft') {
            event.preventDefault();
            scrollToIndex(activeIndex - 1);
        }
        if (event.key === 'ArrowRight') {
            event.preventDefault();
            scrollToIndex(activeIndex + 1);
        }
    });

    window.addEventListener('resize', () => {
        buildDots();
        scrollToIndex(activeIndex);
    });

    buildDots();
    updateUi();
}
