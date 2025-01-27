// Inicialización al cargar el documento
document.addEventListener('DOMContentLoaded', function() {
    initializeEditableCells();
    setupModalClose();
});

// Inicializar celdas editables
function initializeEditableCells() {
    const editableCells = document.querySelectorAll('.editable');
    editableCells.forEach(cell => {
        cell.addEventListener('dblclick', function() {
            makeEditable(this);
        });
    });
}

// Hacer una celda editable
function makeEditable(cell) {
    const originalText = cell.dataset.original;
    const input = document.createElement('input');
    input.type = 'text';
    input.value = originalText;
    input.className = 'edit-input';
    
    cell.innerHTML = '';
    cell.appendChild(input);
    input.focus();

    input.addEventListener('blur', function() {
        const newValue = input.value.trim();
        if (newValue === '' || newValue === originalText) {
            cell.innerHTML = originalText; // Restaurar valor original si no hay cambios
        } else {
            finishEditing(cell, input);
        }
    });
    
    input.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            const newValue = input.value.trim();
            if (newValue === '' || newValue === originalText) {
                cell.innerHTML = originalText; // Restaurar valor original si no hay cambios
            } else {
                finishEditing(cell, input);
            }
        }
    });

    input.addEventListener('keyup', function(e) {
        if (e.key === 'Escape') {
            cell.innerHTML = originalText; // Restaurar valor original al presionar ESC
        }
    });
}

// Finalizar la edición
async function finishEditing(cell, input) {
    const newValue = input.value.trim();
    const especieId = cell.closest('tr').dataset.especieId;
    const field = cell.dataset.field;
    
    try {
        const response = await actualizarEspecie(especieId, field, newValue);
        if (response.success) {
            cell.innerHTML = newValue;
            cell.dataset.original = newValue;
            mostrarNotificacion('Registro actualizado correctamente', 'success');
        } else {
            cell.innerHTML = cell.dataset.original;
            mostrarNotificacion('Error al actualizar el registro', 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        cell.innerHTML = cell.dataset.original;
        mostrarNotificacion('Error al actualizar el registro', 'error');
    }
}

// Actualizar especie en el servidor
async function actualizarEspecie(especieId, field, value) {
    try {
        const response = await fetch(`/especies/${especieId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                [field]: value
            })
        });
        return await response.json();
    } catch (error) {
        console.error('Error en la actualización:', error);
        throw error;
    }
}

// Actualizar imagen
async function actualizarImagen(input, especieId) {
    const file = input.files[0];
    if (!file) return;

    const formData = new FormData();
    formData.append('imagen', file);
    formData.append('_method', 'PUT');
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

    try {
        const response = await fetch(`/especies/${especieId}/imagen`, {
            method: 'POST',
            body: formData
        });

        const data = await response.json();
        
        if (data.success) {
            const imgElement = input.closest('.image-cell').querySelector('.bird-image');
            if (imgElement) {
                imgElement.src = URL.createObjectURL(file);
            } else {
                location.reload();
            }
            mostrarNotificacion('Imagen actualizada correctamente', 'success');
        } else {
            throw new Error(data.message || 'Error al actualizar la imagen');
        }
    } catch (error) {
        mostrarNotificacion('Error al actualizar la imagen', 'error');
        console.error('Error:', error);
    }
}

// Validar especie
function validarEspecie(especieId, estado) {
    Swal.fire({
        title: `¿Estás seguro de ${estado.toLowerCase()} esta especie?`,
        text: "Esta acción no se puede deshacer",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: estado === 'Aprobado' ? '#4CAF50' : '#f44336',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, confirmar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            const formData = new FormData();
            formData.append('estado', estado);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

            fetch(`/especies/${especieId}/validar`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ estado: estado })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire(
                        '¡Completado!',
                        `La especie ha sido ${estado.toLowerCase()} exitosamente`,
                        'success'
                    ).then(() => {
                        // Actualizar la interfaz
                        const row = document.querySelector(`tr[data-especie-id="${especieId}"]`);
                        const estadoCell = row.querySelector('.estado-badge');
                        estadoCell.className = `estado-badge estado-${estado.toLowerCase()}`;
                        estadoCell.textContent = estado;

                        // Ocultar los botones de acción
                        const actionButtons = row.querySelector('.action-buttons');
                        actionButtons.innerHTML = `<span class="estado-${estado.toLowerCase()}">${estado}</span>`;
                    });
                } else {
                    throw new Error(data.message);
                }
            })
            .catch(error => {
                Swal.fire(
                    'Error',
                    'Hubo un problema al procesar la solicitud',
                    'error'
                );
                console.error('Error:', error);
            });
        }
    });
}

// Enviar validación al servidor
async function enviarValidacion(especieId, estado) {
    try {
        const response = await fetch(`/especies/${especieId}/validar`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ estado })
        });

        const data = await response.json();
        
        if (data.success) {
            const row = document.querySelector(`tr[data-especie-id="${especieId}"]`);
            const estadoCell = row.querySelector('.estado-cell');
            const estadoBadge = estadoCell.querySelector('.estado-badge');
            
            estadoBadge.className = `estado-badge estado-${estado.toLowerCase()}`;
            estadoBadge.textContent = estado;
            
            // Ocultar botones después de la validación
            const actionButtons = row.querySelector('.action-buttons');
            actionButtons.innerHTML = `<span class="estado-${estado.toLowerCase()}">${estado}</span>`;
            
            mostrarNotificacion(`Especie ${estado.toLowerCase()} exitosamente`, 'success');
        } else {
            throw new Error(data.message || 'Error al procesar la validación');
        }
    } catch (error) {
        mostrarNotificacion('Error al procesar la validación', 'error');
        console.error('Error:', error);
    }
}

// Aplicar filtros
function aplicarFiltros() {
    const especieFiltro = document.getElementById('filter-species').value.toLowerCase();
    const estadoFiltro = document.getElementById('filter-status').value;
    
    const filas = document.querySelectorAll('tbody tr');
    
    filas.forEach(fila => {
        const nombreComun = fila.querySelector('[data-field="esp_nombre_comun"]').innerText.toLowerCase();
        const nombreCientifico = fila.querySelector('[data-field="esp_nombre_cientifico"]').innerText.toLowerCase();
        const estado = fila.querySelector('.estado-badge').innerText;
        
        const coincideEspecie = nombreComun.includes(especieFiltro) || 
                               nombreCientifico.includes(especieFiltro) || 
                               especieFiltro === '';
        const coincideEstado = estado === estadoFiltro || estadoFiltro === '';
        
        fila.style.display = coincideEspecie && coincideEstado ? '' : 'none';
    });
}

// Funciones para el modal de imagen
function mostrarImagenCompleta(src) {
    const modal = document.getElementById('imagen-modal');
    const modalImg = document.getElementById('imagen-completa');
    modal.style.display = "block";
    modalImg.src = src;
}

function setupModalClose() {
    const modal = document.getElementById('imagen-modal');
    const closeBtn = document.querySelector('.modal-close');
    
    closeBtn.onclick = function() {
        modal.style.display = "none";
    }
    
    window.onclick = function(event) {
        if (event.target === modal) {
            modal.style.display = "none";
        }
    }
}

// Mostrar notificaciones
function mostrarNotificacion(mensaje, tipo) {
    Swal.fire({
        title: tipo === 'success' ? '¡Éxito!' : 'Error',
        text: mensaje,
        icon: tipo,
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true
    });
}

// Manejo de errores global
window.addEventListener('error', function(e) {
    console.error('Error global:', e.error);
    mostrarNotificacion('Ha ocurrido un error inesperado', 'error');
});