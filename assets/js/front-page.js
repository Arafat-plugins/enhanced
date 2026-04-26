/**
 * Enhanced — Front Page JS (Luxina layout)
 * Scroll reveal · rail scroll · subnav active
 */
(function () {
  'use strict';

  const ready = function (fn) {
    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', fn, { once: true });
    } else { fn(); }
  };

  /* ── Scroll Reveal ───────────────────────────────────────── */
  function initReveal() {
    const els = document.querySelectorAll('.fp-reveal');
    if (!els.length) return;
    const io = new IntersectionObserver(function (entries) {
      entries.forEach(function (e) {
        if (e.isIntersecting) {
          e.target.classList.add('is-visible');
          io.unobserve(e.target);
        }
      });
    }, { threshold: 0.08 });
    els.forEach(function (el) { io.observe(el); });
  }

  /* ── Rail scroll arrows ──────────────────────────────────── */
  function initRailArrows() {
    document.querySelectorAll('.lx-product-rail').forEach(function (section) {
      var track = section.querySelector('[data-lx-rail]');
      var prev  = section.querySelector('[data-lx-rail-prev]');
      var next  = section.querySelector('[data-lx-rail-next]');
      if (!track) return;
      var cardW = 240;
      prev && prev.addEventListener('click', function () {
        track.scrollBy({ left: -cardW, behavior: 'smooth' });
      });
      next && next.addEventListener('click', function () {
        track.scrollBy({ left: cardW, behavior: 'smooth' });
      });
    });
  }

  /* ── Category nav arrows ─────────────────────────────────── */
  function initCatArrows() {
    var track = document.querySelector('[data-lx-cat-track]');
    var prev  = document.querySelector('[data-lx-cat-prev]');
    var next  = document.querySelector('[data-lx-cat-next]');
    if (!track) return;
    prev && prev.addEventListener('click', function () {
      track.scrollBy({ left: -260, behavior: 'smooth' });
    });
    next && next.addEventListener('click', function () {
      track.scrollBy({ left: 260, behavior: 'smooth' });
    });
  }

  /* ── Subnav active on click ──────────────────────────────── */
  function initSubnav() {
    document.querySelectorAll('.lx-subnav__link').forEach(function (link) {
      link.addEventListener('click', function () {
        document.querySelectorAll('.lx-subnav__link').forEach(function (l) {
          l.classList.remove('active');
        });
        link.classList.add('active');
      });
    });
  }

  /* ── Sticky header shadow ────────────────────────────────── */
  function initStickyHeader() {
    var header = document.querySelector('.site-header');
    if (!header) return;
    window.addEventListener('scroll', function () {
      header.classList.toggle('scrolled', window.scrollY > 40);
    }, { passive: true });
  }

  /* ── Video BG fade in ────────────────────────────────────── */
  function initVideoBg() {
    document.querySelectorAll('.lx-hero-left__model[autoplay]').forEach(function (v) {
      if (v.readyState >= 3) { v.style.opacity = '1'; }
      else {
        v.addEventListener('canplay', function () { v.style.opacity = '1'; }, { once: true });
      }
    });
  }

  function initHeroEmbedAutoplay() {
    function sendMessage(frame, payload) {
      try {
        if (frame.contentWindow) {
          frame.contentWindow.postMessage(JSON.stringify(payload), '*');
        }
      } catch (err) {
        /* noop */
      }
    }

    document.querySelectorAll('.lx-hero-left__embed iframe[data-hero-autoplay-provider]').forEach(function (frame) {
      var provider = frame.getAttribute('data-hero-autoplay-provider');

      if (!provider) return;

      function nudgePlayback() {
        if (provider === 'youtube') {
          sendMessage(frame, { event: 'command', func: 'mute', args: [] });
          sendMessage(frame, { event: 'command', func: 'playVideo', args: [] });
        } else if (provider === 'vimeo') {
          sendMessage(frame, { method: 'setVolume', value: 0 });
          sendMessage(frame, { method: 'setLoop', value: true });
          sendMessage(frame, { method: 'play' });
        }
      }

      function startNudges() {
        var tries = 0;

        function tick() {
          tries += 1;
          nudgePlayback();

          if (tries < 6) {
            window.setTimeout(tick, 900);
          }
        }

        tick();
      }

      frame.addEventListener('load', startNudges, { once: true });
      window.setTimeout(startNudges, 500);
    });
  }

  /* ── Right panel sliders (TR + BR — randomised transitions) ─ */
  function initRpSliders() {
    var STYLES  = ['fade', 'slide-right', 'slide-left', 'slide-up', 'slide-down', 'zoom-in', 'zoom-out', 'rotate'];
    var ANIM_MS = 860;

    document.querySelectorAll('[data-lx-rp-slider]').forEach(function (slider) {
      var slides    = slider.querySelectorAll('.lx-rp-slide');
      var total     = slides.length;
      if (total <= 1) return;

      var current   = 0;
      var lastStyle = '';
      var interval  = Math.max(2000, parseInt(slider.getAttribute('data-interval'), 10) || 4000);

      function clearAnim(slide) {
        STYLES.forEach(function (s) {
          slide.classList.remove('anim-in--' + s, 'anim-out--' + s);
        });
      }

      function pickStyle() {
        var pool = STYLES.filter(function (s) { return s !== lastStyle; });
        return pool[Math.floor(Math.random() * pool.length)];
      }

      setInterval(function () {
        var prev  = current;
        current   = (current + 1) % total;

        var style = pickStyle();
        lastStyle = style;

        clearAnim(slides[prev]);
        clearAnim(slides[current]);

        slides[current].classList.add('anim-in--' + style);
        slides[prev].classList.add('anim-out--' + style);

        slides[prev].classList.remove('is-active');
        slides[prev].classList.add('is-leaving');
        slides[current].classList.add('is-active');

        setTimeout(function () {
          slides[prev].classList.remove('is-leaving');
          clearAnim(slides[prev]);
        }, ANIM_MS);
      }, interval);
    });
  }

  /* ── Category tiles pagination + mobile carousel dots ──── */
  function initCatPaginate() {
    var wrap = document.querySelector('[data-lx-cat-paginate]');
    if (!wrap) return;

    var perPage   = parseInt(wrap.getAttribute('data-lx-cat-paginate'), 10) || 5;
    var tiles     = Array.from(wrap.querySelectorAll('.lx-cat-tile'));
    var total     = tiles.length;
    var nav       = document.querySelector('.lx-cat-pagination');
    if (!nav || total <= perPage) return;

    var pageCount = Math.ceil(total / perPage);
    var current   = 0;
    var dots      = [];

    for (var p = 0; p < pageCount; p++) {
      var btn = document.createElement('button');
      btn.className = 'lx-cat-pag-dot';
      btn.setAttribute('aria-label', 'Page ' + (p + 1));
      btn.dataset.page = p;
      nav.appendChild(btn);
      dots.push(btn);
    }

    function isMobile() { return window.innerWidth <= 860; }

    function setDot(page) {
      dots.forEach(function (d, i) { d.classList.toggle('is-active', i === page); });
    }

    function showPage(page) {
      current = page;
      tiles.forEach(function (tile, i) {
        var on = i >= page * perPage && i < (page + 1) * perPage;
        tile.classList.toggle('is-cat-hidden', !on);
        if (on) {
          tile.classList.add('is-cat-entering');
          tile.addEventListener('animationend', function () {
            tile.classList.remove('is-cat-entering');
          }, { once: true });
        } else {
          tile.classList.remove('is-cat-entering');
        }
      });
      setDot(page);
    }

    dots.forEach(function (btn) {
      btn.addEventListener('click', function () {
        var page = parseInt(btn.dataset.page, 10);
        if (isMobile()) {
          var maxScroll = wrap.scrollWidth - wrap.clientWidth;
          var target = pageCount > 1 ? maxScroll * (page / (pageCount - 1)) : 0;
          wrap.scrollTo({ left: target, behavior: 'smooth' });
        } else {
          showPage(page);
        }
      });
    });

    wrap.addEventListener('scroll', function () {
      if (!isMobile()) return;
      var maxScroll = wrap.scrollWidth - wrap.clientWidth;
      if (maxScroll > 0) {
        var page = Math.round((wrap.scrollLeft / maxScroll) * (pageCount - 1));
        setDot(Math.max(0, Math.min(pageCount - 1, page)));
      }
    }, { passive: true });

    showPage(0);
  }

  /* ── Touch swipe on hero rail ────────────────────────────── */
  function initHeroTouch() {
    var rails = document.querySelectorAll('.lx-sale-rail, .lx-rail-track');
    rails.forEach(function (rail) {
      var startX = 0;
      rail.addEventListener('touchstart', function (e) {
        startX = e.touches[0].clientX;
      }, { passive: true });
      rail.addEventListener('touchend', function (e) {
        var dx = e.changedTouches[0].clientX - startX;
        if (Math.abs(dx) > 40) {
          rail.scrollBy({ left: dx < 0 ? 260 : -260, behavior: 'smooth' });
        }
      }, { passive: true });
    });
  }

  /* ── Newsletter form feedback ────────────────────────────── */
  function initNewsletter() {
    var form = document.querySelector('.fp2-newsletter__form');
    if (!form) return;
    form.addEventListener('submit', function (e) {
      e.preventDefault();
      var btn = form.querySelector('.fp2-newsletter__btn');
      if (btn) {
        var orig = btn.textContent;
        btn.textContent = 'Subscribed!';
        btn.style.background = '#2d6a4f';
        setTimeout(function () {
          btn.textContent = orig;
          btn.style.background = '';
        }, 3000);
      }
    });
  }

  /* ── Init ────────────────────────────────────────────────── */
  ready(function () {
    initReveal();
    initRailArrows();
    initCatArrows();
    initSubnav();
    initStickyHeader();
    initVideoBg();
    initHeroEmbedAutoplay();
    initRpSliders();
    initCatPaginate();
    initHeroTouch();
    initNewsletter();
  });

})();
