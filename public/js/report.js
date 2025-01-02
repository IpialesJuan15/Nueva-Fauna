document.addEventListener("DOMContentLoaded", function () {
    const urlParams = new URLSearchParams(window.location.search);
    const speciesName = urlParams.get('species');

    let selectedSpecies = JSON.parse(localStorage.getItem("selectedSpecies")) || [];
// Función para convertir imágenes locales a base64
function convertImgToBase64(url) {
    return new Promise((resolve, reject) => {
        const xhr = new XMLHttpRequest();
        xhr.onload = function() {
            const reader = new FileReader();
            reader.onloadend = function() {
                resolve(reader.result);
            };
            reader.readAsDataURL(xhr.response);
        };
        xhr.onerror = reject;
        xhr.open('GET', url);
        xhr.responseType = 'blob';
        xhr.send();
    });
}

    // Especies
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
            date: "2024-03-10",
            description: "Un pequeño colibrí de brillantes plumas esmeralda, común en la región de Ibarra. Se alimenta principalmente de néctar y pequeños insectos."
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

    const species = speciesData.find(s => s.name === speciesName);

    const reportContainer = document.getElementById("reportContent");
    if (species) {
        const title = document.createElement("h2");
        title.textContent = species.name;
        title.classList.add("report-title");
        reportContainer.appendChild(title);

        const img = document.createElement("img");
        img.src = species.image;
        img.alt = species.name;
        img.classList.add("report-image");
        reportContainer.appendChild(img);

        const details = document.createElement("div");
        details.classList.add("report-details");

        const fields = [
            {label: "Nombre Científico", value: `<em>${species.scientificName}</em>`},
            {label: "Familia", value: species.family},
            {label: "Género", value: species.genus},
            {label: "Especie", value: species.species},
            {label: "Región", value: species.region},
            {label: "Fecha de Observación", value: species.date},
            {label: "Estado de Conservación", value: species.endangered ? "En Peligro" : "No En Peligro"},
            {label: "Descripción", value: species.description}
        ];

        const infoList = document.createElement("ul");
        infoList.classList.add("report-info-list");

        fields.forEach(field => {
            const li = document.createElement("li");
            li.innerHTML = `<strong>${field.label}:</strong> ${field.value}`;
            infoList.appendChild(li);
        });

        details.appendChild(infoList);
        reportContainer.appendChild(details);

        // Agregar botón para seleccionar/deseleccionar esta especie
        const selectBtn = document.createElement("button");
        selectBtn.textContent = selectedSpecies.includes(species.name) ? "Deseleccionar esta especie" : "Seleccionar esta especie";
        selectBtn.classList.add("btn");
        selectBtn.style.marginTop = "20px";
        selectBtn.addEventListener("click", function() {
            if (selectedSpecies.includes(species.name)) {
                selectedSpecies = selectedSpecies.filter(s => s !== species.name);
                selectBtn.textContent = "Seleccionar esta especie";
            } else {
                selectedSpecies.push(species.name);
                selectBtn.textContent = "Deseleccionar esta especie";
            }
            localStorage.setItem("selectedSpecies", JSON.stringify(selectedSpecies));
        });
        reportContainer.appendChild(selectBtn);

    } else {
        reportContainer.innerHTML = "<p>Especie no encontrada.</p>";
    }

    document.getElementById("downloadPDF").addEventListener("click", async function () {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();
    
        // Función para convertir imágenes locales a base64
        async function convertImgToBase64(url) {
            return new Promise((resolve, reject) => {
                const xhr = new XMLHttpRequest();
                xhr.onload = function () {
                    const reader = new FileReader();
                    reader.onloadend = function () {
                        resolve(reader.result);
                    };
                    reader.readAsDataURL(xhr.response);
                };
                xhr.onerror = reject;
                xhr.open('GET', url);
                xhr.responseType = 'blob';
                xhr.send();
            });
        }
    
        if (species) {
            const pageWidth = doc.internal.pageSize.getWidth();
            let yPos = 20;
    
            // Encabezado verde con el nombre de la especie
            doc.setDrawColor(0);
            doc.setFillColor(46, 125, 50); // Verde oscuro
            doc.rect(0, 0, pageWidth, 30, 'F');
    
            doc.setFont("helvetica", "bold");
            doc.setTextColor(255, 255, 255); // Blanco
            doc.setFontSize(20);
            doc.text(species.name, pageWidth / 2, 20, { align: "center" });
    
            // Restablecer color de texto a negro
            doc.setTextColor(0, 0, 0);
            yPos = 40;
    
            // Línea divisoria
            doc.setDrawColor(0);
            doc.setLineWidth(0.5);
            doc.line(20, yPos, pageWidth - 20, yPos);
            yPos += 10;
    
            // Intentar convertir la imagen local a base64 e incrustarla
            try {
                const base64Img = await convertImgToBase64(species.image);
                const imgWidth = 60; // Ancho de la imagen
                const imgHeight = 40; // Altura de la imagen
                const imgX = (pageWidth - imgWidth) / 2; // Centrar la imagen
                doc.addImage(base64Img, 'JPEG', imgX, yPos, imgWidth, imgHeight);
                yPos += imgHeight + 10;
            } catch (error) {
                console.error("Error al convertir la imagen a base64:", error);
                doc.setFont("helvetica", "italic");
                doc.setFontSize(10);
                doc.text("No se pudo cargar la imagen.", 20, yPos);
                yPos += 10;
            }
    
            // Otra línea divisoria
            doc.line(20, yPos, pageWidth - 20, yPos);
            yPos += 10;
    
            // Información de la especie
            const pdfFields = [
                { label: "Nombre Común", value: species.name, italic: false },
                { label: "Nombre Científico", value: species.scientificName, italic: true },
                { label: "Familia", value: species.family, italic: false },
                { label: "Género", value: species.genus, italic: false },
                { label: "Especie", value: species.species, italic: false },
                { label: "Región", value: species.region, italic: false },
                { label: "Fecha de Observación", value: species.date, italic: false },
                { label: "Estado de Conservación", value: species.endangered ? "En Peligro" : "No En Peligro", italic: false },
                { label: "Descripción", value: species.description || "No disponible", italic: false }
            ];
    
            doc.setFont("helvetica", "normal");
            doc.setFontSize(12);
    
            pdfFields.forEach((field) => {
                doc.setFont("helvetica", "bold");
                doc.text(`${field.label}:`, 20, yPos);
                doc.setFont("helvetica", field.italic ? "italic" : "normal");
    
                if (field.label === "Descripción") {
                    yPos += 7;
                    const lines = doc.splitTextToSize(field.value, pageWidth - 40);
                    doc.text(lines, 20, yPos);
                    yPos += (lines.length * 7) + 3;
                } else {
                    doc.text(field.value, 70, yPos);
                    yPos += 10;
                }
            });
    
            // Pie de página
            yPos += 10;
            doc.setFont("helvetica", "italic");
            doc.setFontSize(10);
            doc.text("Informe generado por Observador de Aves - Ibarra, Ecuador", 20, yPos);
    
            // Guardar el PDF
            doc.save(`${species.name}_Informe.pdf`);
        }
    });
})    