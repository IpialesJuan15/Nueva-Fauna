document.addEventListener('DOMContentLoaded', function() {
    // Variables para el slider
    const sliderPrev = document.querySelector('.slider-prev');
    const sliderNext = document.querySelector('.slider-next');
    const hero = document.querySelector('.hero');
    
    // Array de imágenes de fondo para el slider
    const backgroundImages = [
        'placeholder-hero-1.jpg',
        'placeholder-hero-2.jpg',
        'placeholder-hero-3.jpg'
    ];
    
    let currentImageIndex = 0;
    
    // Función para cambiar la imagen de fondo
    function changeBackgroundImage(direction) {
        if (direction === 'next') {
            currentImageIndex = (currentImageIndex + 1) % backgroundImages.length;
        } else {
            currentImageIndex = (currentImageIndex - 1 + backgroundImages.length) % backgroundImages.length;
        }
        
        hero.style.backgroundImage = `url('${backgroundImages[currentImageIndex]}')`;
    }
    
    // Event listeners para los botones del slider
    sliderPrev.addEventListener('click', () => changeBackgroundImage('prev'));
    sliderNext.addEventListener('click', () => changeBackgroundImage('next'));
    
    // Navegación responsive
    const searchBtn = document.querySelector('.search-btn');
    
    searchBtn.addEventListener('click', function() {
        // Aquí puedes implementar la funcionalidad de búsqueda
        console.log('Búsqueda clicked');
    });

    // Smooth scrolling para enlaces internos
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            document.querySelector(this.getAttribute('href')).scrollIntoView({
                behavior: 'smooth'
            });
        });
    });

    // Animación de números en la sección de estadísticas
    function animateValue(obj, start, end, duration) {
        let startTimestamp = null;
        const step = (timestamp) => {
            if (!startTimestamp) startTimestamp = timestamp;
            const progress = Math.min((timestamp - startTimestamp) / duration, 1);
            obj.innerHTML = Math.floor(progress * (end - start) + start).toLocaleString();
            if (progress < 1) {
                window.requestAnimationFrame(step);
            }
        };
        window.requestAnimationFrame(step);
    }

    const observationsStat = document.querySelector('.stat:nth-child(1) h3');
    const speciesStat = document.querySelector('.stat:nth-child(2) h3');
    const naturalistsStat = document.querySelector('.stat:nth-child(3) h3');

    // Iniciar animación cuando la sección de estadísticas esté en el viewport
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                animateValue(observationsStat, 0, 1234567, 2000);
                animateValue(speciesStat, 0, 98765, 2000);
                animateValue(naturalistsStat, 0, 12345, 2000);
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.5 });

    observer.observe(document.querySelector('.stats-container'));
});