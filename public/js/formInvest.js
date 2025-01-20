const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

let registros = []; // Almacena todos los registros ingresados
let map, marker;
document.addEventListener('DOMContentLoaded', () => {
    // Cargar datos al inicializar
    cargarDatos();

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
                        <td>
                            <button class="btn btn-success btn-sm" onclick="enviarRevision(${especie.esp_id})">Enviar Revisión</button>
                            <button class="btn btn-danger btn-sm" onclick="eliminarRegistro(${especie.esp_id})">Eliminar Registro</button>
                        </td>
                    `;

                    tbody.appendChild(fila);
                });
            })
            .catch(error => console.error('Error al cargar los datos:', error));
    }

    // Función para enviar una especie a revisión
    window.enviarRevision = function (id) {
        alert(`Enviar a revisión el registro con ID: ${id}`);
        // Aquí puedes implementar la lógica para enviar a revisión
    };

    // Función para eliminar un registro
    window.eliminarRegistro = function (id) {
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
});
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

// Asegúrate de que el DOM esté completamente cargado antes de ejecutar el código
document.addEventListener('DOMContentLoaded', () => {
    // Asignar el evento al botón de búsqueda
    const buscarBtn = document.getElementById('buscar-btn'); // Cambia el id según el botón de tu HTML
    if (buscarBtn) {
        buscarBtn.addEventListener('click', buscarRegistro);
    }
});

// Función para buscar un registro
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
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        },
        body: JSON.stringify({ nombre_comun: nombreComun }),
    })
        .then(response => {
            if (!response.ok) {
                throw new Error('Error al buscar el registro.');
            }
            return response.json();
        })
        .then(data => {
            if (data.esp_id) {
                // Llenar los campos del formulario con los datos obtenidos
                document.getElementById('editar_esp_id').value = data.esp_id;
                document.getElementById('editar_nombre_comun').value = data.esp_nombre_comun || '';
                document.getElementById('editar_reino').value = data.reino_nombre || '';
                document.getElementById('editar_familia').value = data.fam_nombre || '';
                document.getElementById('editar_genero').value = data.gene_nombre || '';
                document.getElementById('editar_nombre_cientifico').value = data.esp_nombre_cientifico || '';
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
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
        })
            .then(response => {
                if (response.ok) {
                    window.location.href = '/login'; // Redirige al formulario de login
                } else {
                    alert('Error al cerrar sesión. Por favor, intenta de nuevo.');
                }
            })
            .catch(error => console.error('Error al cerrar sesión:', error));
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