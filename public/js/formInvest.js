let registros = []; // Almacena todos los registros ingresados
let map, marker;

// Inicializar el mapa
document.addEventListener('DOMContentLoaded', function () {
    map = L.map('map').setView([0, 0], 2); // Vista inicial

    // Cargar los mosaicos del mapa
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // Añadir un marcador
    marker = L.marker([0, 0], { draggable: false }).addTo(map);
});

// Mostrar sección específica
function showSection(sectionId) {
    document.querySelectorAll('.content').forEach(section => {
        section.style.display = section.id === sectionId ? 'block' : 'none';
    });
}

// Actualizar mapa con Latitud y Longitud
function actualizarMapa() {
    const latitud = parseFloat(document.getElementById('latitud').value);
    const longitud = parseFloat(document.getElementById('longitud').value);

    if (!isNaN(latitud) && !isNaN(longitud)) {
        marker.setLatLng([latitud, longitud]);
        map.setView([latitud, longitud], 13);
    } else {
        alert('Por favor, ingrese valores válidos para latitud y longitud.');
    }
}

// Mostrar sección específica y ocultar las demás
function showSection(sectionId) {
    const sections = document.querySelectorAll('.content');
    sections.forEach(section => {
        if (section.id === sectionId) {
            section.style.display = 'block'; // Muestra la sección seleccionada
        } else {
            section.style.display = 'none'; // Oculta las demás secciones
        }
    });
}

// Guardar Registro
document.getElementById('form-registro').addEventListener('submit', function (e) {
    e.preventDefault();

    const registro = {
        id: Date.now(),
        nombreComun: document.getElementById('nombre_comun').value,
        reino: document.getElementById('reino').value,
        familia: document.getElementById('familia').value,
        genero: document.getElementById('genero').value,
        nombreCientifico: document.getElementById('nombre_cientifico').value,
        ubicacion: `${document.getElementById('latitud').value}, ${document.getElementById('longitud').value}`,
        fecha: new Date().toLocaleDateString(),
        imagen: URL.createObjectURL(document.getElementById('imagen').files[0])
    };

    registros.push(registro);
    actualizarTabla();
    alert('Registro guardado exitosamente.');
    this.reset();
});

// Actualizar la tabla principal
function actualizarTabla() {
    const tbody = document.querySelector('#tabla-datos tbody');
    tbody.innerHTML = ''; // Limpia la tabla antes de actualizarla

    registros.forEach(registro => {
        const row = tbody.insertRow();
        row.insertCell(0).innerText = registro.nombreComun;
        row.insertCell(1).innerText = registro.reino;
        row.insertCell(2).innerText = registro.familia;
        row.insertCell(3).innerText = registro.genero;
        row.insertCell(4).innerText = registro.nombreCientifico;
        row.insertCell(5).innerText = registro.ubicacion;
        row.insertCell(6).innerText = registro.fechaRegistro;

        // Cargar la imagen
        const imgCell = row.insertCell(7);
        const img = document.createElement('img');
        img.src = registro.imagen;
        img.style.width = '50px';
        img.style.height = '50px';
        imgCell.appendChild(img);

        // Botones de acción
        const actionsCell = row.insertCell(8);
        actionsCell.innerHTML = `
            <button onclick="enviarRevision(${registro.id})">Enviar Revisión</button>
            <button onclick="eliminarRegistro(${registro.id})">Eliminar Registro</button>
        `;
    });
}

// Filtrar Registros
function filtrarDatos() {
    const criterio = document.getElementById('buscar').value.toLowerCase();
    const tbody = document.querySelector('#tabla-filtrada tbody');
    tbody.innerHTML = '';

    const resultados = registros.filter(r =>
        r.nombreComun.toLowerCase().includes(criterio) ||
        r.ubicacion.includes(criterio) ||
        r.fecha.includes(criterio)
    );

    if (resultados.length === 0) {
        alert('No se encontraron resultados.');
    } else {
        resultados.forEach(r => {
            const row = tbody.insertRow();
            row.insertCell(0).innerText = r.nombreComun;
            row.insertCell(1).innerText = r.reino;
            row.insertCell(2).innerText = r.familia;
            row.insertCell(3).innerText = r.genero;
            row.insertCell(4).innerText = r.nombreCientifico;
            row.insertCell(5).innerText = r.ubicacion;
            row.insertCell(6).innerText = r.fecha;

            const imgCell = row.insertCell(7);
            const img = document.createElement('img');
            img.src = r.imagen;
            img.style.width = '50px';
            imgCell.appendChild(img);
        });
    }
}
// Función para buscar un registro y cargarlo en el formulario de edición
function buscarRegistro() {
    const nombreBuscar = document.getElementById('buscar_nombre_comun').value.trim();
    const registro = registros.find(r => r.nombreComun.toLowerCase() === nombreBuscar.toLowerCase());

    if (registro) {
        // Cargar los datos del registro en el formulario de edición
        document.getElementById('editar_nombre_comun').value = registro.nombreComun;
        document.getElementById('editar_reino').value = registro.reino;
        document.getElementById('editar_familia').value = registro.familia;
        document.getElementById('editar_genero').value = registro.genero;
        document.getElementById('editar_nombre_cientifico').value = registro.nombreCientifico;
        document.getElementById('observacion').value = ''; // Limpia el campo de observación
        alert('Registro encontrado. Puede editar los datos.');
    } else {
        alert('No se encontró ningún registro con ese Nombre Común.');
    }
}

// Editar y guardar los cambios en el registro
document.getElementById('form-editar').addEventListener('submit', function (e) {
    e.preventDefault();

    const nombreBuscar = document.getElementById('buscar_nombre_comun').value.trim();
    const registro = registros.find(r => r.nombreComun.toLowerCase() === nombreBuscar.toLowerCase());

    if (registro) {
        // Actualizar los valores del registro
        registro.nombreComun = document.getElementById('editar_nombre_comun').value;
        registro.reino = document.getElementById('editar_reino').value;
        registro.familia = document.getElementById('editar_familia').value;
        registro.genero = document.getElementById('editar_genero').value;
        registro.nombreCientifico = document.getElementById('editar_nombre_cientifico').value;
        registro.observacion = document.getElementById('observacion').value;

        actualizarTabla(); // Actualiza la tabla con los nuevos datos
        alert('Registro actualizado correctamente.');
        this.reset(); // Limpia el formulario de edición
        showSection('datos_ingresados'); // Redirige a la tabla de datos ingresados
    } else {
        alert('No se encontró ningún registro con ese Nombre Común.');
    }
});

// Eliminar un registro
function eliminarRegistro(id) {
    registros = registros.filter(r => r.id !== id); // Filtra y elimina el registro por ID
    actualizarTabla(); // Actualiza la tabla después de eliminar
    alert('Registro eliminado exitosamente.');
}

// Enviar a revisión
function enviarRevision(id) {
    alert(`El registro con ID ${id} ha sido enviado a revisión.`);
}
