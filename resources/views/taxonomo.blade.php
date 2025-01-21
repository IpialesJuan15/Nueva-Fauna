<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Panel del Taxónomo</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/taxonomo.css') }}" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
</head>
<body>
    <header>
        <nav class="navbar">
            <div class="logo">Taxonomía</div>
            <ul class="menu">
                <li><a href="{{ url('/home') }}">Inicio</a></li>
                <li><a href="{{ url('/registros') }}">Registros</a></li>
                <li><a href="#" id="notification-trigger">
                    <i class="fas fa-bell"></i>
                    <span id="notification-count" class="badge">0</span>
                </a></li>
                <li>
                    <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        Cerrar Sesión
                    </a>
                </li>
            </ul>
        </nav>
    </header>

    <main>
        <h1>Gestión de Datos Taxonómicos</h1>

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <section class="filter-section">
            <h2>Buscar Registros</h2>
            <div class="filter-container">
                <label for="filter-species">Especie:</label>
                <select id="filter-species">
                    <option value="">Todas</option>
                    @foreach($revisiones->unique('especie.esp_nombre_comun') as $revision)
                        @if($revision->especie)
                            <option value="{{ $revision->especie->esp_nombre_comun }}">
                                {{ $revision->especie->esp_nombre_comun }}
                            </option>
                        @endif
                    @endforeach
                </select>
                <label for="filter-status">Estado:</label>
                <select id="filter-status">
                    <option value="">Todos</option>
                    <option value="pendiente">Pendiente</option>
                    <option value="aprobado">Aprobado</option>
                    <option value="rechazado">Rechazado</option>
                </select>
                <button class="filter-apply" onclick="filterRecords()">Filtrar</button>
            </div>
        </section>

        <section class="records-section">
            <h2>Registros para Revisión</h2>
            <table>
                <thead>
                    <tr>
                        <th>Imagen</th>
                        <th>Nombre Común</th>
                        <th>Nombre Científico</th>
                        <th>Descripción</th>
                        <th>Investigador</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="record-table">
                    @forelse($revisiones as $revision)
                        @if($revision->especie && $revision->user)
                            <tr data-revision-id="{{ $revision->id }}">
                                <td>
                                    @if($revision->especie->imagenes && $revision->especie->imagenes->first())
                                        <img src="{{ asset('storage/' . $revision->especie->imagenes->first()->img_ruta) }}" 
                                             alt="{{ $revision->especie->esp_nombre_comun }}" 
                                             class="bird-image">
                                    @else
                                        <img src="{{ asset('images/no-image.jpg') }}" 
                                             alt="No imagen disponible" 
                                             class="bird-image">
                                    @endif
                                </td>
                                <td>{{ $revision->especie->esp_nombre_comun }}</td>
                                <td>{{ $revision->especie->esp_nombre_cientifico }}</td>
                                <td>{{ $revision->especie->esp_descripcion ?? 'Sin descripción' }}</td>
                                <td>{{ $revision->user->user_nombre ?? '' }} {{ $revision->user->user_apellido ?? '' }}</td>
                                <td>
                                    <select class="status-select">
                                        <option value="pendiente" {{ $revision->estado == 'pendiente' ? 'selected' : '' }}>
                                            Pendiente
                                        </option>
                                        <option value="aprobado" {{ $revision->estado == 'aprobado' ? 'selected' : '' }}>
                                            Aprobado
                                        </option>
                                        <option value="rechazado" {{ $revision->estado == 'rechazado' ? 'selected' : '' }}>
                                            Rechazado
                                        </option>
                                    </select>
                                </td>
                                <td>
                                    <button onclick="actualizarRevision({{ $revision->id }})" class="btn-guardar">
                                        💾 Guardar
                                    </button>
                                </td>
                            </tr>
                        @endif
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">No hay registros pendientes de revisión</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </section>
    </main>

    <section id="notifications" class="hidden">
        <h2>Notificaciones</h2>
        <ul id="notifications-list"></ul>
        <button onclick="closeNotifications()">Cerrar</button>
    </section>

    <footer>
        <p>&copy; 2024 Observador de Aves - Ibarra, Ecuador</p>
    </footer>

    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="{{ asset('js/taxonomo.js') }}"></script>
</body>
</html>