let currentSlide = 0;
const slides = document.querySelectorAll('.carousel-slide');

function moveSlide(direction) {
  currentSlide = (currentSlide + direction + slides.length) % slides.length;
  slides.forEach((slide, index) => {
    slide.style.display = index === currentSlide ? 'block' : 'none';
  });
}

// Initialize with the first slide visible
slides.forEach((slide, index) => {
  slide.style.display = index === 0 ? 'block' : 'none';
});
