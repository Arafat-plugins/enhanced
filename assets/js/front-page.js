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

  /* ── Right panel sliders (TR + BR auto-fade) ────────────── */
  function initRpSliders() {
    document.querySelectorAll('[data-lx-rp-slider]').forEach(function (slider) {
      var slides  = slider.querySelectorAll('.lx-rp-slide');
      var total   = slides.length;
      if (total <= 1) return;

      var current  = 0;
      var interval = Math.max(2000, parseInt(slider.getAttribute('data-interval'), 10) || 4000);
      var FADE_MS  = 750;

      setInterval(function () {
        var prev    = current;
        current     = (current + 1) % total;

        slides[prev].classList.remove('is-active');
        slides[prev].classList.add('is-leaving');
        slides[current].classList.add('is-active');

        setTimeout(function () {
          slides[prev].classList.remove('is-leaving');
        }, FADE_MS);
      }, interval);
    });
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
    initHeroTouch();
    initNewsletter();
  });

})();
