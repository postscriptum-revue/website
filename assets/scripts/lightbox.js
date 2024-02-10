const images = document.querySelectorAll("img");
const lightbox = document.querySelector(".lightbox");
const lightboxImage = lightbox.querySelector(".lightbox__image");

for (const image of images) {
    image.addEventListener("click", (e) => {
        openLightbox(e.target.src);
    });
}

lightbox.addEventListener("click", closeLightbox);

function openLightbox(src) {
    lightbox.style.display = "flex";
    lightboxImage.src = src;
}

function closeLightbox() {
	lightbox.style.display = "none";
}

document.addEventListener("click", (e) => {
	console.log(e.target)
})
