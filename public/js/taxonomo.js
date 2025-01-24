
// Guardar Registro
function saveRecord(button) {
    const row = button.closest('tr');
    const commonName = row.cells[1].innerText;
    const scientificName = row.cells[2].innerText;
    const description = row.cells[3].innerText;
    const status = row.cells[4].querySelector('select').value;

    alert(`Registro guardado:\nNombre Común: ${commonName}\nNombre Científico: ${scientificName}\nEstado: ${status}`);
}

// Previsualizar Imagen
function previewImage(input) {
    const file = input.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
            const img = input.previousElementSibling;
            img.src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
}