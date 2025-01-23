document.addEventListener('DOMContentLoaded', function () {
    const backgroundImages = [
        '/images/fondo1.png',
        '/images/fondo2.jpg',
        '/images/fondo5.jpg'
    ];

    const hero = document.querySelector('.hero-content');
    const sliderPrev = document.querySelector('.slider-prev');
    const sliderNext = document.querySelector('.slider-next');
    let currentImageIndex = 0;

    // Función para actualizar la imagen de fondo
    function updateBackground() {
        hero.style.backgroundImage = `url('${backgroundImages[currentImageIndex]}')`;
    }

    // Función para pasar a la siguiente imagen
    function nextImage() {
        currentImageIndex = (currentImageIndex + 1) % backgroundImages.length; // Avanzar al siguiente
        updateBackground();
    }

    // Función para regresar a la imagen anterior
    function prevImage() {
        currentImageIndex = (currentImageIndex - 1 + backgroundImages.length) % backgroundImages.length; // Retroceder
        updateBackground();
    }

    // Event listeners para botones
    sliderNext.addEventListener('click', nextImage);
    sliderPrev.addEventListener('click', prevImage);

    // Iniciar el carrusel con la primera imagen
    updateBackground();

    // Carrusel automático cada 5 segundos
    setInterval(nextImage, 5000); // Cambia cada 5 segundos
});
