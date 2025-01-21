// Mostrar Notificaciones
document.getElementById('notification-trigger').addEventListener('click', function () {
    const notifications = document.getElementById('notifications');
    notifications.classList.toggle('hidden');
});

// Cerrar Notificaciones
function closeNotifications() {
    document.getElementById('notifications').classList.add('hidden');
}

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


function actualizarRevision(revisionId) {
    const row = document.querySelector(`tr[data-revision-id="${revisionId}"]`);
    const estado = row.querySelector('.status-select').value;

    fetch(`/revisiones/${revisionId}/actualizar`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        },
        body: JSON.stringify({
            estado: estado
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Revisión actualizada exitosamente');
            // Actualizar la fila o recargar la tabla según sea necesario
        } else {
            alert('Error al actualizar la revisión');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al actualizar la revisión');
    });
}

// Agregar notificaciones en tiempo real (opcional)
function actualizarNotificaciones() {
    fetch('/revisiones/pendientes/count')
        .then(response => response.json())
        .then(data => {
            const notificationsList = document.getElementById('notifications-list');
            notificationsList.innerHTML = `
                <li>Tienes ${data.count} revisiones pendientes</li>
            `;
        });
}

// Actualizar notificaciones cada minuto
setInterval(actualizarNotificaciones, 60000);