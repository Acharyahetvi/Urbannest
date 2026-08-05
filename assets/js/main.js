/* UrbanNest — interactions (Vanilla ES6) */
(() => {
    'use strict';

    /* ---------- Navbar shadow on scroll ---------- */
    const nav = document.querySelector('.un-navbar');
    const scrollBtn = document.getElementById('unScrollTop');
    const onScroll = () => {
        const y = window.scrollY;
        nav?.classList.toggle('scrolled', y > 40);
        scrollBtn?.classList.toggle('show', y > 320);
    };
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();

    scrollBtn?.addEventListener('click', (e) => {
        e.preventDefault();
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });

    /* ---------- Reveal on scroll ---------- */
    const revealTargets = document.querySelectorAll('.reveal, .un-card, .un-feature, .un-quote, .un-blog-card, .un-agent-card, .un-cat-tile, .un-city-card');
    if ('IntersectionObserver' in window && revealTargets.length) {
        revealTargets.forEach(el => el.classList.add('reveal'));
        const io = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    io.unobserve(entry.target);
                }
            });
        }, { threshold: 0.12 });
        revealTargets.forEach(el => io.observe(el));
    }

    /* ---------- Wishlist / compare toggle ---------- */
    document.addEventListener('click', (e) => {
        const btn = e.target.closest('.un-icon-btn[data-toggle]');
        if (!btn) return;
        e.preventDefault();
        btn.classList.toggle('active');
        const icon = btn.querySelector('i');
        if (btn.dataset.toggle === 'wishlist' && icon) {
            icon.classList.toggle('fa-regular');
            icon.classList.toggle('fa-solid');
        }
    });

    /* ---------- Counter animation ---------- */
    const counters = document.querySelectorAll('[data-count]');
    if (counters.length && 'IntersectionObserver' in window) {
        const cio = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (!entry.isIntersecting) return;
                const el = entry.target;
                const target = +el.dataset.count;
                const duration = 1500;
                const start = performance.now();
                const step = (t) => {
                    const p = Math.min((t - start) / duration, 1);
                    const eased = 1 - Math.pow(1 - p, 3);
                    el.textContent = Math.floor(target * eased).toLocaleString();
                    if (p < 1) requestAnimationFrame(step);
                    else el.textContent = target.toLocaleString() + (el.dataset.suffix || '');
                };
                requestAnimationFrame(step);
                cio.unobserve(el);
            });
        }, { threshold: 0.4 });
        counters.forEach(c => cio.observe(c));
    }

    /* ---------- Gallery thumbnails ---------- */
    const mainImg = document.getElementById('galleryMain');
    document.querySelectorAll('.un-gallery-thumb').forEach(t => {
        t.addEventListener('click', () => {
            const src = t.dataset.src || t.querySelector('img')?.src;
            if (!src || !mainImg) return;
            mainImg.style.opacity = '0';
            setTimeout(() => {
                mainImg.src = src;
                mainImg.style.opacity = '1';
            }, 200);
            document.querySelectorAll('.un-gallery-thumb').forEach(x => x.classList.remove('active'));
            t.classList.add('active');
        });
    });

    /* ---------- Simple testimonial autoscroll (optional) ---------- */
    const testCarousel = document.getElementById('unTestimonials');
    if (testCarousel) new bootstrap.Carousel(testCarousel, { interval: 6000, pause: 'hover' });

    /* ---------- Year in footer already handled by PHP ---------- */
})();
