(function () {
	"use strict";

	document.documentElement.classList.add("js");

	const settings = window.EnhancedSettings || {};
	const body = document.body;

	const onReady = function (fn) {
		if (document.readyState === "loading") {
			document.addEventListener("DOMContentLoaded", fn, { once: true });
			return;
		}

		fn();
	};

	const getFocusable = function (root) {
		return Array.from(
			root.querySelectorAll(
				'a[href], button:not([disabled]), textarea, input:not([type="hidden"]):not([disabled]), select, [tabindex]:not([tabindex="-1"])'
			)
		).filter(function (element) {
			return !element.hasAttribute("hidden");
		});
	};

	const lockBody = function (key, shouldLock) {
		const currentLocks = (body.dataset.uiLocks || "").split(" ").filter(Boolean);
		const locks = new Set(currentLocks);

		if (shouldLock) {
			locks.add(key);
		} else {
			locks.delete(key);
		}

		body.dataset.uiLocks = Array.from(locks).join(" ");
		body.style.overflow = locks.size ? "hidden" : "";
	};

	const createFocusTrap = function (root) {
		return function (event) {
			if (event.key !== "Tab") {
				return;
			}

			const focusable = getFocusable(root);
			if (!focusable.length) {
				return;
			}

			const first = focusable[0];
			const last = focusable[focusable.length - 1];

			if (event.shiftKey && document.activeElement === first) {
				event.preventDefault();
				last.focus();
			} else if (!event.shiftKey && document.activeElement === last) {
				event.preventDefault();
				first.focus();
			}
		};
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
		initProductGallery();
		initHomeRails();
		initHeroSlider();
		initArrivalShowcase();
	});

	function initMobileMenu() {
		const toggle = document.querySelector("[data-menu-toggle]");
		const panel = document.querySelector("[data-menu-panel]");
		if (!toggle || !panel) return;

		const close = function () {
			toggle.setAttribute("aria-expanded", "false");
			panel.classList.remove("is-open");
			body.classList.remove("menu-open");
			lockBody("menu", false);
		};

		toggle.addEventListener("click", function () {
			const open = toggle.getAttribute("aria-expanded") === "true";
			toggle.setAttribute("aria-expanded", String(!open));
			panel.classList.toggle("is-open", !open);
			body.classList.toggle("menu-open", !open);
			lockBody("menu", !open);
		});

		document.addEventListener("keydown", function (event) {
			if (event.key === "Escape") {
				close();
			}
		});

		window.addEventListener("resize", function () {
			if (window.innerWidth > (settings.mobileBreakpoint || 960)) {
				close();
			}
		});
	}

	function initSearchDrawer() {
		const drawer = document.querySelector("[data-search-drawer]");
		const triggers = document.querySelectorAll("[data-search-trigger]");
		const closers = document.querySelectorAll("[data-search-close]");
		const input = document.querySelector("[data-search-input]");
		if (!drawer) return;

		let lastTrigger = null;
		const trapFocus = createFocusTrap(drawer);

		const open = function (trigger) {
			lastTrigger = trigger || document.activeElement;
			drawer.hidden = false;
			void drawer.offsetWidth;
			drawer.classList.add("is-open");
			body.classList.add("search-open");
			lockBody("search", true);
			document.addEventListener("keydown", trapFocus);
			window.setTimeout(function () {
				if (input) {
					input.focus();
				}
			}, 80);
		};

		const close = function () {
			drawer.classList.remove("is-open");
			body.classList.remove("search-open");
			lockBody("search", false);
			document.removeEventListener("keydown", trapFocus);
			window.setTimeout(function () {
				drawer.hidden = true;
				if (lastTrigger && typeof lastTrigger.focus === "function") {
					lastTrigger.focus();
				}
			}, 260);
		};

		triggers.forEach(function (trigger) {
			trigger.addEventListener("click", function () {
				open(trigger);
			});
		});

		closers.forEach(function (closer) {
			closer.addEventListener("click", close);
		});

		document.addEventListener("keydown", function (event) {
			if (event.key === "Escape" && !drawer.hidden) {
				close();
			}
		});
	}

	function initShopSidebar() {
		const sidebar = document.getElementById("shop-sidebar");
		const toggles = document.querySelectorAll("[data-sidebar-toggle]");
		const closers = document.querySelectorAll("[data-sidebar-close]");
		const overlay = document.querySelector("[data-sidebar-overlay]");
		if (!sidebar) return;

		let lastTrigger = null;
		const trapFocus = createFocusTrap(sidebar);

		const open = function (trigger) {
			lastTrigger = trigger || document.activeElement;
			sidebar.classList.add("is-open");
			if (overlay) overlay.classList.add("is-visible");
			toggles.forEach(function (toggle) {
				toggle.setAttribute("aria-expanded", "true");
			});
			lockBody("filters", true);
			document.addEventListener("keydown", trapFocus);

			const closeButton = sidebar.querySelector("[data-sidebar-close]");
			if (closeButton && window.innerWidth <= 960) {
				window.setTimeout(function () {
					closeButton.focus();
				}, 50);
			}
		};

		const close = function () {
			sidebar.classList.remove("is-open");
			if (overlay) overlay.classList.remove("is-visible");
			toggles.forEach(function (toggle) {
				toggle.setAttribute("aria-expanded", "false");
			});
			lockBody("filters", false);
			document.removeEventListener("keydown", trapFocus);

			if (lastTrigger && typeof lastTrigger.focus === "function") {
				lastTrigger.focus();
			}
		};

		toggles.forEach(function (toggle) {
			toggle.addEventListener("click", function () {
				open(toggle);
			});
		});

		closers.forEach(function (closer) {
			closer.addEventListener("click", close);
		});

		if (overlay) {
			overlay.addEventListener("click", close);
		}

		document.addEventListener("keydown", function (event) {
			if (event.key === "Escape" && sidebar.classList.contains("is-open")) {
				close();
			}
		});

		window.addEventListener("resize", function () {
			if (window.innerWidth > 960) {
				close();
			}
		});
	}

	function initSidebarAccordions() {
		document.querySelectorAll(".shop-sidebar .widget").forEach(function (widget) {
			const title = widget.querySelector(".widget__title");
			if (!title) return;

			widget.classList.remove("is-collapsed");
			title.setAttribute("role", "button");
			title.setAttribute("tabindex", "0");

			const toggle = function () {
				widget.classList.toggle("is-collapsed");
			};

			title.addEventListener("click", toggle);
			title.addEventListener("keydown", function (event) {
				if (event.key === "Enter" || event.key === " ") {
					event.preventDefault();
					toggle();
				}
			});
		});
	}

	function initFilterGroups() {
		document.querySelectorAll(".en-group").forEach(function (group) {
			const head = group.querySelector(".en-group__head");
			const bodyElement = group.querySelector(".en-group__body");
			if (!head || !bodyElement) return;

			const setNaturalHeight = function () {
				if (group.dataset.open === "false") return;
				bodyElement.style.maxHeight = bodyElement.scrollHeight + "px";
			};

			requestAnimationFrame(setNaturalHeight);

			head.addEventListener("click", function () {
				const open = group.dataset.open !== "false";
				if (open) {
					bodyElement.style.maxHeight = bodyElement.scrollHeight + "px";
					requestAnimationFrame(function () {
						bodyElement.style.maxHeight = "0px";
					});
					group.dataset.open = "false";
					head.setAttribute("aria-expanded", "false");
				} else {
					group.dataset.open = "true";
					head.setAttribute("aria-expanded", "true");
					bodyElement.style.maxHeight = bodyElement.scrollHeight + "px";
				}
			});

			bodyElement.addEventListener("transitionend", function (event) {
				if (event.propertyName !== "max-height") {
					return;
				}

				if (group.dataset.open !== "false") {
					bodyElement.style.maxHeight = "none";
				}
			});
		});
	}

	function initPriceSlider() {
		document.querySelectorAll("[data-price-form]").forEach(function (form) {
			const track = form.querySelector("[data-price-track]");
			const fill = form.querySelector("[data-price-fill]");
			const handles = form.querySelectorAll("[data-price-handle]");
			const inputMin = form.querySelector('[data-price-input="min"]');
			const inputMax = form.querySelector('[data-price-input="max"]');
			const displayMin = form.querySelector('[data-price-display="min"]');
			const displayMax = form.querySelector('[data-price-display="max"]');
			if (!track || !fill || handles.length !== 2) return;

			const MIN = Number(form.dataset.min);
			const MAX = Number(form.dataset.max);
			const currency = form.dataset.currency || "$";
			let currentMin = Number(form.dataset.curMin);
			let currentMax = Number(form.dataset.curMax);
			const range = Math.max(1, MAX - MIN);

			const format = function (value) {
				return currency + Math.round(value).toLocaleString();
			};

			const percent = function (value) {
				return ((value - MIN) / range) * 100;
			};

			const paint = function () {
				const low = percent(currentMin);
				const high = percent(currentMax);

				fill.style.left = low + "%";
				fill.style.width = high - low + "%";
				handles[0].style.left = low + "%";
				handles[1].style.left = high + "%";

				if (inputMin) inputMin.value = Math.round(currentMin);
				if (inputMax) inputMax.value = Math.round(currentMax);
				if (displayMin) displayMin.textContent = format(currentMin);
				if (displayMax) displayMax.textContent = format(currentMax);
			};

			paint();

			let activeHandle = null;

			const positionFromEvent = function (event) {
				const rect = track.getBoundingClientRect();
				const clientX = event.touches ? event.touches[0].clientX : event.clientX;
				const x = clientX - rect.left;
				const progress = Math.max(0, Math.min(1, x / rect.width));
				return MIN + progress * range;
			};

			const onMove = function (event) {
				if (!activeHandle) return;
				const value = positionFromEvent(event);

				if (activeHandle.dataset.priceHandle === "min") {
					currentMin = Math.max(MIN, Math.min(currentMax - 1, value));
				} else {
					currentMax = Math.min(MAX, Math.max(currentMin + 1, value));
				}

				paint();
			};

			const onUp = function () {
				if (!activeHandle) return;
				activeHandle.classList.remove("is-active");
				activeHandle = null;
				document.removeEventListener("mousemove", onMove);
				document.removeEventListener("mouseup", onUp);
				document.removeEventListener("touchmove", onMove);
				document.removeEventListener("touchend", onUp);
				body.style.userSelect = "";
			};

			handles.forEach(function (handle) {
				const onDown = function (event) {
					event.preventDefault();
					activeHandle = handle;
					handle.classList.add("is-active");
					handle.focus();
					body.style.userSelect = "none";
					document.addEventListener("mousemove", onMove);
					document.addEventListener("mouseup", onUp);
					document.addEventListener("touchmove", onMove, { passive: false });
					document.addEventListener("touchend", onUp);
				};

				handle.addEventListener("mousedown", onDown);
				handle.addEventListener("touchstart", onDown, { passive: false });

				handle.addEventListener("keydown", function (event) {
					const step = Math.max(1, Math.round(range / 100));
					let delta = 0;

					if (event.key === "ArrowLeft" || event.key === "ArrowDown") delta = -step;
					if (event.key === "ArrowRight" || event.key === "ArrowUp") delta = step;
					if (!delta) return;

					event.preventDefault();

					if (handle.dataset.priceHandle === "min") {
						currentMin = Math.max(MIN, Math.min(currentMax - 1, currentMin + delta));
					} else {
						currentMax = Math.min(MAX, Math.max(currentMin + 1, currentMax + delta));
					}

					paint();
				});
			});

			track.addEventListener("mousedown", function (event) {
				if (
					event.target !== track &&
					!event.target.classList.contains("en-price__rail") &&
					!event.target.classList.contains("en-price__fill")
				) {
					return;
				}

				const value = positionFromEvent(event);
				const nearestHandle =
					Math.abs(value - currentMin) < Math.abs(value - currentMax) ? handles[0] : handles[1];

				activeHandle = nearestHandle;
				nearestHandle.classList.add("is-active");
				body.style.userSelect = "none";
				onMove(event);
				document.addEventListener("mousemove", onMove);
				document.addEventListener("mouseup", onUp);
			});
		});
	}

	function initFilterSearch() {
		const form = document.querySelector("[data-filter-search]");
		if (!form) return;

		const input = form.querySelector(".en-search__input");
		const clearButton = form.querySelector("[data-search-clear]");
		if (!input) return;

		let timer;

		input.addEventListener("input", function () {
			window.clearTimeout(timer);
			timer = window.setTimeout(function () {
				form.submit();
			}, 520);
		});

		input.addEventListener("keydown", function (event) {
			if (event.key === "Enter") {
				event.preventDefault();
				window.clearTimeout(timer);
				form.submit();
			}
		});

		if (clearButton) {
			clearButton.addEventListener("click", function () {
				input.value = "";
				form.submit();
			});
		}
	}

	function initQuantityButtons() {
		document.querySelectorAll(".quantity-wrap").forEach(function (wrap) {
			const input = wrap.querySelector("input[type='number']");
			if (!input) return;

			wrap.querySelectorAll("button").forEach(function (button) {
				button.addEventListener("click", function () {
					const step = Number(input.step || 1);
					const min = Number(input.min || 1);
					const max = Number(input.max || Infinity);
					const direction = button.dataset.dir === "minus" ? -1 : 1;
					const currentValue = Number(input.value || min);
					const next = Math.min(max, Math.max(min, currentValue + step * direction));
					input.value = next;
					input.dispatchEvent(new Event("change", { bubbles: true }));
				});
			});
		});
	}

	function initProductTabs() {
		document.querySelectorAll("[data-product-tabs]").forEach(function (root) {
			const tabs = root.querySelectorAll(".product-tabs__tab");
			const panels = root.querySelectorAll(".product-tabs__panel");

			tabs.forEach(function (tab, index) {
				tab.addEventListener("click", function () {
					tabs.forEach(function (currentTab) {
						currentTab.classList.remove("is-active");
						currentTab.setAttribute("aria-selected", "false");
					});

					panels.forEach(function (panel) {
						panel.classList.remove("is-active");
						panel.hidden = true;
					});

					tab.classList.add("is-active");
					tab.setAttribute("aria-selected", "true");
					if (panels[index]) {
						panels[index].classList.add("is-active");
						panels[index].hidden = false;
					}
				});
			});
		});
	}

	function initProductGallery() {
		document.querySelectorAll("[data-product-gallery]").forEach(function (gallery) {
			const mainImage = gallery.querySelector("[data-gallery-main]");
			const thumbs = gallery.querySelectorAll("[data-gallery-thumb]");
			if (!mainImage || !thumbs.length) return;

			thumbs.forEach(function (thumb) {
				thumb.addEventListener("click", function () {
					const fullSrc = thumb.dataset.fullSrc;
					const alt = thumb.dataset.alt || mainImage.alt;
					if (!fullSrc) return;

					mainImage.src = fullSrc;
					mainImage.alt = alt;

					thumbs.forEach(function (button) {
						button.classList.remove("is-active");
						button.setAttribute("aria-pressed", "false");
					});

					thumb.classList.add("is-active");
					thumb.setAttribute("aria-pressed", "true");
				});
			});
		});
	}

	function initHomeRails() {
		document.querySelectorAll("[data-rail-slider]").forEach(function (slider) {
			const track = slider.querySelector("[data-rail-track]");
			const prevButton = slider.querySelector("[data-rail-prev]");
			const nextButton = slider.querySelector("[data-rail-next]");
			if (!track || !prevButton || !nextButton) return;

			const getStep = function () {
				const firstItem = track.children[0];
				const gap = parseFloat(window.getComputedStyle(track).columnGap || window.getComputedStyle(track).gap || 0);

				if (!firstItem) {
					return track.clientWidth * 0.82;
				}

				return firstItem.getBoundingClientRect().width + gap;
			};

			const updateButtons = function () {
				const maxScroll = Math.max(0, track.scrollWidth - track.clientWidth - 4);
				prevButton.disabled = track.scrollLeft <= 4;
				nextButton.disabled = track.scrollLeft >= maxScroll;
			};

			prevButton.addEventListener("click", function () {
				track.scrollBy({ left: -getStep() * 1.4, behavior: "smooth" });
			});

			nextButton.addEventListener("click", function () {
				track.scrollBy({ left: getStep() * 1.4, behavior: "smooth" });
			});

			track.addEventListener("scroll", updateButtons, { passive: true });
			window.addEventListener("resize", updateButtons);
			updateButtons();
		});
	}

	function initHeroSlider() {
		initMediaShowcase("[data-hero-slider]", "[data-hero-slide]", "[data-hero-thumb]", 5200);
	}

	function initArrivalShowcase() {
		initMediaShowcase("[data-arrival-showcase]", "[data-arrival-slide]", "[data-arrival-thumb]", 4600);
	}

	function initMediaShowcase(rootSelector, slideSelector, thumbSelector, autoplayDelay) {
		document.querySelectorAll(rootSelector).forEach(function (root) {
			const slides = Array.from(root.querySelectorAll(slideSelector));
			const thumbs = Array.from(root.querySelectorAll(thumbSelector));
			if (slides.length <= 1 || thumbs.length !== slides.length) return;

			let activeIndex = Math.max(
				0,
				slides.findIndex(function (slide) {
					return slide.classList.contains("is-active");
				})
			);
			let autoplayId = 0;
			let touchStartX = 0;
			let touchStartY = 0;

			const setActive = function (nextIndex) {
				activeIndex = (nextIndex + slides.length) % slides.length;

				slides.forEach(function (slide, index) {
					const isActive = index === activeIndex;
					slide.classList.toggle("is-active", isActive);
					slide.hidden = !isActive;
					slide.setAttribute("aria-hidden", String(!isActive));
				});

				thumbs.forEach(function (thumb, index) {
					const isActive = index === activeIndex;
					thumb.classList.toggle("is-active", isActive);
					thumb.setAttribute("aria-pressed", String(isActive));
				});
			};

			const stopAutoplay = function () {
				window.clearTimeout(autoplayId);
			};

			const queueAutoplay = function () {
				stopAutoplay();

				if (!autoplayDelay) {
					return;
				}

				autoplayId = window.setTimeout(function () {
					setActive(activeIndex + 1);
					queueAutoplay();
				}, autoplayDelay);
			};

			const focusThumb = function (index) {
				if (thumbs[index] && typeof thumbs[index].focus === "function") {
					thumbs[index].focus();
				}
			};

			thumbs.forEach(function (thumb, index) {
				thumb.addEventListener("click", function () {
					setActive(index);
					queueAutoplay();
				});

				thumb.addEventListener("keydown", function (event) {
					let nextIndex = null;

					if (event.key === "ArrowRight" || event.key === "ArrowDown") {
						nextIndex = activeIndex + 1;
					}

					if (event.key === "ArrowLeft" || event.key === "ArrowUp") {
						nextIndex = activeIndex - 1;
					}

					if (null === nextIndex) {
						return;
					}

					event.preventDefault();
					setActive(nextIndex);
					focusThumb(activeIndex);
					queueAutoplay();
				});
			});

			root.addEventListener("mouseenter", stopAutoplay);
			root.addEventListener("mouseleave", queueAutoplay);
			root.addEventListener("focusin", stopAutoplay);
			root.addEventListener("focusout", function (event) {
				if (!root.contains(event.relatedTarget)) {
					queueAutoplay();
				}
			});

			root.addEventListener(
				"touchstart",
				function (event) {
					const touch = event.changedTouches[0];
					touchStartX = touch.clientX;
					touchStartY = touch.clientY;
				},
				{ passive: true }
			);

			root.addEventListener(
				"touchend",
				function (event) {
					const touch = event.changedTouches[0];
					const deltaX = touch.clientX - touchStartX;
					const deltaY = touch.clientY - touchStartY;

					if (Math.abs(deltaX) < 42 || Math.abs(deltaY) > 70) {
						return;
					}

					setActive(deltaX < 0 ? activeIndex + 1 : activeIndex - 1);
					queueAutoplay();
				},
				{ passive: true }
			);

			setActive(activeIndex);
			queueAutoplay();
		});
	}
})();
