document.addEventListener("DOMContentLoaded", function () {
    const speciesList = document.getElementById("speciesList");
    const totalSpeciesEl = document.getElementById("totalSpecies");
    const endangeredSpeciesEl = document.getElementById("endangeredSpecies");
    const searchInput = document.getElementById("searchInput");
    const filterCategory = document.getElementById("filterCategory");
    const filterInput = document.getElementById("filterInput");
    let allSpecies = []; // Variable para almacenar todas las especies

    // Inicializar el mapa
    const map = L.map('map').setView([-0.3499, -78.1222], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
    const markers = new L.FeatureGroup().addTo(map);

    function fetchVisibleSpecies() {
        fetch('/observador/especies')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    allSpecies = data.especies; // Guardar todas las especies
                    displaySpecies(data.especies);
                    updateStats(data.especies);
                    updateMap(data.especies);
                } else {
                    console.error('Error al cargar las especies visibles:', data.message);
                }
            })
            .catch(error => console.error('Error al cargar las especies visibles:', error));
    }

    function displaySpecies(speciesArray) {
        speciesList.innerHTML = "";
        speciesArray.forEach(species => {
            const card = document.createElement("div");
            card.classList.add("card");

            const img = document.createElement("img");
            img.src = species.imagenes?.[0]?.img_ruta 
                ? `/storage/${species.imagenes[0].img_ruta}` 
                : "/images/no-image.jpg";
            img.alt = species.esp_nombre_comun;
            img.onerror = () => img.src = "/images/no-image.jpg";
            card.appendChild(img);

            const content = document.createElement("div");
            content.classList.add("card-content");

            const name = document.createElement("h4");
            name.textContent = species.esp_nombre_comun;
            content.appendChild(name);

            const sciName = document.createElement("p");
            sciName.textContent = species.esp_nombre_cientifico;
            sciName.style.fontStyle = 'italic';
            content.appendChild(sciName);

            const btn = document.createElement("a");
            btn.href = `/report?species=${encodeURIComponent(species.esp_nombre_comun)}`;
            btn.classList.add("btn");
            btn.innerHTML = '<i class="fas fa-info-circle"></i> Detalles';
            content.appendChild(btn);

            card.appendChild(content);
            speciesList.appendChild(card);
        });
    }

    function updateMap(species) {
        markers.clearLayers();
        
        species.forEach(species => {
            if (species.ubicaciones && species.ubicaciones.length > 0) {
                species.ubicaciones.forEach(ubicacion => {
                    const marker = L.marker([ubicacion.ubi_latitud, ubicacion.ubi_longitud])
                        .bindPopup(`
                            <strong>${species.esp_nombre_comun}</strong><br>
                            <em>${species.esp_nombre_cientifico}</em><br>
                            <small>${ubicacion.ubi_region}</small>
                        `);
                    markers.addLayer(marker);
                });
            }
        });

        if (markers.getLayers().length > 0) {
            map.fitBounds(markers.getBounds());
        }
    }

    function updateStats(data) {
        totalSpeciesEl.textContent = data.length;
        endangeredSpeciesEl.textContent = data.filter(s => s.esp_estado_valid === 2).length;
    }

    // Función de búsqueda y filtrado
    function filterSpecies() {
        const searchTerm = searchInput.value.toLowerCase();
        const category = filterCategory.value;
        const filterValue = filterInput.value.toLowerCase();

        const filteredSpecies = allSpecies.filter(species => {
            const matchesSearch = species.esp_nombre_comun.toLowerCase().includes(searchTerm) ||
                                species.esp_nombre_cientifico.toLowerCase().includes(searchTerm);

            let matchesFilter = true;
            if (filterValue) {
                switch (category) {
                    case 'family':
                        matchesFilter = species.genero?.familia?.fam_nombre.toLowerCase().includes(filterValue);
                        break;
                    case 'genus':
                        matchesFilter = species.genero?.gene_nombre.toLowerCase().includes(filterValue);
                        break;
                    case 'species':
                        matchesFilter = species.esp_nombre_cientifico.toLowerCase().includes(filterValue);
                        break;
                    case 'region':
                        matchesFilter = species.ubicaciones?.some(u => 
                            u.ubi_region.toLowerCase().includes(filterValue));
                        break;
                }
            }

            return matchesSearch && matchesFilter;
        });

        displaySpecies(filteredSpecies);
        updateMap(filteredSpecies);
        updateStats(filteredSpecies);
    }

    // Event Listeners
    searchInput.addEventListener('input', filterSpecies);
    filterCategory.addEventListener('change', filterSpecies);
    filterInput.addEventListener('input', filterSpecies);

    // Inicializar
    fetchVisibleSpecies();
});

async function verDetalles(id) {
    try {
        const response = await fetch(`/observador/especies/${id}`);
        const data = await response.json();

        if (data.success) {
            const especie = data.especie;
            const detalleHTML = `
                <h2>${especie.esp_nombre_comun}</h2>
                <img src="/storage/${especie.imagenes[0].img_ruta}" alt="${especie.esp_nombre_comun}" style="width:100%; border-radius: 8px;">
                <p><strong>Nombre Científico:</strong> <i>${especie.esp_nombre_cientifico}</i></p>
                <p><strong>Descripción:</strong> ${especie.esp_descripcion}</p>
                <p><strong>Familia:</strong> ${especie.genero.familia.fam_nombre}</p>
                <p><strong>Género:</strong> ${especie.genero.gene_nombre}</p>
                <p><strong>Reino:</strong> ${especie.genero.familia.reino.reino_nombre}</p>
                <p><strong>Ubicación:</strong> ${especie.ubicaciones[0]?.ubi_descripcion || 'No disponible'}</p>
                <p><strong>Región:</strong> ${especie.ubicaciones[0]?.ubi_region || 'No disponible'}</p>
            `;
            document.getElementById('detalleEspecie').innerHTML = detalleHTML;

            const modal = document.getElementById('detalleModal');
            modal.style.display = 'block';
        } else {
            mostrarNotificacion(data.message, 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        mostrarNotificacion('Error al cargar los detalles de la especie', 'error');
    }
}

function cerrarModal() {
    const modal = document.getElementById('detalleModal');
    modal.style.display = 'none';
}
