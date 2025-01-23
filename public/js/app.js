document.addEventListener("DOMContentLoaded", function () {
    const speciesList = document.getElementById("speciesList");
    const totalSpeciesEl = document.getElementById("totalSpecies");
    const endangeredSpeciesEl = document.getElementById("endangeredSpecies");

    function fetchVisibleSpecies() {
        fetch('/observador/especies')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    displaySpecies(data.especies);
                    updateStats(data.especies);
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
            img.src = species.imagenes?.[0]?.img_ruta ? `/storage/${species.imagenes[0].img_ruta}` : "/images/no-image.jpg";
            img.alt = species.esp_nombre_comun;
            card.appendChild(img);

            const content = document.createElement("div");
            content.classList.add("card-content");

            const name = document.createElement("h4");
            name.textContent = species.esp_nombre_comun;
            content.appendChild(name);

            const sciName = document.createElement("p");
            sciName.textContent = species.esp_nombre_cientifico;
            content.appendChild(sciName);

            const btn = document.createElement("a");
            btn.href = `/report?species=${encodeURIComponent(species.esp_nombre_comun)}`;
            btn.classList.add("btn");
            btn.textContent = "Detalles";
            content.appendChild(btn);

            card.appendChild(content);
            speciesList.appendChild(card);
        });
    }

    function updateStats(data) {
        totalSpeciesEl.textContent = data.length;
        endangeredSpeciesEl.textContent = data.filter(s => s.esp_en_peligro).length; // Si hay un campo para "en peligro"
    }

    // Llamar a la función para cargar especies visibles
    fetchVisibleSpecies();
});
