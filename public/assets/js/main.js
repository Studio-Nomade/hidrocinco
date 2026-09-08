(() => {
  const toggle = document.querySelector('.nav-toggle');
  const nav = document.querySelector('.main-nav');
  const header = document.querySelector('[data-site-header]');

  toggle?.addEventListener('click', () => {
    const open = toggle.getAttribute('aria-expanded') === 'true';
    toggle.setAttribute('aria-expanded', String(!open));
    nav?.classList.toggle('is-open', !open);
  });

  nav?.addEventListener('click', (event) => {
    if (event.target instanceof HTMLAnchorElement) {
      toggle?.setAttribute('aria-expanded', 'false');
      nav.classList.remove('is-open');
    }
  });

  const syncHeader = () => header?.classList.toggle('is-scrolled', window.scrollY > 12);
  syncHeader();
  window.addEventListener('scroll', syncHeader, { passive: true });
})();
