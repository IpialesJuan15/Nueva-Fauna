const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

let registros = [];
let map, marker;
let mapEditar, markerEditar;

document.addEventListener('DOMContentLoaded', () => {
    // Asegurarse de que el contenedor del mapa tenga tamaño definido
    const mapContainer = document.getElementById('map-editar');
    if (!mapContainer) {
        console.error('El contenedor "map-editar" no existe.');
        return;
    }

    // Inicializar mapa de edición
    mapEditar = L.map('map-editar').setView([0.3517, -78.1223], 13); // Centrado en Ibarra, Ecuador
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(mapEditar);

    // Agregar marcador inicial
    markerEditar = L.marker([0.3517, -78.1223], { draggable: false }).addTo(mapEditar);
});

// Función para actualizar el mapa en la sección de edición
function actualizarMapaEdicion() {
    const latitud = parseFloat(document.getElementById('editar_latitud').value);
    const longitud = parseFloat(document.getElementById('editar_longitud').value);

    if (!isNaN(latitud) && !isNaN(longitud)) {
        markerEditar.setLatLng([latitud, longitud]);
        mapEditar.setView([latitud, longitud], 13);
    } else {
        alert('Por favor, ingrese valores válidos para latitud y longitud.');
    }
}

// Si el mapa está en una sección inicialmente oculta
function mostrarMapaEditar() {
    const mapContainer = document.getElementById('map-editar');
    mapContainer.style.display = 'block'; // Mostrar el contenedor
    mapEditar.invalidateSize(); // Asegurarse de que el mapa se redibuje correctamente
}


// Mapa de Observaciones
let mapObservaciones;
let markers = [];
document.addEventListener('DOMContentLoaded', () => {
    // Cargar datos al inicializar
    cargarDatos();
    cargarObservaciones();

    // Inicializar el mapa de registro
    map = L.map('map').setView([0.3517, -78.1223], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);
    marker = L.marker([0.3517, -78.1223], { draggable: false }).addTo(map);

    // Inicializar el mapa de observaciones
    mapObservaciones = L.map('map-observaciones').setView([0.3517, -78.1223], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(mapObservaciones);

    // Asignar el evento al botón de búsqueda
    const buscarBtn = document.getElementById('buscar-btn');
    if (buscarBtn) {
        buscarBtn.addEventListener('click', buscarRegistro);
    }
});
// Función para mostrar u ocultar la sección de observaciones
function mostrarObservaciones() {
    // Ocultar todas las secciones
    var secciones = document.querySelectorAll('section');
    secciones.forEach(function (seccion) {
        seccion.style.display = 'none';  // Ocultar todas las secciones
    });

    // Mostrar solo la sección de Observaciones
    var observaciones = document.getElementById('observaciones');
    observaciones.style.display = 'block';  // Mostrar Observaciones
}


window.eliminarRegistro = function (id) {
    if (confirm('¿Estás seguro de que deseas eliminar este registro?')) {
        fetch(`/especies/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(data => {
                    throw new Error(data.message || 'Error al eliminar el registro.');
                });
            }
            return response.json();
        })
        .then(data => {
            alert(data.message || 'Registro eliminado con éxito.');
            cargarDatos(); // Recargar la tabla después de eliminar
        })
        .catch(error => {
            console.error('Error al eliminar el registro:', error);
            alert(`Error: ${error.message}`);
        });
    }
};

// Función para cargar datos en la tabla
function cargarDatos() {
    fetch('/especies', {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
        },
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Error al cargar los datos.');
        }
        return response.json();
    })
    .then(data => {
        const tbody = document.querySelector('#tabla-datos tbody');
        tbody.innerHTML = ''; // Limpiar la tabla antes de agregar datos

        data.forEach(especie => {
            const ubicacion = especie.ubicaciones[0];
            const imagen = especie.imagenes[0];
            const fila = document.createElement('tr');

            fila.innerHTML = `
                <td>${especie.esp_nombre_comun}</td>
                <td>${especie.genero.familia.reino.reino_nombre}</td>
                <td>${especie.genero.familia.fam_nombre}</td>
                <td>${especie.genero.gene_nombre}</td>
                <td>${especie.esp_nombre_cientifico}</td>
                <td>${ubicacion ? `${ubicacion.ubi_latitud}, ${ubicacion.ubi_longitud}` : 'N/A'}</td>
                <td>${especie.created_at ? new Date(especie.created_at).toLocaleDateString() : 'N/A'}</td>
                <td>
                    ${imagen ? `<img src="/storage/${imagen.img_ruta}" alt="${especie.esp_nombre_comun}" width="50" height="50">` : 'Sin imagen'}
                </td>
                <td>${especie.esp_estado_valid ? 'Aprobada' : 'Pendiente'}</td>
                <td>
                    <button class="btn btn-danger btn-sm" onclick="eliminarRegistro(${especie.esp_id})">Eliminar Registro</button>
                </td>
            `;
            tbody.appendChild(fila);
        });
    })
    .catch(error => {
        console.error('Error al cargar los datos:', error);
        alert('Error al cargar los datos. Por favor, intenta nuevamente.');
    });
}



/*window.eliminarRegistro = function (id) {
    if (confirm('¿Estás seguro de que deseas eliminar este registro?')) {
        fetch(`/especies/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
        })
            .then(response => {
                if (response.ok) {
                    alert('Registro eliminado con éxito.');
                    cargarDatos(); // Recargar la tabla
                } else {
                    alert('Error al eliminar el registro.');
                }
            })
            .catch(error => console.error('Error al eliminar el registro:', error));
    }
};

// Función para cargar datos de registro
// Función para cargar los datos y el estado de validación
function cargarDatos() {
    fetch('/especies', {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
        },
    })
        .then(response => response.json())
        .then(data => {
            const tbody = document.querySelector('#tabla-datos tbody');
            tbody.innerHTML = ''; // Limpiar tabla antes de agregar datos

            data.forEach(especie => {
                const ubicacion = especie.ubicaciones[0];
                const imagen = especie.imagenes[0];
                const fila = document.createElement('tr');

                fila.innerHTML = `
                    <td>${especie.esp_nombre_comun}</td>
                    <td>${especie.genero.familia.reino.reino_nombre}</td>
                    <td>${especie.genero.familia.fam_nombre}</td>
                    <td>${especie.genero.gene_nombre}</td>
                    <td>${especie.esp_nombre_cientifico}</td>
                    <td>${ubicacion ? `${ubicacion.ubi_latitud}, ${ubicacion.ubi_longitud}` : 'N/A'}</td>
                    <td>${especie.created_at ? new Date(especie.created_at).toLocaleDateString() : 'N/A'}</td>
                    <td>
                        ${imagen ? `<img src="/storage/${imagen.img_ruta}" alt="${especie.esp_nombre_comun}" width="50" height="50">` : 'Sin imagen'}
                    </td>
                    <td>${especie.esp_estado_valid ? 'Aprobada' : 'Pendiente'}</td>
                    <td>
                        <button class="btn btn-danger btn-sm" onclick="eliminarRegistro(${especie.esp_id})">Eliminar Registro</button>
                    </td>
                `;
                tbody.appendChild(fila);
            });
        })
        .catch(error => console.error('Error al cargar los datos:', error));
}
*/



// Función para cargar observaciones
function cargarObservaciones() {
    fetch('/especies', {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
        },
    })
        .then(response => response.json())
        .then(data => {
            const grid = document.getElementById('vista-cuadricula');
            grid.innerHTML = ''; // Limpiar la cuadrícula

            // Limpiar marcadores previos del mapa
            markers.forEach(marker => mapObservaciones.removeLayer(marker));
            markers = [];

            data.forEach(especie => {
                // Crear tarjeta en la cuadrícula
                const tarjeta = document.createElement('div');
                tarjeta.classList.add('observacion-card');
                tarjeta.innerHTML = `
                    <img src="${especie.imagenes[0] ? `/storage/${especie.imagenes[0].img_ruta}` : 'ruta_por_defecto.jpg'}" alt="${especie.esp_nombre_comun}">
                    <h3>${especie.esp_nombre_comun || 'Sin Nombre'}</h3>
                    <p>${especie.esp_nombre_cientifico || 'Sin Nombre Científico'}</p>
                    <button class="btn btn-primary btn-sm" onclick="enfocarMapa(${especie.ubicaciones[0].ubi_latitud}, ${especie.ubicaciones[0].ubi_longitud})">Ver en el Mapa</button>
                `;
                grid.appendChild(tarjeta);

                // Crear marcador en el mapa
                if (especie.ubicaciones && especie.ubicaciones[0]) {
                    const marker = L.marker([
                        especie.ubicaciones[0].ubi_latitud,
                        especie.ubicaciones[0].ubi_longitud,
                    ]).addTo(mapObservaciones);
                    marker.bindPopup(`
                        <strong>${especie.esp_nombre_comun}</strong><br>
                        ${especie.esp_nombre_cientifico || 'Sin Nombre Científico'}
                    `);
                    markers.push(marker);
                }
            });
        })
        .catch(error => console.error('Error al cargar observaciones:', error));
}

// Función para enfocar el mapa en una ubicación específica
function enfocarMapa(latitud, longitud) {
    mapObservaciones.setView([latitud, longitud], 15);
}

// Función para manejar las vistas de cuadrícula y mapa
function mostrarCuadricula() {
    document.getElementById('vista-cuadricula').style.display = 'grid';
    document.getElementById('vista-mapa').style.display = 'none';
}

function mostrarMapa() {
    document.getElementById('vista-cuadricula').style.display = 'none';
    document.getElementById('vista-mapa').style.display = 'block';
    mapObservaciones.invalidateSize(); // Refrescar el mapa
}

document.addEventListener('DOMContentLoaded', () => {
    // Cargar datos al inicializar
    cargarDatos();

    // Inicializar el mapa
    map = L.map('map').setView([0.3517, -78.1223], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);
    marker = L.marker([0.3517, -78.1223], { draggable: false }).addTo(map);

    // Asignar el evento al botón de búsqueda
    const buscarBtn = document.getElementById('buscar-btn');
    if (buscarBtn) {
        buscarBtn.addEventListener('click', buscarRegistro);
    }
});

// =======================
// Funciones de datos
// =======================

// Cargar registros en la tabla
/*function cargarDatos() {
    fetch('/especies', {
        method: 'GET',
        headers: { 'Content-Type': 'application/json' },
    })
        .then(response => response.json())
        .then(data => {
            const tbody = document.querySelector('#tabla-datos tbody');
            tbody.innerHTML = '';

            data.forEach(especie => {
                const ubicacion = especie.ubicaciones[0];
                const imagen = especie.imagenes[0];
                const fila = document.createElement('tr');

                fila.innerHTML = `
                <td>${especie.esp_nombre_comun}</td>
                <td>${especie.genero.familia.reino.reino_nombre}</td>
                <td>${especie.genero.familia.fam_nombre}</td>
                <td>${especie.genero.gene_nombre}</td>
                <td>${especie.esp_nombre_cientifico}</td>
                <td>${ubicacion ? `${ubicacion.ubi_latitud}, ${ubicacion.ubi_longitud}` : 'N/A'}</td>
                <td>${especie.created_at ? new Date(especie.created_at).toLocaleDateString() : 'N/A'}</td>
                <td>${imagen ? `<img src="/storage/${imagen.img_ruta}" alt="${especie.esp_nombre_comun}" width="50" height="50">` : 'Sin imagen'}</td>
                <td>
                    <button class="btn btn-success btn-sm" onclick="enviarRevision(${especie.esp_id})">Enviar Revisión</button>
                    <button class="btn btn-danger btn-sm" onclick="eliminarRegistro(${especie.esp_id})">Eliminar Registro</button>
                </td>
            `;
                tbody.appendChild(fila);
            });
        })
        .catch(error => console.error('Error al cargar los datos:', error));
}*/


/*window.enviarRevision = function (id) {
    if (confirm('¿Estás seguro de que deseas enviar este registro para revisión?')) {
        fetch(`/especies/${id}/revision`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            }
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    cargarDatos(); // Recargar la tabla
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al enviar el registro a revisión');
            });
    }
};*/



// Función para buscar un registro y rellenar el formulario de edición
function buscarRegistro() {
    const nombreComun = document.getElementById('buscar_nombre_comun').value;

    if (!nombreComun.trim()) {
        alert('Por favor, ingresa un nombre común para buscar.');
        return;
    }

    fetch('/especies/buscar', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
        },
        body: JSON.stringify({ nombre_comun: nombreComun }),
    })
        .then(response => response.json())
        .then(data => {
            if (data.esp_id) {
                // Rellenar los campos del formulario de edición
                document.getElementById('editar_esp_id').value = data.esp_id;
                document.getElementById('editar_nombre_comun').value = data.esp_nombre_comun || '';
                document.getElementById('editar_reino').value = data.reino_nombre || '';
                document.getElementById('editar_familia').value = data.fam_nombre || '';
                document.getElementById('editar_genero').value = data.gene_nombre || '';
                document.getElementById('editar_nombre_cientifico').value = data.esp_nombre_cientifico || '';
                document.getElementById('editar_descripcion').value = data.esp_descripcion || '';
                document.getElementById('editar_latitud').value = data.latitud || '';
                document.getElementById('editar_longitud').value = data.longitud || '';
                document.getElementById('editar_region').value = data.region || '';
                document.getElementById('editar_descripcion_ubicacion').value = data.descripcion_ubicacion || '';
            } else {
                alert('Especie no encontrada.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Hubo un problema al buscar el registro. Por favor, intenta nuevamente.');
        });
}

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

function logout() {
    if (confirm('¿Estás seguro de que deseas salir?')) {
        fetch('/logout', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
            },
        })
            .then(response => {
                if (response.ok) {
                    window.location.href = '/login';
                } else {
                    alert('Error al cerrar sesión. Por favor, intenta de nuevo.');
                }
            })
            .catch(error => console.error('Error al cerrar sesión:', error));
    }
}

// Función para manejar el menú desplegable del usuario
function toggleUserMenu() {
    const dropdown = document.getElementById('user-dropdown');
    dropdown.classList.toggle('show');
}

// Cerrar menú desplegable si se hace clic fuera de él
window.addEventListener('click', function (e) {
    const dropdown = document.getElementById('user-dropdown');
    if (!dropdown.contains(e.target) && !e.target.matches('.user-icon')) {
        dropdown.classList.remove('show');
    }
});