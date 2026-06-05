// GSAP-powered scroll animations & micro-interactions.
// Convention-based: add data attributes in Blade, no per-element JS needed.
//
//   data-animate="up|down|left|right|zoom|fade"   single element reveal
//   data-delay="0.2"                              stagger/offset (seconds)
//   data-animate-group                            stagger children on reveal
//   data-count="1200" data-suffix="+"            animated number counter
//   data-parallax="0.2"                           subtle scroll parallax (speed)
//
// FOUC is prevented by `.js [data-animate] { opacity: 0 }` in app.css combined
// with the inline `documentElement.classList.add('js')` in the layout head.

import gsap from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

const FROM = {
    up: { y: 48 },
    down: { y: -48 },
    left: { x: 64 },
    right: { x: -64 },
    zoom: { scale: 0.9 },
    fade: {},
};

export default function initAnimations() {
    const reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    // Reduced motion (or a safety net): just reveal everything instantly.
    if (reduce) {
        gsap.set('[data-animate], [data-animate-group] > *', { opacity: 1, clearProps: 'transform' });
        document.querySelectorAll('[data-count]').forEach((el) => {
            el.textContent = Number(el.dataset.count || 0).toLocaleString() + (el.dataset.suffix || '');
        });
        return;
    }

    // 1. Single-element reveals.
    // Explicit set()+to() (rather than from()) so the end state is always the
    // natural layout — never affected by inherited opacity — and reveals are
    // deterministic on load, scroll, or deep-link.
    gsap.utils.toArray('[data-animate]').forEach((el) => {
        const vars = FROM[el.dataset.animate] || FROM.up;
        gsap.set(el, { opacity: 0, ...vars });
        ScrollTrigger.create({
            trigger: el,
            start: 'top 85%',
            once: true,
            onEnter: () => gsap.to(el, {
                opacity: 1, x: 0, y: 0, scale: 1,
                duration: 0.9, ease: 'power3.out',
                delay: parseFloat(el.dataset.delay) || 0,
            }),
        });
    });

    // 2. Staggered groups (card grids, feature lists…)
    gsap.utils.toArray('[data-animate-group]').forEach((group) => {
        const items = gsap.utils.toArray(group.children);
        gsap.set(items, { opacity: 0, y: 40 });
        ScrollTrigger.create({
            trigger: group,
            start: 'top 82%',
            once: true,
            onEnter: () => gsap.to(items, {
                opacity: 1, y: 0,
                duration: 0.7, ease: 'power3.out', stagger: 0.12,
            }),
        });
    });

    // 3. Animated number counters
    gsap.utils.toArray('[data-count]').forEach((el) => {
        const target = parseFloat(el.dataset.count) || 0;
        const suffix = el.dataset.suffix || '';
        const counter = { v: 0 };
        gsap.to(counter, {
            v: target,
            duration: 2,
            ease: 'power2.out',
            scrollTrigger: { trigger: el, start: 'top 90%', once: true },
            onUpdate() {
                el.textContent = Math.round(counter.v).toLocaleString() + suffix;
            },
        });
    });

    // 4. Subtle parallax on decorated elements
    gsap.utils.toArray('[data-parallax]').forEach((el) => {
        const speed = parseFloat(el.dataset.parallax) || 0.2;
        gsap.to(el, {
            yPercent: speed * 100,
            ease: 'none',
            scrollTrigger: {
                trigger: el.closest('section') || el,
                start: 'top bottom',
                end: 'bottom top',
                scrub: true,
            },
        });
    });

    // Recalculate once images have loaded (heights shift triggers otherwise).
    window.addEventListener('load', () => ScrollTrigger.refresh());
}
