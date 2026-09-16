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

  // Botón flotante de contacto (WhatsApp + teléfono)
  const fab = document.querySelector('[data-fab]');
  const fabToggle = fab?.querySelector('[data-fab-toggle]');
  const fabActions = fab?.querySelector('[data-fab-actions]');
  fabToggle?.addEventListener('click', () => {
    const open = fab.classList.toggle('is-open');
    fabToggle.setAttribute('aria-expanded', String(open));
    if (fabActions) fabActions.hidden = !open;
  });
  document.addEventListener('click', (e) => {
    if (fab && fab.classList.contains('is-open') && !fab.contains(e.target)) {
      fab.classList.remove('is-open');
      fabToggle?.setAttribute('aria-expanded', 'false');
      if (fabActions) fabActions.hidden = true;
    }
  });

  // Helper genérico de modal (bloquea scroll, cierra con overlay/Escape)
  const openModal = (el) => { if (!el) return; el.hidden = false; document.body.style.overflow = 'hidden'; };
  const closeModal = (el) => { if (!el) return; el.hidden = true; document.body.style.overflow = ''; };

  // Modal de Emergencia
  const emergencyModal = document.querySelector('[data-emergency-modal]');
  document.querySelectorAll('[data-emergency-open]').forEach((btn) =>
    btn.addEventListener('click', () => openModal(emergencyModal)));
  emergencyModal?.querySelectorAll('[data-emergency-close]').forEach((btn) =>
    btn.addEventListener('click', () => closeModal(emergencyModal)));

  // Lightbox de video (YouTube sin controles)
  const videoModal = document.querySelector('[data-video-modal]');
  const videoPlayer = videoModal?.querySelector('[data-video-player]');
  const closeVideo = () => { if (videoPlayer) videoPlayer.innerHTML = ''; closeModal(videoModal); };
  document.querySelectorAll('[data-video-open]').forEach((btn) =>
    btn.addEventListener('click', () => {
      const id = btn.getAttribute('data-video-id');
      if (!id || !videoPlayer) return;
      videoPlayer.innerHTML = '<iframe src="https://www.youtube-nocookie.com/embed/' + id +
        '?autoplay=1&controls=0&rel=0&modestbranding=1&playsinline=1&disablekb=1" title="Video Hidrocinco" ' +
        'allow="autoplay; encrypted-media; fullscreen" allowfullscreen frameborder="0"></iframe>';
      openModal(videoModal);
    }));
  videoModal?.querySelectorAll('[data-video-close]').forEach((btn) =>
    btn.addEventListener('click', closeVideo));

  document.addEventListener('keydown', (e) => {
    if (e.key !== 'Escape') return;
    if (emergencyModal && !emergencyModal.hidden) closeModal(emergencyModal);
    if (videoModal && !videoModal.hidden) closeVideo();
  });
})();
