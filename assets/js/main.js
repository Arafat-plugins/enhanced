(function () {
	"use strict";

	document.documentElement.classList.add("js");

	const settings = window.EnhancedSettings || {};

	const onReady = function (fn) {
		document.readyState === "loading"
			? document.addEventListener("DOMContentLoaded", fn, { once: true })
			: fn();
	};

	onReady(function () {
		initMobileMenu();
		initSearchDrawer();
		initShopSidebar();
		initSidebarAccordions();
		initFilterGroups();
		initPriceSlider();
		initFilterSearch();
		initQuantityButtons();
		initProductTabs();
	});

	// ── Mobile nav ─────────────────────────────────────────────
	function initMobileMenu() {
		const toggle = document.querySelector("[data-menu-toggle]");
		const panel  = document.querySelector("[data-menu-panel]");
		if (!toggle || !panel) return;

		const close = function () {
			toggle.setAttribute("aria-expanded", "false");
			panel.classList.remove("is-open");
			document.body.classList.remove("menu-open");
		};

		toggle.addEventListener("click", function () {
			const open = toggle.getAttribute("aria-expanded") === "true";
			toggle.setAttribute("aria-expanded", String(!open));
			panel.classList.toggle("is-open", !open);
			document.body.classList.toggle("menu-open", !open);
		});

		document.addEventListener("keydown", function (e) { if (e.key === "Escape") close(); });
		window.addEventListener("resize", function () {
			if (window.innerWidth > (settings.mobileBreakpoint || 960)) close();
		});
	}

	// ── Search drawer ──────────────────────────────────────────
	function initSearchDrawer() {
		const drawer   = document.querySelector("[data-search-drawer]");
		const triggers = document.querySelectorAll("[data-search-trigger]");
		const closers  = document.querySelectorAll("[data-search-close]");
		const input    = document.querySelector("[data-search-input]");
		if (!drawer) return;

		const open = function () {
			drawer.hidden = false;
			void drawer.offsetWidth;
			drawer.classList.add("is-open");
			document.body.classList.add("search-open");
			setTimeout(function () { if (input) input.focus(); }, 80);
		};

		const close = function () {
			drawer.classList.remove("is-open");
			document.body.classList.remove("search-open");
			setTimeout(function () { drawer.hidden = true; }, 260);
		};

		triggers.forEach(function (el) { el.addEventListener("click", open); });
		closers.forEach(function (el)  { el.addEventListener("click", close); });
		document.addEventListener("keydown", function (e) {
			if (e.key === "Escape" && !drawer.hidden) close();
		});
	}

	// ── Shop sidebar (mobile drawer) ───────────────────────────
	function initShopSidebar() {
		const sidebar  = document.getElementById("shop-sidebar");
		const toggles  = document.querySelectorAll("[data-sidebar-toggle]");
		const closers  = document.querySelectorAll("[data-sidebar-close]");
		const overlay  = document.querySelector("[data-sidebar-overlay]");
		if (!sidebar) return;

		const open = function () {
			sidebar.classList.add("is-open");
			if (overlay) overlay.classList.add("is-visible");
			toggles.forEach(function (t) { t.setAttribute("aria-expanded", "true"); });
			document.body.style.overflow = "hidden";
		};

		const close = function () {
			sidebar.classList.remove("is-open");
			if (overlay) overlay.classList.remove("is-visible");
			toggles.forEach(function (t) { t.setAttribute("aria-expanded", "false"); });
			document.body.style.overflow = "";
		};

		toggles.forEach(function (t) { t.addEventListener("click", open); });
		closers.forEach(function (t) { t.addEventListener("click", close); });
		if (overlay) overlay.addEventListener("click", close);
		document.addEventListener("keydown", function (e) {
			if (e.key === "Escape" && sidebar.classList.contains("is-open")) close();
		});
	}

	// ── Sidebar filter group accordions ────────────────────────
	function initSidebarAccordions() {
		document.querySelectorAll(".shop-sidebar .widget").forEach(function (widget) {
			const title = widget.querySelector(".widget__title");
			if (!title) return;

			// Default: open
			widget.classList.remove("is-collapsed");

			title.setAttribute("role", "button");
			title.setAttribute("tabindex", "0");

			const toggle = function () {
				widget.classList.toggle("is-collapsed");
			};

			title.addEventListener("click", toggle);
			title.addEventListener("keydown", function (e) {
				if (e.key === "Enter" || e.key === " ") { e.preventDefault(); toggle(); }
			});
		});
	}

	// ── Custom filter group collapse ───────────────────────────
	function initFilterGroups() {
		document.querySelectorAll(".en-group").forEach(function (group) {
			const head = group.querySelector(".en-group__head");
			const body = group.querySelector(".en-group__body");
			if (!head || !body) return;

			// Measure natural height once to enable smooth transitions.
			const setNaturalHeight = function () {
				if (group.dataset.open === "false") return;
				body.style.maxHeight = body.scrollHeight + "px";
			};
			requestAnimationFrame(setNaturalHeight);

			head.addEventListener("click", function () {
				const open = group.dataset.open !== "false";
				if (open) {
					body.style.maxHeight = body.scrollHeight + "px";
					requestAnimationFrame(function () { body.style.maxHeight = "0px"; });
					group.dataset.open = "false";
					head.setAttribute("aria-expanded", "false");
				} else {
					group.dataset.open = "true";
					head.setAttribute("aria-expanded", "true");
					body.style.maxHeight = body.scrollHeight + "px";
				}
			});

			body.addEventListener("transitionend", function (e) {
				if (e.propertyName !== "max-height") return;
				if (group.dataset.open !== "false") body.style.maxHeight = "none";
			});
		});
	}

	// ── Dual-handle price slider ───────────────────────────────
	function initPriceSlider() {
		document.querySelectorAll("[data-price-form]").forEach(function (form) {
			const track    = form.querySelector("[data-price-track]");
			const fill     = form.querySelector("[data-price-fill]");
			const handles  = form.querySelectorAll("[data-price-handle]");
			const inputMin = form.querySelector('[data-price-input="min"]');
			const inputMax = form.querySelector('[data-price-input="max"]');
			const dispMin  = form.querySelector('[data-price-display="min"]');
			const dispMax  = form.querySelector('[data-price-display="max"]');
			if (!track || !fill || handles.length !== 2) return;

			const MIN = Number(form.dataset.min);
			const MAX = Number(form.dataset.max);
			const currency = form.dataset.currency || "$";
			let cMin = Number(form.dataset.curMin);
			let cMax = Number(form.dataset.curMax);
			const range = Math.max(1, MAX - MIN);

			const fmt = function (n) {
				return currency + Math.round(n).toLocaleString();
			};
			const pct = function (v) { return ((v - MIN) / range) * 100; };

			const paint = function () {
				const lo = pct(cMin);
				const hi = pct(cMax);
				fill.style.left  = lo + "%";
				fill.style.width = (hi - lo) + "%";
				handles[0].style.left = lo + "%";
				handles[1].style.left = hi + "%";
				if (inputMin) inputMin.value = Math.round(cMin);
				if (inputMax) inputMax.value = Math.round(cMax);
				if (dispMin) dispMin.textContent = fmt(cMin);
				if (dispMax) dispMax.textContent = fmt(cMax);
			};

			paint();

			let active = null;

			const positionFromEvent = function (e) {
				const rect = track.getBoundingClientRect();
				const x = (e.touches ? e.touches[0].clientX : e.clientX) - rect.left;
				const p = Math.max(0, Math.min(1, x / rect.width));
				return MIN + p * range;
			};

			const onMove = function (e) {
				if (!active) return;
				const v = positionFromEvent(e);
				if (active.dataset.priceHandle === "min") {
					cMin = Math.min(v, cMax - 1);
					cMin = Math.max(MIN, cMin);
				} else {
					cMax = Math.max(v, cMin + 1);
					cMax = Math.min(MAX, cMax);
				}
				paint();
			};

			const onUp = function () {
				if (!active) return;
				active.classList.remove("is-active");
				active = null;
				document.removeEventListener("mousemove", onMove);
				document.removeEventListener("mouseup", onUp);
				document.removeEventListener("touchmove", onMove);
				document.removeEventListener("touchend", onUp);
				document.body.style.userSelect = "";
			};

			handles.forEach(function (h) {
				const onDown = function (e) {
					e.preventDefault();
					active = h;
					h.classList.add("is-active");
					h.focus();
					document.body.style.userSelect = "none";
					document.addEventListener("mousemove", onMove);
					document.addEventListener("mouseup", onUp);
					document.addEventListener("touchmove", onMove, { passive: false });
					document.addEventListener("touchend", onUp);
				};
				h.addEventListener("mousedown", onDown);
				h.addEventListener("touchstart", onDown, { passive: false });

				h.addEventListener("keydown", function (e) {
					const step = Math.max(1, Math.round(range / 100));
					let delta = 0;
					if (e.key === "ArrowLeft" || e.key === "ArrowDown") delta = -step;
					if (e.key === "ArrowRight" || e.key === "ArrowUp")  delta = step;
					if (!delta) return;
					e.preventDefault();
					if (h.dataset.priceHandle === "min") {
						cMin = Math.max(MIN, Math.min(cMax - 1, cMin + delta));
					} else {
						cMax = Math.min(MAX, Math.max(cMin + 1, cMax + delta));
					}
					paint();
				});
			});

			// Click on track jumps nearest handle.
			track.addEventListener("mousedown", function (e) {
				if (e.target !== track && !e.target.classList.contains("en-price__rail") && !e.target.classList.contains("en-price__fill")) return;
				const v = positionFromEvent(e);
				const nearest = Math.abs(v - cMin) < Math.abs(v - cMax) ? handles[0] : handles[1];
				active = nearest;
				nearest.classList.add("is-active");
				document.body.style.userSelect = "none";
				onMove(e);
				document.addEventListener("mousemove", onMove);
				document.addEventListener("mouseup", onUp);
			});
		});
	}

	// ── Debounced search auto-submit + clear ───────────────────
	function initFilterSearch() {
		const form = document.querySelector("[data-filter-search]");
		if (!form) return;
		const input = form.querySelector(".en-search__input");
		const clear = form.querySelector("[data-search-clear]");
		if (!input) return;

		let timer;
		input.addEventListener("input", function () {
			clearTimeout(timer);
			timer = setTimeout(function () { form.submit(); }, 520);
		});
		input.addEventListener("keydown", function (e) {
			if (e.key === "Enter") { e.preventDefault(); clearTimeout(timer); form.submit(); }
		});

		if (clear) {
			clear.addEventListener("click", function () {
				input.value = "";
				form.submit();
			});
		}
	}

	// ── Quantity +/- ───────────────────────────────────────────
	function initQuantityButtons() {
		document.querySelectorAll(".quantity-wrap").forEach(function (wrap) {
			const input = wrap.querySelector("input[type='number']");
			if (!input) return;

			wrap.querySelectorAll("button").forEach(function (btn) {
				btn.addEventListener("click", function () {
					const step = Number(input.step || 1);
					const min  = Number(input.min  || 1);
					const max  = Number(input.max  || Infinity);
					const dir  = btn.dataset.dir === "minus" ? -1 : 1;
					const next = Math.min(max, Math.max(min, Number(input.value) + step * dir));
					input.value = next;
					input.dispatchEvent(new Event("change", { bubbles: true }));
				});
			});
		});
	}

	// ── Product tabs ───────────────────────────────────────────
	function initProductTabs() {
		document.querySelectorAll(".product-tabs").forEach(function (root) {
			const tabs   = root.querySelectorAll(".product-tabs__tab");
			const panels = root.querySelectorAll(".product-tabs__panel");

			tabs.forEach(function (tab, i) {
				tab.addEventListener("click", function () {
					tabs.forEach(function (t)   { t.classList.remove("is-active"); });
					panels.forEach(function (p) { p.classList.remove("is-active"); });
					tab.classList.add("is-active");
					if (panels[i]) panels[i].classList.add("is-active");
				});
			});
		});
	}
})();
