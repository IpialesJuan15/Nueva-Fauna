<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Panel del Taxónomo</title>
    
    <!-- Fuentes y Estilos -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/taxonomo.css') }}" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
</head>

<body>
    <header>
        <nav class="navbar">
            <div class="logo">Taxonomía</div>
            <ul class="menu">
                <li><a href="{{ route('home') }}">Inicio</a></li>
                <li><a href="#" id="notification-trigger">Notificaciones</a></li>
                <li>
                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="logout-btn">Cerrar Sesión</button>
                    </form>
                </li>
            </ul>
        </nav>
    </header>

    <main>
        <h1>Gestión de Datos Taxonómicos</h1>

        <!-- Filtros -->
        <section class="filter-section">
            <h2>Buscar Registros</h2>
            <div class="filter-container">
                <div class="filter-group">
                    <label for="filter-species">Especie:</label>
                    <input type="text" id="filter-species" class="search-input" placeholder="Buscar por especie...">
                </div>
                
                <div class="filter-group">
                    <label for="filter-status">Estado:</label>
                    <select id="filter-status" class="status-select">
                        <option value="">Todos</option>
                        <option value="1">Pendiente</option>
                        <option value="2">Aprobado</option>
                        <option value="3">Rechazado</option>
                    </select>
                </div>
                
                <div class="filter-group button-group">
                    <button class="filter-apply" onclick="aplicarFiltros()">
                        <i class="fas fa-search"></i> Filtrar
                    </button>
                </div>
            </div>
        </section>

        <!-- Tabla -->
        <section class="records-section">
            <h2>Registros de Especies</h2>
            <div class="table-container">
                <table class="records-table">
                    <thead>
                        <tr>
                            <th>Imagen</th>
                            <th>Nombre Común</th>
                            <th>Nombre Científico</th>
                            <th>Descripción</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($especies as $especie)
                        <tr data-especie-id="{{ $especie->esp_id }}">
                            <td class="image-cell">
                                @if ($especie->imagenes->isNotEmpty())
                                    <img src="{{ asset('storage/' . $especie->imagenes->first()->img_ruta) }}" 
                                         alt="{{ $especie->esp_nombre_comun }}" 
                                         class="bird-image"
                                         onclick="mostrarImagenCompleta(this.src)">
                                    <div class="image-update">
                                        <input type="file" class="file-input" onchange="actualizarImagen(this, '{{ $especie->esp_id }}')" accept="image/*">
                                        <button class="update-image-btn" onclick="this.previousElementSibling.click()">
                                            <i class="fas fa-camera"></i> Actualizar
                                        </button>
                                    </div>
                                @else
                                    <div class="no-image">
                                        <input type="file" class="file-input" onchange="actualizarImagen(this, '{{ $especie->esp_id }}')" accept="image/*">
                                        <button class="update-image-btn" onclick="this.previousElementSibling.click()">
                                            <i class="fas fa-upload"></i> Subir
                                        </button>
                                    </div>
                                @endif
                            </td>
                            <td>{{ $especie->esp_nombre_comun }}</td>
                            <td>{{ $especie->esp_nombre_cientifico }}</td>
                            <td>{{ $especie->esp_descripcion }}</td>
                            <td class="estado-cell">
                                <span class="estado-badge 
                                    {{ strtolower($especie->registros->first()->regis_estado ?? 'pendiente') === 'aprobado' ? 'estado-aprobado' : '' }}
                                    {{ strtolower($especie->registros->first()->regis_estado ?? 'pendiente') === 'rechazado' ? 'estado-rechazado' : '' }}
                                    {{ strtolower($especie->registros->first()->regis_estado ?? 'pendiente') === 'pendiente' ? 'estado-pendiente' : '' }}">
                                    {{ ucfirst($especie->registros->first()->regis_estado ?? 'Pendiente') }}
                                </span>
                            </td>                            
                            <td>
                                <!-- Formulario único con comentarios -->
                                <form action="{{ route('especies.validar', ['id' => $especie->esp_id]) }}" method="POST">
                                    @csrf
                                    <textarea name="comentarios" placeholder="Comentarios (opcional)" rows="3" style="width: 100%; resize: none; padding: 8px; border-radius: 6px; border: 1px solid #ddd;"></textarea>
                                    <div style="margin-top: 10px; display: flex; gap: 10px;">
                                        <button type="submit" name="estado" value="Aprobado"
                                            class="btn btn-success" style="background-color: #4CAF50; color: white; border: none; padding: 8px 15px; border-radius: 6px; cursor: pointer; transition: all 0.3s;">
                                            <i class="fas fa-check"></i> Aprobar
                                        </button>
                                        <button type="submit" name="estado" value="Rechazado"
                                            class="btn btn-danger" style="background-color: #f44336; color: white; border: none; padding: 8px 15px; border-radius: 6px; cursor: pointer; transition: all 0.3s;">
                                            <i class="fas fa-times"></i> Rechazar
                                        </button>
                                    </div>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>

        <!-- Modal para imagen -->
        <div id="imagen-modal" class="modal">
            <span class="modal-close">&times;</span>
            <img class="modal-content" id="imagen-completa">
        </div>
    </main>

    <footer>
        <p>&copy; 2024 Sistema de Gestión de Especies - Ibarra, Ecuador</p>
    </footer>

    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/taxonomo.js') }}"></script>
</body>
</html>