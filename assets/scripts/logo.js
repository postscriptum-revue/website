const logo = document.querySelector(".site-header__logo");
const p = logo.querySelector("span:first-of-type");
const s = logo.querySelector("span:last-of-type");

// `Element.style` returns an object.
// The raw text is stored in the `cssText` property.
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

let intervalID;

logo.addEventListener("mouseenter", () => {
	intervalID = setInterval(randomizeLogo, 200);
});

logo.addEventListener("mouseleave", () => {
	clearInterval(intervalID);
	p.style = pOriginalStyle;
	s.style = sOriginalStyle;
});
