// TODO: Fix for Safari.
alignFootnotes();

function alignFootnotes() {
	const fn_refs = document.querySelectorAll(".footnote-ref");
	let totalOffset = 0;
	for (const ref of fn_refs) {
		const fn = getFootnote(ref);
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
