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
document.addEventListener('DOMContentLoaded', function () {
    const masSection = document.querySelector('#mas .observations-grid');

    // Función para cargar las especies aprobadas
    async function cargarEspeciesAprobadas() {
        try {
            const response = await fetch('/observador/especies');
            const data = await response.json();

            if (data.success) {
                masSection.innerHTML = ''; // Limpiar contenido existente

                data.especies.forEach(especie => {
                    // Crear un nuevo elemento para cada especie
                    const observationDiv = document.createElement('div');
                    observationDiv.classList.add('observation');

                    const img = document.createElement('img');
                    img.src = especie.imagenes?.[0]?.img_ruta
                        ? `/storage/${especie.imagenes[0].img_ruta}`
                        : '/images/no-image.jpg';
                    img.alt = especie.esp_nombre_comun;

                    const speciesName = document.createElement('p');
                    speciesName.textContent = especie.esp_nombre_comun;

                    observationDiv.appendChild(img);
                    observationDiv.appendChild(speciesName);
                    masSection.appendChild(observationDiv);
                });
            } else {
                masSection.innerHTML = '<p>No se encontraron especies aprobadas.</p>';
            }
        } catch (error) {
            console.error('Error al cargar las especies aprobadas:', error);
            masSection.innerHTML = '<p>Error al cargar las especies aprobadas.</p>';
        }
    }

    // Agregar evento al enlace "Más"
    const masLink = document.querySelector('a[href="#mas"]');
    masLink.addEventListener('click', function (event) {
        event.preventDefault(); // Evitar comportamiento predeterminado del enlace
        cargarEspeciesAprobadas();
    });
});
