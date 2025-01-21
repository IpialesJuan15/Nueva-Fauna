document.addEventListener('DOMContentLoaded', function() {
    const sliderPrev = document.querySelector('.slider-prev');
    const sliderNext = document.querySelector('.slider-next');
    const hero = document.querySelector('.hero');
    const sliderIndicators = document.querySelector('.slider-indicators');

    const backgroundImages = [
        'images/fondo1.png',
        'images/fondo2.jpg',
        'images/fono3.png'
    ];

    let currentImageIndex = 0;
    let isTransitioning = false;

    // Precargar imágenes
    backgroundImages.forEach(src => {
        const img = new Image();
        img.src = src;
    });

    // Función para ir a una diapositiva específica
    function goToSlide(index) {
        if (isTransitioning || index === currentImageIndex) return;
        isTransitioning = true;

        // Actualizar indicadores
        document.querySelectorAll('.indicator').forEach((ind, i) => {
            ind.classList.toggle('active', i === index);
        });

        // Crear un elemento temporal para la nueva imagen
        const tempDiv = document.createElement('div');
        tempDiv.className = 'hero-background';
        tempDiv.style.backgroundImage = `url('${backgroundImages[index]}')`;
        tempDiv.style.opacity = '0';
        hero.appendChild(tempDiv);

        // Esperar un momento para que el DOM se actualice
        requestAnimationFrame(() => {
            // Hacer aparecer la nueva imagen
            tempDiv.style.opacity = '1';

            // Desvanecer la imagen anterior
            setTimeout(() => {
                // Actualizar la imagen principal
                hero.style.backgroundImage = `url('${backgroundImages[index]}')`;
                currentImageIndex = index;
                
                // Limpiar
                tempDiv.remove();
                isTransitioning = false;
            }, 500);
        });
    }

    // Función para cambiar la imagen
    function changeBackgroundImage(direction) {
        if (isTransitioning) return;

        let newIndex;
        if (direction === 'next') {
            newIndex = (currentImageIndex + 1) % backgroundImages.length;
        } else {
            newIndex = (currentImageIndex - 1 + backgroundImages.length) % backgroundImages.length;
        }

        goToSlide(newIndex);
    }

    // Event listeners para los botones
    sliderPrev.addEventListener('click', (e) => {
        e.preventDefault();
        changeBackgroundImage('prev');
    });

    sliderNext.addEventListener('click', (e) => {
        e.preventDefault();
        changeBackgroundImage('next');
    });

    // Crear indicadores
    backgroundImages.forEach((_, index) => {
        const indicator = document.createElement('button');
        indicator.classList.add('indicator');
        if (index === 0) indicator.classList.add('active');
        indicator.addEventListener('click', () => goToSlide(index));
        sliderIndicators.appendChild(indicator);
    });

    // Configuración inicial
    hero.style.backgroundImage = `url('${backgroundImages[currentImageIndex]}')`;

    // Autoplay
    let autoplayInterval = setInterval(() => {
        changeBackgroundImage('next');
    }, 5000);

    // Detener autoplay al hover
    hero.addEventListener('mouseenter', () => {
        clearInterval(autoplayInterval);
    });

    hero.addEventListener('mouseleave', () => {
        autoplayInterval = setInterval(() => {
            changeBackgroundImage('next');
        }, 5000);
    });

    // Soporte táctil
    let touchStartX = 0;
    let touchEndX = 0;

    hero.addEventListener('touchstart', e => {
        touchStartX = e.changedTouches[0].screenX;
    });

    hero.addEventListener('touchend', e => {
        touchEndX = e.changedTouches[0].screenX;
        if (touchStartX - touchEndX > 50) {
            changeBackgroundImage('next');
        } else if (touchEndX - touchStartX > 50) {
            changeBackgroundImage('prev');
        }
    });
});