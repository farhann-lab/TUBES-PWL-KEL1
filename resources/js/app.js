import './bootstrap';
import './elco-alert';
import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

document.addEventListener('pointerdown', (event) => {
    const link = event.target.closest?.('.elco-bottom-nav-item');

    if (!link) return;
    if (event.button !== 0 || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) return;

    const href = link.getAttribute('href');
    if (!href || href === '#') return;

    const target = new URL(href, window.location.origin);
    const current = new URL(window.location.href);

    if (target.pathname === current.pathname && target.search === current.search) return;

    event.preventDefault();
    window.location.assign(target.href);
}, true);
