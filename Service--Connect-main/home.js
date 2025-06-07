document.querySelectorAll('.faq-header').forEach(header => {
    header.addEventListener('click', () => {
        const content = header.nextElementSibling;
        const icon = header.querySelector('.icon');

        if (content.classList.contains('open')) {
            content.classList.remove('open');
            icon.textContent = '+';
        } else {
            document.querySelectorAll('.faq-content').forEach(c => c.classList.remove('open'));
            document.querySelectorAll('.icon').forEach(i => i.textContent = '+');

            content.classList.add('open');
            icon.textContent = '×';
        }
    });
});
let currentIndex = 0;

function moveSlide(direction) {
  const items = document.querySelectorAll('.carousel-item');
  const totalItems = items.length;

  currentIndex = (currentIndex + direction + totalItems) % totalItems;
  
  const carousel = document.querySelector('.carousel');
  carousel.style.transform = `translateX(-${currentIndex * 100}%)`;
}
let serviceIndex = 0;

function moveServiceSlide(direction) {
    const items = document.querySelectorAll('.service-carousel .carousel-item');
    const totalItems = items.length;

    serviceIndex = (serviceIndex + direction + totalItems) % totalItems;

    const carousel = document.querySelector('.service-carousel .carousel');
    carousel.style.transform = `translateX(-${serviceIndex * 100}%)`;
}
