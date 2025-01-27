<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Panel del Taxónomo</title>
    <!-- Fuentes y Estilos -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/taxonomo.css') }}" />

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"
        integrity="sha512-Fo3rlrQkTyZ7VRGynrVNRnjHyUqUQ+9S5eYz4jj9jK1tkmJZsWWQV7gxo4e2LmmkFv7yU2bJ2g8Q5N3v3F+1dA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
    <!-- Menú Superior -->
    <header>
        <nav class="navbar">
            <div class="logo">Taxonomía</div>
            <ul class="menu">
                <li><a href="#">Inicio</a></li>
                <li><a href="#">Registros</a></li>
                <li><a href="#" id="notification-trigger">Notificaciones</a></li>
                <li><a href="#">Cerrar Sesión</a></li>
            </ul>
        </nav>
    </header>

    <!-- Contenido Principal -->
    <main>
        <h1>Gestión de Datos Taxonómicos</h1>

        <!-- Filtros -->
        <section class="filter-section">
            <h2>Buscar Registros</h2>
            <div class="filter-container">
                <label for="filter-species">Especie:</label>
                <select id="filter-species">
                    <option value="">Todas</option>
                    <option value="Tigrisoma mexicanum">Tigrisoma mexicanum</option>
                    <option value="Ardea alba">Ardea alba</option>
                </select>
                <label for="filter-location">Ubicación:</label>
                <select id="filter-location">
                    <option value="">Todas</option>
                    <option value="Manglares">Manglares</option>
                    <option value="Humedales">Humedales</option>
                </select>
                <label for="filter-status">Estado:</label>
                <select id="filter-status">
                    <option value="">Todos</option>
                    <option value="Pendiente">Pendiente</option>
                    <option value="Aprobado">Aprobado</option>
                    <option value="Rechazado">Rechazado</option>
                </select>
                <button class="filter-apply" onclick="filterRecords()">Filtrar</button>
            </div>
        </section>

        <!-- Tabla de Registros -->
        <section class="records-section">
            <h2>Registros Pendientes</h2>
            <table>
                <thead>
                    <tr>
                        <th>Imagen</th>
                        <th>Nombre Común</th>
                        <th>Nombre Científico</th>
                        <th>Descripción</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($especies as $especie)
                        <tr>
                            <td>
                            @if ($especie->imagenes->isNotEmpty())
                                <img src="{{ asset('storage/' . $especie->imagenes->first()->img_ruta) }}" alt="{{ $especie->esp_nombre_comun }}" class="bird-image">
                                @else
                                    <span>Sin imagen disponible</span>
                            @endif
                            </td>
                            <td>{{ $especie->esp_nombre_comun }}</td>
                            <td>{{ $especie->esp_nombre_cientifico }}</td>
                            <td>{{ $especie->esp_descripcion }}</td>
                            <td>
                                <!-- Formulario único con comentarios -->
                                <form action="{{ route('especies.validar', ['id' => $especie->esp_id]) }}"
                                    method="POST">
                                    @csrf
                                    <textarea name="comentarios" placeholder="Comentarios (opcional)" rows="3"></textarea>
                                    <div style="margin-top: 10px;">
                                        <button type="submit" name="estado" value="Aprobado"
                                            class="btn btn-success">Aprobar</button>
                                        <button type="submit" name="estado" value="Rechazado"
                                            class="btn btn-danger">Rechazar</button>
                                    </div>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </section>
    </main>
    <!-- Notificaciones -->
    <section id="notifications" class="hidden">
        <h2>Notificaciones</h2>
        <ul id="notifications-list">
            <li>Registro pendiente: Tigrisoma mexicanum</li>
            <li>Registro aprobado: Ardea alba</li>
        </ul>
        <button onclick="closeNotifications()">Cerrar</button>
    </section>

    <footer>
        <p>&copy; 2024 Observador de Aves - Ibarra, Ecuador</p>
    </footer>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <!-- jsPDF -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <!-- Script Principal -->
    <script src="{{ asset('js/taxonomo.js') }}"></script>
</body>

</html>
