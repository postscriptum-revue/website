/* Logo randomization on hover/focus */
(function () {
	const logo = document.querySelector(".site-header__logo");
	if (!logo) return;

	const ps = logo.querySelectorAll(".site-header__logo-ps");
	const p = ps[0];
	const s = ps[1];

	const pOriginalStyle = p.style.cssText;
	const sOriginalStyle = s.style.cssText;

	function getRandomInt(min, max) {
		return Math.floor(Math.random() * (max - min + 1) + 1);
	}

	function randomizeLogo() {
		for (const char of [p, s]) {
			const ssNum = getRandomInt(1, 20);
			const ssPaddedNum = String(ssNum).padStart(2, "0");
			const newStyle = "ss" + ssPaddedNum;
			char.style = `font-feature-settings: '${newStyle}'`;
		}
	}

	function restoreLogo() {
		clearInterval(intervalID);
		p.style = pOriginalStyle;
		s.style = sOriginalStyle;
	}

	let intervalID;

	logo.addEventListener("mouseenter", () => {
		intervalID = setInterval(randomizeLogo, 200);
	});
	logo.addEventListener("mouseleave", restoreLogo);
	logo.addEventListener("focus", () => {
		intervalID = setInterval(randomizeLogo, 200);
	});
	logo.addEventListener("blur", restoreLogo);
})();

/* Mobile sidebar toggle */
(function () {
	const siteAside = document.querySelector(".site-aside");
	if (!siteAside) return;

	const button = siteAside.querySelector(".site-aside__mobile-button");
	if (!button) return;

	function openSidebar() {
		document.body.classList.add("site-aside-opened");
		button.setAttribute("aria-expanded", "true");
		button.setAttribute("aria-label", "Fermer le menu latéral");
	}

	function closeSidebar() {
		document.body.classList.remove("site-aside-opened");
		button.setAttribute("aria-expanded", "false");
		button.setAttribute("aria-label", "Ouvrir le menu latéral");
	}

	button.addEventListener("click", () => {
		if (document.body.classList.contains("site-aside-opened")) {
			closeSidebar();
		} else {
			openSidebar();
		}
	});

	document.addEventListener("keydown", (e) => {
		if (e.key === "Escape" && document.body.classList.contains("site-aside-opened")) {
			closeSidebar();
			button.focus();
		}
	});
})();

/* Footnote alignment & navigation */
(function () {
	const fn_refs = document.querySelectorAll(".footnote-ref");
	if (fn_refs.length === 0) return;

	alignFootnotes();
	navigateBack();

	function alignFootnotes() {
		let totalOffset = 0;
		for (const ref of fn_refs) {
			const fn = getFootnote(ref);
			if (!fn) continue;
			const offset = ref.offsetTop - totalOffset;
			const margin = offset < 0 ? 0 : offset;
			const fn_height = fn.getBoundingClientRect().height;
			fn.style.marginTop = margin + "px";
			totalOffset += margin + fn_height;
		}
	}

	function getFootnote(ref) {
		const id = ref.getAttribute("href");
		return document.querySelector(id);
	}

	function navigateBack() {
		const footnoteBacklinks = document.querySelectorAll('.footnote-list__backlink');

		for (const link of footnoteBacklinks) {
			link.addEventListener('click', function (e) {
				e.preventDefault();

				const targetElement = getFootnote(link);

				if (targetElement) {
					targetElement.scrollIntoView({ behavior: 'smooth' });
					targetElement.focus();

					targetElement.classList.add('footnote-ref-highlight');
					setTimeout(() => {
						targetElement.classList.remove('footnote-ref-highlight');
					}, 1500);
				}
			});
		}
	}
})();

/* Image lightbox */
(function () {
	const lightbox = document.querySelector(".lightbox");
	if (!lightbox) return;

	const lightboxImage = lightbox.querySelector(".lightbox__image");
	const images = document.querySelectorAll("main img");
	let previousFocus = null;

	for (const image of images) {
		image.addEventListener("click", (e) => {
			openLightbox(e.target.src, e.target.alt);
		});
	}

	lightbox.addEventListener("click", closeLightbox);

	document.addEventListener("keydown", (e) => {
		if (e.key === "Escape" && lightbox.style.display === "flex") {
			closeLightbox();
		}
	});

	function openLightbox(src, alt) {
		previousFocus = document.activeElement;
		lightbox.style.display = "flex";
		lightboxImage.src = src;
		lightboxImage.alt = alt || "";
		lightbox.focus();
	}

	function closeLightbox() {
		lightbox.style.display = "none";
		lightboxImage.src = "";
		if (previousFocus) {
			previousFocus.focus();
		}
	}
})();
