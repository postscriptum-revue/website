// TODO: Fix for Safari.
alignFootnotes();
navigateBack();

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

function navigateBack() {
	const footnoteBacklinks = document.querySelectorAll('.footnote-list__backlink');
	
	for(const link of footnoteBacklinks ){
		link.addEventListener('click', function(e) {
			e.preventDefault();

			const targetElement = getFootnote(link);
			
			if (targetElement) {
				targetElement.scrollIntoView({ behavior: 'smooth'});
				
				targetElement.classList.add('footnote-ref-highlight');
				setTimeout(() => {
					targetElement.classList.remove('footnote-ref-highlight');
				}, 1500);
			}
		});
	};
};
