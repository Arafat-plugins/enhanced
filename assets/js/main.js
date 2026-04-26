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
		initShopFeatures();
		initQuantityButtons();
		initProductTabs();
		initProductGallery();
		initVariationPills();
		initSimpleOptionPickers();
		initWishlistToggle();
		initHomeRails();
		initHeroSlider();
		initArrivalShowcase();
	});

	function requestFormSubmit(form) {
		if (!form) {
			return;
		}

		if (typeof form.requestSubmit === "function") {
			form.requestSubmit();
			return;
		}

		const event = new Event("submit", { bubbles: true, cancelable: true });
		if (form.dispatchEvent(event)) {
			form.submit();
		}
	}

	function initShopFeatures() {
		initShopSidebar();
		initSidebarAccordions();
		initFilterGroups();
		initPriceSlider();
		initFilterSearch();
		initShopArchiveAjax();
	}

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
		if (!sidebar) return;

		const getState = function () {
			return {
				sidebar: document.getElementById("shop-sidebar"),
				toggles: document.querySelectorAll("[data-sidebar-toggle]"),
				closers: document.querySelectorAll("[data-sidebar-close]"),
				overlay: document.querySelector("[data-sidebar-overlay]"),
			};
		};

		let lastTrigger = null;

		if (!sidebar.enhancedTrapFocus) {
			sidebar.enhancedTrapFocus = createFocusTrap(sidebar);
		}

		const open = function (trigger) {
			const state = getState();
			if (!state.sidebar) {
				return;
			}

			lastTrigger = trigger || document.activeElement;
			state.sidebar.classList.add("is-open");
			if (state.overlay) state.overlay.classList.add("is-visible");
			state.toggles.forEach(function (toggle) {
				toggle.setAttribute("aria-expanded", "true");
			});
			lockBody("filters", true);
			document.addEventListener("keydown", state.sidebar.enhancedTrapFocus);

			const closeButton = state.sidebar.querySelector("[data-sidebar-close]");
			if (closeButton && window.innerWidth <= 960) {
				window.setTimeout(function () {
					closeButton.focus();
				}, 50);
			}
		};

		const close = function () {
			const state = getState();
			if (!state.sidebar) {
				return;
			}

			state.sidebar.classList.remove("is-open");
			if (state.overlay) state.overlay.classList.remove("is-visible");
			state.toggles.forEach(function (toggle) {
				toggle.setAttribute("aria-expanded", "false");
			});
			lockBody("filters", false);
			document.removeEventListener("keydown", state.sidebar.enhancedTrapFocus);

			if (lastTrigger && typeof lastTrigger.focus === "function") {
				lastTrigger.focus();
			}
		};

		getState().toggles.forEach(function (toggle) {
			if (toggle.dataset.sidebarBound === "true") {
				return;
			}

			toggle.dataset.sidebarBound = "true";
			toggle.addEventListener("click", function () {
				open(toggle);
			});
		});

		getState().closers.forEach(function (closer) {
			if (closer.dataset.sidebarBound === "true") {
				return;
			}

			closer.dataset.sidebarBound = "true";
			closer.addEventListener("click", close);
		});

		const overlay = document.querySelector("[data-sidebar-overlay]");
		if (overlay && overlay.dataset.sidebarBound !== "true") {
			overlay.dataset.sidebarBound = "true";
			overlay.addEventListener("click", close);
		}

		if (document.documentElement.dataset.shopSidebarGlobals === "true") {
			return;
		}

		document.documentElement.dataset.shopSidebarGlobals = "true";

		document.addEventListener("keydown", function (event) {
			const currentSidebar = document.getElementById("shop-sidebar");
			if (event.key === "Escape" && currentSidebar && currentSidebar.classList.contains("is-open")) {
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
			if (widget.dataset.accordionReady === "true") return;

			const title = widget.querySelector(".widget__title");
			if (!title) return;

			widget.dataset.accordionReady = "true";
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
			if (group.dataset.filterGroupReady === "true") return;

			const head = group.querySelector(".en-group__head");
			const bodyElement = group.querySelector(".en-group__body");
			if (!head || !bodyElement) return;

			group.dataset.filterGroupReady = "true";
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
			if (form.dataset.priceReady === "true") return;

			const track = form.querySelector("[data-price-track]");
			const fill = form.querySelector("[data-price-fill]");
			const handles = form.querySelectorAll("[data-price-handle]");
			const inputMin = form.querySelector('[data-price-input="min"]');
			const inputMax = form.querySelector('[data-price-input="max"]');
			const displayMin = form.querySelector('[data-price-display="min"]');
			const displayMax = form.querySelector('[data-price-display="max"]');
			if (!track || !fill || handles.length !== 2) return;

			form.dataset.priceReady = "true";
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
		if (!form || form.dataset.filterSearchReady === "true") return;

		const input = form.querySelector(".en-search__input");
		const clearButton = form.querySelector("[data-search-clear]");
		if (!input) return;

		form.dataset.filterSearchReady = "true";
		let timer;

		input.addEventListener("input", function () {
			window.clearTimeout(timer);
			timer = window.setTimeout(function () {
				requestFormSubmit(form);
			}, 520);
		});

		input.addEventListener("keydown", function (event) {
			if (event.key === "Enter") {
				event.preventDefault();
				window.clearTimeout(timer);
				requestFormSubmit(form);
			}
		});

		if (clearButton) {
			clearButton.addEventListener("click", function () {
				input.value = "";
				requestFormSubmit(form);
			});
		}
	}

	function initShopArchiveAjax() {
		if (document.documentElement.dataset.shopArchiveAjaxReady === "true") {
			return;
		}

		const getCurrentShell = function () {
			const currentShell = document.querySelector("[data-shop-shell], .shop-shell");
			if (currentShell && !currentShell.hasAttribute("data-shop-shell")) {
				currentShell.setAttribute("data-shop-shell", "");
			}

			return currentShell;
		};

		if (!getCurrentShell()) {
			return;
		}

		document.documentElement.dataset.shopArchiveAjaxReady = "true";

		let controller = null;

		const setLoading = function (loading) {
			const currentShell = getCurrentShell();
			if (!currentShell) {
				return;
			}

			currentShell.classList.toggle("is-loading", loading);
			currentShell.setAttribute("aria-busy", loading ? "true" : "false");
		};

		const buildAjaxUrl = function (url) {
			const ajaxUrl = new URL(url, window.location.href);
			ajaxUrl.searchParams.set("enhanced_shop_ajax", "1");
			return ajaxUrl;
		};

		const replacePageCount = function (pageCountHtml) {
			const currentCount = document.querySelector(".page-banner__count");

			if (!pageCountHtml) {
				if (currentCount) {
					currentCount.remove();
				}
				return;
			}

			const template = document.createElement("template");
			template.innerHTML = pageCountHtml.trim();
			const nextCount = template.content.firstElementChild;
			if (!nextCount) {
				return;
			}

			if (currentCount) {
				currentCount.replaceWith(nextCount);
				return;
			}

			const bannerInner = document.querySelector(".page-banner__inner");
			if (bannerInner) {
				bannerInner.appendChild(nextCount);
			}
		};

		const getFormUrl = function (form) {
			const url = new URL(form.getAttribute("action") || window.location.href, window.location.href);
			const params = new URLSearchParams();
			const formData = new FormData(form);

			formData.forEach(function (value, key) {
				if (value === "") {
					return;
				}

				params.append(key, value);
			});

			url.search = params.toString();
			url.searchParams.delete("enhanced_shop_ajax");

			return url;
		};

		const refreshShop = function (url, options) {
			const requestOptions = Object.assign(
				{
					pushState: true,
					focusSearch: false,
				},
				options || {}
			);
			const nextUrl = new URL(url, window.location.href);
			nextUrl.searchParams.delete("enhanced_shop_ajax");

			if (controller) {
				controller.abort();
			}

			controller = new AbortController();
			setLoading(true);

			fetch(buildAjaxUrl(nextUrl), {
				credentials: "same-origin",
				headers: {
					"X-Requested-With": "XMLHttpRequest",
				},
				signal: controller.signal,
			})
				.then(function (response) {
					if (!response.ok) {
						throw new Error("Shop refresh failed");
					}

					return response.json();
				})
				.then(function (payload) {
					if (!payload || !payload.success || !payload.data || !payload.data.shopShell) {
						throw new Error("Invalid shop payload");
					}

					const template = document.createElement("template");
					template.innerHTML = payload.data.shopShell.trim();
					const nextShell = template.content.firstElementChild;
					const currentShell = getCurrentShell();
					const currentSidebar = document.getElementById("shop-sidebar");

					if (!nextShell || !currentShell) {
						throw new Error("Missing shop shell");
					}

					if (currentSidebar && currentSidebar.enhancedTrapFocus) {
						document.removeEventListener("keydown", currentSidebar.enhancedTrapFocus);
					}

					lockBody("filters", false);
					currentShell.replaceWith(nextShell);
					replacePageCount(payload.data.pageCountHtml || "");

					if (requestOptions.pushState) {
						window.history.pushState({ enhancedShop: true }, "", nextUrl.toString());
					}

					initShopFeatures();

					if (requestOptions.focusSearch) {
						const searchInput = document.querySelector(".en-search__input");
						if (searchInput) {
							searchInput.focus();
							searchInput.setSelectionRange(searchInput.value.length, searchInput.value.length);
						}
					}
				})
				.catch(function (error) {
					if (error && error.name === "AbortError") {
						return;
					}

					window.location.assign(nextUrl.toString());
				})
				.finally(function () {
					setLoading(false);
				});
		};

		document.addEventListener("submit", function (event) {
			const form = event.target;
			if (!(form instanceof HTMLFormElement)) {
				return;
			}

			if (!form.matches("[data-filter-search], [data-price-form], .woocommerce-ordering")) {
				return;
			}

			event.preventDefault();
			refreshShop(getFormUrl(form), {
				focusSearch: form.matches("[data-filter-search]"),
			});
		});

		document.addEventListener("change", function (event) {
			const field = event.target;
			if (!(field instanceof HTMLElement) || !field.matches(".woocommerce-ordering select")) {
				return;
			}

			const form = field.closest("form");
			if (form) {
				refreshShop(getFormUrl(form));
			}
		});

		document.addEventListener("click", function (event) {
			const link = event.target.closest("a");
			if (!link) {
				return;
			}

			if (
				event.button !== 0
				|| !link.href
				|| !link.closest(".en-filters, .woocommerce-pagination, .shop-empty")
				|| link.target === "_blank"
				|| event.metaKey
				|| event.ctrlKey
				|| event.shiftKey
				|| event.altKey
			) {
				return;
			}

			const href = link.getAttribute("href");
			if (!href || href.charAt(0) === "#") {
				return;
			}

			event.preventDefault();
			refreshShop(href);
		});

		window.addEventListener("popstate", function () {
			if (!getCurrentShell()) {
				return;
			}

			refreshShop(window.location.href, {
				pushState: false,
			});
		});
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
			const thumbs = Array.from(gallery.querySelectorAll("[data-gallery-thumb]"));
			if (!mainImage) return;

			const setThumbState = function (activeThumb) {
				thumbs.forEach(function (button) {
					const isActive = button === activeThumb;
					button.classList.toggle("is-active", isActive);
					button.setAttribute("aria-pressed", String(isActive));
				});
			};

			const findThumbBySource = function (source) {
				if (!source) {
					return null;
				}

				return (
					thumbs.find(function (thumb) {
						return thumb.dataset.fullSrc === source;
					}) || null
				);
			};

			const setMainImage = function (source, alt, matchedThumb) {
				if (!source) {
					return;
				}

				mainImage.src = source;
				mainImage.alt = alt || mainImage.alt;
				setThumbState(matchedThumb || findThumbBySource(source));
			};

			const rememberBaseImage = function (source, alt) {
				if (!source) {
					return;
				}

				gallery.dataset.baseImageSrc = source;
				gallery.dataset.baseImageAlt = alt || "";
			};

			const activeThumb = gallery.querySelector("[data-gallery-thumb].is-active");
			rememberBaseImage(
				(activeThumb && activeThumb.dataset.fullSrc) || mainImage.currentSrc || mainImage.src,
				(activeThumb && activeThumb.dataset.alt) || mainImage.alt
			);

			gallery.enhancedSetMainImage = function (source, alt) {
				setMainImage(source, alt, findThumbBySource(source));
			};

			gallery.enhancedResetMainImage = function () {
				const baseSource = gallery.dataset.baseImageSrc || mainImage.currentSrc || mainImage.src;
				const baseAlt = gallery.dataset.baseImageAlt || mainImage.alt;
				setMainImage(baseSource, baseAlt, findThumbBySource(baseSource));
			};

			thumbs.forEach(function (thumb) {
				thumb.addEventListener("click", function () {
					const fullSrc = thumb.dataset.fullSrc;
					const alt = thumb.dataset.alt || mainImage.alt;
					if (!fullSrc) return;

					rememberBaseImage(fullSrc, alt);
					setMainImage(fullSrc, alt, thumb);
				});
			});
		});
	}

	function initVariationPills() {
		const commonColors = {
			black: "#111111",
			white: "#ffffff",
			red: "#e53e3e",
			blue: "#2563eb",
			navy: "#1e3a8a",
			green: "#16a34a",
			olive: "#6b8e23",
			yellow: "#eab308",
			pink: "#ec4899",
			maroon: "#7f1d1d",
			brown: "#8b5a2b",
			beige: "#d6c4a1",
			grey: "#6b7280",
			gray: "#6b7280",
			cream: "#f5e6d3",
			orange: "#f97316",
			purple: "#7c3aed",
		};
		const colorMap = Object.assign({}, commonColors, settings.variationColors || {});
		const normalizeValue = function (value) {
			return String(value || "")
				.toLowerCase()
				.trim()
				.replace(/[^a-z0-9]+/g, "-")
				.replace(/^-+|-+$/g, "");
		};
		const isLightColor = function (value) {
			const normalized = String(value || "").trim().toLowerCase();
			const hexMatch = normalized.match(/^#([0-9a-f]{3}|[0-9a-f]{6})$/i);
			if (!hexMatch) {
				return false;
			}

			let hex = hexMatch[1];
			if (hex.length === 3) {
				hex = hex
					.split("")
					.map(function (part) {
						return part + part;
					})
					.join("");
			}

			const red = parseInt(hex.slice(0, 2), 16);
			const green = parseInt(hex.slice(2, 4), 16);
			const blue = parseInt(hex.slice(4, 6), 16);
			const brightness = (red * 299 + green * 587 + blue * 114) / 1000;

			return brightness >= 214;
		};
		const getAttributeType = function (select) {
			const row = select.closest("tr");
			const labelElement = row ? row.querySelector("label") : null;
			const label = labelElement ? labelElement.textContent.toLowerCase().trim() : "";
			const attributeName = (
				select.getAttribute("data-attribute_name") ||
				select.name ||
				select.id ||
				""
			)
				.toLowerCase()
				.trim();

			if (attributeName.includes("color") || label.includes("color")) {
				return "color";
			}

			if (attributeName.includes("size") || label.includes("size")) {
				return "size";
			}

			return "text";
		};
		const getColorValue = function (option) {
			const candidates = [
				option.value,
				option.textContent,
				option.label,
				option.dataset ? option.dataset.slug : "",
			]
				.map(normalizeValue)
				.filter(Boolean);

			for (const candidate of candidates) {
				if (colorMap[candidate]) {
					return colorMap[candidate];
				}
			}

			return "#cccccc";
		};

		document.querySelectorAll(".variations_form").forEach(function (form) {
			const selects = Array.from(form.querySelectorAll(".variations select"));
			if (!selects.length) return;

			selects.forEach(function (select) {
				if (select.dataset.pillsReady === "true") {
					return;
				}

				const wrapper = document.createElement("div");
				const attributeType = getAttributeType(select);
				wrapper.className = "variation-pills variation-pills--" + attributeType;
				select.insertAdjacentElement("afterend", wrapper);
				select.classList.add("variation-select--enhanced");
				select.dataset.pillsReady = "true";

				const sync = function () {
					const options = Array.from(select.options).filter(function (option) {
						return option.value;
					});

					if (options.length < 2) {
						wrapper.hidden = true;
						select.classList.remove("variation-select--enhanced");
						return;
					}

					select.classList.add("variation-select--enhanced");
					wrapper.hidden = false;
					wrapper.innerHTML = "";

					options.forEach(function (option) {
						const button = document.createElement("button");
						button.type = "button";
						button.className = "variation-pills__button";
						button.disabled = option.disabled;

						const isActive = select.value === option.value;
						button.classList.toggle("is-active", isActive);
						button.setAttribute("aria-pressed", String(isActive));
						button.dataset.value = option.value;

						if (attributeType === "color") {
							const colorValue = getColorValue(option);
							button.classList.add("variation-pills__button--color");
							button.classList.toggle("variation-pills__button--light", isLightColor(colorValue));
							button.setAttribute("aria-label", option.text.trim());

							const swatch = document.createElement("span");
							swatch.className = "variation-pills__swatch";
							swatch.style.setProperty("--variation-swatch-color", colorValue);
							swatch.setAttribute("aria-hidden", "true");

							const srText = document.createElement("span");
							srText.className = "screen-reader-text";
							srText.textContent = option.text.trim();

							button.appendChild(swatch);
							button.appendChild(srText);
						} else {
							if (attributeType === "size") {
								button.classList.add("variation-pills__button--size");
							}

							button.textContent = option.text.trim();
						}

						button.addEventListener("click", function () {
							if (option.disabled) {
								return;
							}

							select.value = option.value;
							select.dispatchEvent(new Event("change", { bubbles: true }));
						});

						wrapper.appendChild(button);
					});
				};

				select.enhancedSyncVariationPills = sync;
				select.addEventListener("change", sync);
				window.setTimeout(sync, 20);
			});

			form.addEventListener("change", function () {
				window.setTimeout(function () {
					selects.forEach(function (select) {
						if (typeof select.enhancedSyncVariationPills === "function") {
							select.enhancedSyncVariationPills();
						}
					});
				}, 0);
			});

			if (window.jQuery) {
				const gallery = form.closest("[data-product-gallery]");
				if (!gallery) {
					return;
				}

				window.jQuery(form).on("found_variation", function (event, variation) {
					const image = variation && variation.image ? variation.image : null;
					const source = image && (image.full_src || image.src);
					if (!source || typeof gallery.enhancedSetMainImage !== "function") {
						return;
					}

					gallery.enhancedSetMainImage(source, image.alt || image.title || "");
				});

				window.jQuery(form).on("hide_variation reset_data", function () {
					if (typeof gallery.enhancedResetMainImage === "function") {
						gallery.enhancedResetMainImage();
					}
				});
			}
		});
	}

	function initSimpleOptionPickers() {
		document.querySelectorAll("[data-simple-option-pickers]").forEach(function (pickerRoot) {
			pickerRoot.querySelectorAll("[data-simple-option-picker]").forEach(function (picker) {
				const input = picker.querySelector("[data-simple-attribute-input]");
				const buttons = Array.from(picker.querySelectorAll("[data-simple-attribute-option]"));
				if (!input || !buttons.length) {
					return;
				}

				const sync = function () {
					buttons.forEach(function (button) {
						const isActive = button.dataset.value === input.value;
						button.classList.toggle("is-active", isActive);
						button.setAttribute("aria-pressed", String(isActive));
					});
				};

				buttons.forEach(function (button) {
					button.addEventListener("click", function () {
						input.value = button.dataset.value || "";
						sync();
					});
				});

				sync();
			});
		});
	}

	function initWishlistToggle() {
		const storageKey = "enhancedWishlist";
		let savedIds = [];

		try {
			savedIds = JSON.parse(window.localStorage.getItem(storageKey) || "[]");
			if (!Array.isArray(savedIds)) {
				savedIds = [];
			}
		} catch (error) {
			savedIds = [];
		}

		const persist = function () {
			try {
				window.localStorage.setItem(storageKey, JSON.stringify(savedIds));
			} catch (error) {
				return;
			}
		};

		document.querySelectorAll("[data-wishlist-toggle]").forEach(function (button) {
			const productId = Number(button.dataset.productId || 0);
			const label = button.querySelector("[data-wishlist-label]");
			const icon = button.querySelector(".product-details__wishlist-icon");
			if (!productId || !label) return;

			const sync = function () {
				const active = savedIds.includes(productId);
				button.classList.toggle("is-active", active);
				button.setAttribute("aria-pressed", String(active));
				label.textContent = active ? "Saved to wishlist" : "Add to wishlist";

				if (icon) {
					icon.textContent = active ? "♥" : "♡";
				}
			};

			button.addEventListener("click", function () {
				if (savedIds.includes(productId)) {
					savedIds = savedIds.filter(function (id) {
						return id !== productId;
					});
				} else {
					savedIds.push(productId);
				}

				persist();
				sync();
			});

			sync();
		});
	}

	function initHomeRails() {
		document.querySelectorAll("[data-rail-slider]").forEach(function (slider) {
			const track = slider.querySelector("[data-rail-track]");
			const prevButton = slider.querySelector("[data-rail-prev]");
			const nextButton = slider.querySelector("[data-rail-next]");
			const autoplayDelay = Number(slider.dataset.railAutoplay || 0);
			if (!track || !prevButton || !nextButton) return;

			let autoplayId = 0;

			const prepareAutoplayTrack = function () {
				if (!autoplayDelay) {
					return;
				}

				const originalItems = Array.from(track.children).filter(function (item) {
					return item.dataset.railClone !== "true";
				});

				if (originalItems.length < 2) {
					return;
				}

				let cloneIndex = 0;
				const maxClones = Math.max(originalItems.length * 3, 10);

				while (track.scrollWidth <= track.clientWidth + 8 && cloneIndex < maxClones) {
					const source = originalItems[cloneIndex % originalItems.length];
					const clone = source.cloneNode(true);
					clone.dataset.railClone = "true";
					track.appendChild(clone);
					cloneIndex += 1;
				}
			};

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

			const stopAutoplay = function () {
				window.clearTimeout(autoplayId);
			};

			const queueAutoplay = function () {
				stopAutoplay();

				if (!autoplayDelay || track.scrollWidth <= track.clientWidth + 8) {
					return;
				}

				autoplayId = window.setTimeout(function () {
					const step = getStep() * 1.08;
					const maxScroll = Math.max(0, track.scrollWidth - track.clientWidth - 4);
					const nextLeft = track.scrollLeft + step >= maxScroll ? 0 : track.scrollLeft + step;
					track.scrollTo({ left: nextLeft, behavior: "smooth" });
					queueAutoplay();
				}, autoplayDelay);
			};

			prevButton.addEventListener("click", function () {
				track.scrollBy({ left: -getStep() * 1.4, behavior: "smooth" });
				queueAutoplay();
			});

			nextButton.addEventListener("click", function () {
				track.scrollBy({ left: getStep() * 1.4, behavior: "smooth" });
				queueAutoplay();
			});

			track.addEventListener("scroll", updateButtons, { passive: true });
			window.addEventListener("resize", function () {
				prepareAutoplayTrack();
				updateButtons();
			});
			slider.addEventListener("mouseenter", stopAutoplay);
			slider.addEventListener("mouseleave", queueAutoplay);
			slider.addEventListener("focusin", stopAutoplay);
			slider.addEventListener("focusout", function (event) {
				if (!slider.contains(event.relatedTarget)) {
					queueAutoplay();
				}
			});
			prepareAutoplayTrack();
			updateButtons();
			queueAutoplay();
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
