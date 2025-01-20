document.addEventListener('DOMContentLoaded', function () {
    // Selección de elementos principales
    const sliderPrev = document.querySelector('.slider-prev');
    const sliderNext = document.querySelector('.slider-next');
    const hero = document.querySelector('.hero');
    const sliderIndicators = document.querySelector('.slider-indicators');

    // Rutas de las imágenes de fondo
    const backgroundImages = [
        "{{ asset('images/fondo1.png') }}",
        "{{ asset('images/fondo2.jpg') }}",
        "{{ asset('images/fondo3.png') }}"
    ];

    let currentImageIndex = 0;
    let isTransitioning = false;

    // Precarga de imágenes
    backgroundImages.forEach(src => {
        const img = new Image();
        img.src = src;
        img.onload = () => console.log(`Imagen cargada: ${src}`);
        img.onerror = () => console.error(`Error al cargar la imagen: ${src}`);
    });

    // Función para cambiar a una diapositiva específica
    function goToSlide(index) {
        if (isTransitioning || index === currentImageIndex) return; // Evita cambios múltiples o al mismo índice
        isTransitioning = true;

        // Actualizar los indicadores
        document.querySelectorAll('.indicator').forEach((ind, i) => {
            ind.classList.toggle('active', i === index);
        });

        // Crear un elemento temporal para la nueva imagen
        const tempDiv = document.createElement('div');
        tempDiv.className = 'hero-background';
        tempDiv.style.backgroundImage = `url('${backgroundImages[index]}')`;
        tempDiv.style.opacity = '0';
        hero.appendChild(tempDiv);

        // Esperar para realizar la transición
        requestAnimationFrame(() => {
            tempDiv.style.opacity = '1'; // Aparecer la nueva imagen

            // Desvanecer la imagen anterior
            setTimeout(() => {
                hero.style.backgroundImage = `url('${backgroundImages[index]}')`;
                currentImageIndex = index;

                // Limpiar el contenedor
                tempDiv.remove();
                isTransitioning = false;
            }, 500); // Duración de la transición
        });
    }

    // Función para cambiar la imagen de fondo
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

    // Crear indicadores para el carrusel
    backgroundImages.forEach((_, index) => {
        const indicator = document.createElement('button');
        indicator.classList.add('indicator');
        if (index === 0) indicator.classList.add('active');
        indicator.addEventListener('click', () => goToSlide(index));
        sliderIndicators.appendChild(indicator);
    });

    // Configuración inicial
    hero.style.backgroundImage = `url('${backgroundImages[currentImageIndex]}')`;

    // Autoplay del carrusel
    let autoplayInterval = setInterval(() => {
        changeBackgroundImage('next');
    }, 5000); // Cambiar cada 5 segundos

    // Detener autoplay al hacer hover
    hero.addEventListener('mouseenter', () => {
        clearInterval(autoplayInterval);
    });

    // Reiniciar autoplay al salir del hover
    hero.addEventListener('mouseleave', () => {
        autoplayInterval = setInterval(() => {
            changeBackgroundImage('next');
        }, 5000);
    });

    // Soporte táctil (para dispositivos móviles)
    let touchStartX = 0;
    let touchEndX = 0;

    hero.addEventListener('touchstart', e => {
        touchStartX = e.changedTouches[0].screenX;
    });

    hero.addEventListener('touchend', e => {
        touchEndX = e.changedTouches[0].screenX;
        if (touchStartX - touchEndX > 50) {
            changeBackgroundImage('next'); // Swipe hacia la izquierda
        } else if (touchEndX - touchStartX > 50) {
            changeBackgroundImage('prev'); // Swipe hacia la derecha
        }
    });
});
