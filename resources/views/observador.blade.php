<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Observador de Aves - Ibarra, Ecuador</title>
    <!-- Fuentes y Estilos -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}" />

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"
          integrity="sha512-Fo3rlrQkTyZ7VRGynrVNRnjHyUqUQ+9S5eYz4jj9jK1tkmJZsWWQV7gxo4e2LmmkFv7yU2bJ2g8Q5N3v3F+1dA=="
          crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
    <header>
        <div class="header-container">
            <div class="brand">
                <i class="fas fa-dove"></i>
                <h1>Observador de Aves</h1>
            </div>
            <nav>
                <ul>
                    <li><a href="{{ url('/observador') }}">Inicio</a></li>
                    <li><a href="{{ url('/login') }}">Login</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <section class="control-section">
        <div class="search-filter-stats">
            <div class="search-bar">
                <input type="text" placeholder="Buscar por nombre común o científico" id="searchInput">
                <button id="searchButton"><i class="fas fa-search"></i></button>
            </div>
            <div class="filter">
                <label for="filterCategory">Filtrar por:</label>
                <select id="filterCategory">
                    <option value="all">Todas</option>
                    <option value="family">Familia</option>
                    <option value="genus">Género</option>
                    <option value="species">Especie</option>
                    <option value="region">Región</option>
                    <option value="date">Fecha</option>
                </select>
                <input type="text" id="filterInput" placeholder="Valor del filtro">
                <button id="applyFilter"><i class="fas fa-filter"></i></button>
            </div>
            <div class="stats">
                <div class="stat-item">
                    <p>Total de Especies</p>
                    <span id="totalSpecies">0</span>
                </div>
                <div class="stat-item">
                    <p>En Peligro</p>
                    <span id="endangeredSpecies">0</span>
                </div>
            </div>
        </div>
    </section>

    <main>
        <div class="main-content">
            <div class="map-section">
                <h3>Mapa de Ubicaciones</h3>
                <div id="map"></div>
            </div>
            <div class="species-section">
                <h3>Especies</h3>
                <div class="card-container" id="speciesList">
                    @if (!empty($especies) && $especies->count() > 0)
                        @foreach ($especies as $especie)
                            <div class="card">
                                <img src="{{ asset('storage/' . $especie->imagenes->first()->img_ruta) }}" alt="{{ $especie->esp_nombre_comun }}" class="card-image">
                                <div class="card-content">
                                    <h4>{{ $especie->esp_nombre_comun }}</h4>
                                    <p><i>{{ $especie->esp_nombre_cientifico }}</i></p>
                                    <button class="btn-details" onclick="verDetalles({{ $especie->esp_id }})">Detalles</button>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p>No hay especies disponibles para mostrar.</p>
                    @endif
                </div>
            </div>            
            
            <!-- Modal para Detalles -->
            <div id="detalleModal" class="modal">
                <div class="modal-content">
                    <span class="close" onclick="cerrarModal()">&times;</span>
                    <div id="detalleEspecie">
                        <!-- Aquí se cargará la información de la especie -->
                    </div>
                </div>
            </div>
            
        </div>
        <div class="combined-report-container">
            <button id="generateCombinedReport" class="btn combined-report-btn" style="display:none;">Generar Informe Conjunto</button>
        </div>
    </main>

    <footer>
        <p>&copy; 2024 Observador de Aves - Ibarra, Ecuador</p>
    </footer>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <!-- jsPDF -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <!-- Script Principal -->
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
