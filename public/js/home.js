document.addEventListener("DOMContentLoaded", function () {
    cargarEspeciesAprobadas();
});

function verDetallesEspecie(id) {
    // Redirigir a la página del reporte con el ID de la especie
    window.location.href = `/report?id=${id}`;
}

document.addEventListener("DOMContentLoaded", function () {
    cargarEspeciesValidadas();
});

async function cargarEspeciesValidadas() {
    try {
        const response = await fetch('/especies/validadas/contar'); // Ruta definida en web.php
        const data = await response.json();

        if (response.ok) {
            // Actualizar el número de especies en la sección
            const especiesCountElement = document.querySelector('.stats-container .stat:nth-child(2) h3');
            especiesCountElement.textContent = data.total.toLocaleString(); // Formatear con separadores de miles
        } else {
            console.error('Error al obtener el conteo de especies:', data.message);
        }
    } catch (error) {
        console.error('Error al obtener el conteo de especies:', error);
    }
}
document.addEventListener("DOMContentLoaded", function () {
    cargarObservacionesValidadas();
});

async function cargarObservacionesValidadas() {
    try {
        const response = await fetch('/observaciones/validadas/contar'); // Ruta definida en web.php
        const data = await response.json();

        if (response.ok) {
            // Actualizar el número de observaciones en la sección
            const observacionesCountElement = document.querySelector('.stats-container .stat:nth-child(1) h3');
            observacionesCountElement.textContent = data.total.toLocaleString(); // Formatear con separadores de miles
        } else {
            console.error('Error al obtener el conteo de observaciones:', data.message);
        }
    } catch (error) {
        console.error('Error al obtener el conteo de observaciones:', error);
    }
}


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

async function cargarEspeciesAprobadas() {
    try {
        const response = await fetch('/observador/especies'); // Asegúrate de que la ruta coincida con tu backend
        const data = await response.json();

        if (data.success) {
            const container = document.querySelector(".observations-grid");
            container.innerHTML = ""; // Limpiar contenido existente

            data.especies.forEach(especie => {
                const card = document.createElement("div");
                card.className = "observation";

                // Imagen
                const img = document.createElement("img");
                img.src = especie.imagenes.length > 0
                    ? `/storage/${especie.imagenes[0].img_ruta}`
                    : "/images/no-image.jpg"; // Imagen por defecto
                img.alt = especie.esp_nombre_comun;
                card.appendChild(img);

                // Nombre común
                const name = document.createElement("p");
                name.textContent = especie.esp_nombre_comun;
                card.appendChild(name);

                // Nombre científico
                const sciName = document.createElement("p");
                sciName.textContent = especie.esp_nombre_cientifico;
                sciName.style.fontStyle = "italic";
                sciName.style.fontSize = "14px";
                card.appendChild(sciName);

                // Botón de detalles
                const btn = document.createElement("button");
                btn.className = "btn";
                btn.textContent = "Detalles";
                btn.onclick = () => verDetallesEspecie(especie.esp_id);
                card.appendChild(btn);

                container.appendChild(card);
            });
        } else {
            console.error("Error al cargar las especies aprobadas:", data.message);
        }
    } catch (error) {
        console.error("Error al cargar las especies aprobadas:", error);
    }
}


