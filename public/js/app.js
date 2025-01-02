console.log("El archivo app.js se está ejecutando correctamente.");

document.addEventListener("DOMContentLoaded", function () {
    let selectedSpecies = JSON.parse(localStorage.getItem("selectedSpecies")) || [];

    const speciesData = [
        {
            name: "Colibrí Esmeralda",
            scientificName: "Chlorostilbon lucidus",
            image: "images/colibri.jpg",
            location: [0.362, -78.129],
            endangered: false,
            family: "Trochilidae",
            genus: "Chlorostilbon",
            species: "C. lucidus",
            region: "Ibarra",
            date: "2024-03-10"
        },
        {
            name: "Mosquero Cardenal",
            scientificName: "Pyrocephalus rubinus",
            image: "images/Pyrocephalus_rubinus_-Piraju,_Sao_Paulo,_Brasil_-male-8.jpg",
            location: [0.364, -78.128],
            endangered: false,
            family: "Tyrannidae",
            genus: "Pyrocephalus",
            species: "P. rubinus",
            region: "Ibarra",
            date: "2024-05-12",
            description: "El Mosquero Cardenal es un ave pequeña de plumaje rojo intenso, habita en áreas abiertas y a menudo se posa en lugares visibles. Caza insectos al vuelo."
        },
        {
            name: "Paloma",
            scientificName: "Columba livia",
            image: "images/paloma.jpg",
            location: [0.365, -78.127],
            endangered: false,
            family: "Columbidae",
            genus: "Columba",
            species: "C. livia",
            region: "Ibarra",
            date: "2024-06-20",
            description: "La Paloma es un ave de tamaño mediano, ampliamente distribuida en áreas urbanas. Se alimenta de semillas y suele ser muy sociable cerca de las personas."
        },
        {
            name: "Chingolo",
            scientificName: "Zonotrichia capensis",
            image: "images/descarga.jpg",
            location: [0.366, -78.126],
            endangered: false,
            family: "Passerellidae",
            genus: "Zonotrichia",
            species: "Z. capensis",
            region: "Ibarra",
            date: "2024-07-15",
            description: "El Chingolo (Zonotrichia capensis) es un ave pequeña y muy común en distintos hábitats abiertos, incluidos jardines urbanos. Es reconocido por su característico canto."
        }
    ];

    const totalSpeciesEl = document.getElementById("totalSpecies");
    const endangeredSpeciesEl = document.getElementById("endangeredSpecies");
    const speciesList = document.getElementById("speciesList");
    const filterCategory = document.getElementById("filterCategory");
    const filterInput = document.getElementById("filterInput");

    function updateStats(data) {
        totalSpeciesEl.textContent = data.length;
        endangeredSpeciesEl.textContent = data.filter(s => s.endangered).length;
    }

    updateStats(speciesData);

    function displaySpecies(speciesArray) {
        speciesList.innerHTML = "";
        speciesArray.forEach(species => {
            const card = document.createElement("div");
            card.classList.add("card");

            const img = document.createElement("img");
            img.src = species.image;
            img.alt = species.name;
            card.appendChild(img);

            const content = document.createElement("div");
            content.classList.add("card-content");

            const name = document.createElement("h4");
            name.textContent = species.name;
            content.appendChild(name);

            const sciName = document.createElement("p");
            sciName.textContent = species.scientificName;
            content.appendChild(sciName);

            const btn = document.createElement("a");
            btn.href = `/report?species=${encodeURIComponent(species.name)}`;
            btn.classList.add("btn");
            btn.textContent = "Detalles";
            content.appendChild(btn);
            
            // Botón Seleccionar/Deseleccionar
            const selectBtn = document.createElement("button");
            selectBtn.classList.add("btn");
            selectBtn.style.marginLeft = "10px";
            if (selectedSpecies.includes(species.name)) {
                selectBtn.textContent = "Deseleccionar";
            } else {
                selectBtn.textContent = "Seleccionar";
            }

            selectBtn.addEventListener("click", function() {
                if (selectedSpecies.includes(species.name)) {
                    selectedSpecies = selectedSpecies.filter(s => s !== species.name);
                    selectBtn.textContent = "Seleccionar";
                } else {
                    selectedSpecies.push(species.name);
                    selectBtn.textContent = "Deseleccionar";
                }
                localStorage.setItem("selectedSpecies", JSON.stringify(selectedSpecies));
                showGenerateCombinedBtn();
            });

            content.appendChild(selectBtn);

            card.appendChild(content);
            speciesList.appendChild(card);
        });
    }

    displaySpecies(speciesData);

    const map = L.map('map').setView([0.362883, -78.129356], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    let markers = [];
    function addMarkers(data) {
        markers.forEach(m => map.removeLayer(m));
        markers = [];
        data.forEach(species => {
            const marker = L.marker(species.location).addTo(map)
                .bindPopup(`<b>${species.name}</b><br>${species.scientificName}`);
            markers.push(marker);
        });
    }

    addMarkers(speciesData);

    document.getElementById("searchButton").addEventListener("click", function () {
        const searchTerm = document.getElementById("searchInput").value.toLowerCase();
        filterSpecies({ category: "all", value: searchTerm });
    });

    document.getElementById("applyFilter").addEventListener("click", function () {
        const category = filterCategory.value;
        let value = filterInput.value;
        
        if (category === "date" && value) {
            value = value.trim();
        } else {
            value = value.toLowerCase();
        }

        filterSpecies({ category, value });
    });

    function filterSpecies({ category, value }) {
        let filtered = speciesData;

        if (category && category !== "all" && value) {
            filtered = speciesData.filter(species => {
                const fieldValue = species[category].toString().toLowerCase();
                return fieldValue.includes(value);
            });
        } else if (value) {
            filtered = speciesData.filter(species =>
                species.name.toLowerCase().includes(value) ||
                species.scientificName.toLowerCase().includes(value)
            );
        }

        updateStats(filtered);
        displaySpecies(filtered);
        addMarkers(filtered);
    }

    filterCategory.addEventListener("change", function() {
        if (this.value === "date") {
            filterInput.value = "";
            filterInput.type = "date";
        } else {
            filterInput.value = "";
            filterInput.type = "text";
        }
    });

    function showGenerateCombinedBtn() {
        const combinedBtn = document.getElementById("generateCombinedReport");
        if (selectedSpecies.length > 1) {
            combinedBtn.style.display = "block";
        } else {
            combinedBtn.style.display = "none";
        }
    }

    showGenerateCombinedBtn();

    document.getElementById("generateCombinedReport").addEventListener("click", function() {
        generateCombinedPDF(speciesData, selectedSpecies);
    });

    // Función para convertir imagen local a base64
    function convertImgToBase64(url) {
        return new Promise((resolve, reject) => {
            const xhr = new XMLHttpRequest();
            xhr.onload = function() {
                const reader = new FileReader();
                reader.onloadend = function() {
                    resolve(reader.result);
                }
                reader.readAsDataURL(xhr.response);
            };
            xhr.onerror = reject;
            xhr.open('GET', url);
            xhr.responseType = 'blob';
            xhr.send();
        });
    }

    async function generateCombinedPDF(allSpecies, selectedArray) {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();
    
        const pageWidth = doc.internal.pageSize.getWidth();
        const pageHeight = doc.internal.pageSize.getHeight();
        let yPos = 20;
    
        // Función para chequear si se debe crear una nueva página
        function checkAddPage() {
            if (yPos > pageHeight - 20) {
                doc.addPage();
                yPos = 20; 
            }
        }
    
        // Encabezado verde (sólo en la primera página)
        doc.setFillColor(46,125,50);
        doc.rect(0, 0, pageWidth, 30, 'F');
        doc.setFont("helvetica", "bold");
        doc.setTextColor(255,255,255);
        doc.setFontSize(20);
        doc.text("Informe Conjunto de Especies", pageWidth/2, 20, { align: "center" });
        doc.setTextColor(0,0,0);
        yPos = 40;
    
        for (let i = 0; i < selectedArray.length; i++) {
            const selectedName = selectedArray[i];
            const species = allSpecies.find(s => s.name === selectedName);
            if (!species) continue;
    
            // Antes de empezar con la siguiente especie, verificar espacio
            checkAddPage();
    
            if (i > 0) {
                doc.setDrawColor(0);
                doc.setLineWidth(0.5);
                doc.line(20, yPos, pageWidth - 20, yPos);
                yPos += 10;
                checkAddPage();
            }
    
            // Título de la especie
            doc.setFont("helvetica", "bold");
            doc.setFontSize(16);
            doc.text(species.name, 20, yPos);
            yPos += 8;
            checkAddPage();
    
            // Convertir imagen local a base64
            try {
                const base64Img = await convertImgToBase64(species.image);
                const imgWidth = 60;
                const imgHeight = 40;
                const imgX = (pageWidth - imgWidth) / 2;
    
                // Verificar espacio antes de imagen
                if (yPos + imgHeight + 20 > pageHeight) {
                    doc.addPage();
                    yPos = 20;
                }
                doc.addImage(base64Img, 'JPEG', imgX, yPos, imgWidth, imgHeight);
                yPos += imgHeight + 10;
                checkAddPage();
            } catch (e) {
                doc.setFont("helvetica", "italic");
                doc.setFontSize(10);
                doc.text("No se pudo cargar la imagen local en el PDF.", 20, yPos+5);
                yPos += 20;
                checkAddPage();
            }
    
            doc.setFontSize(12);
            doc.setFont("helvetica", "normal");
    
            const pdfFields = [
                { label: "Nombre Común", value: species.name, italic: false },
                { label: "Nombre Científico", value: species.scientificName, italic: true },
                { label: "Familia", value: species.family || "", italic: false },
                { label: "Género", value: species.genus || "", italic: false },
                { label: "Especie", value: species.species || "", italic: false },
                { label: "Región", value: species.region || "", italic: false },
                { label: "Fecha de Observación", value: species.date || "", italic: false },
                { label: "Estado de Conservación", value: species.endangered ? "En Peligro" : "No En Peligro", italic: false },
                { label: "Descripción", value: species.description || "Sin descripción", italic: false }
            ];
    
            for (const field of pdfFields) {
                doc.setFont("helvetica", "bold");
                doc.text(`${field.label}:`, 20, yPos);
                doc.setFont("helvetica", field.italic ? "italic" : "normal");
    
                if (field.label === "Descripción") {
                    yPos += 7;
                    checkAddPage();
                    const lines = doc.splitTextToSize(field.value, pageWidth - 40);
                    for (const line of lines) {
                        doc.text(line, 20, yPos);
                        yPos += 7;
                        checkAddPage();
                    }
                    yPos += 3;
                } else {
                    // Verificar espacio antes de escribir
                    if (yPos > pageHeight - 20) {
                        doc.addPage();
                        yPos = 20;
                    }
                    doc.text(field.value, 70, yPos);
                    yPos += 10;
                }
                doc.setFont("helvetica", "normal");
                checkAddPage();
            }
    
            yPos += 10;
            checkAddPage();
        }
    
        doc.setFont("helvetica", "italic");
        doc.setFontSize(10);
        doc.text("Informe generado por Observador de Aves - Ibarra, Ecuador", 20, yPos);
    
        doc.save(`Informe_Conjunto.pdf`);
    }
    
});
