<!doctype html>
<html lang="en">

<head>
    <title>Investigador</title>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Bootstrap CSS v5.2.1 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
    <link rel="stylesheet" href="{{ asset('css/formInvest.css') }}" />

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>


</head>

<body>
    <header>
        <div class="navbar-container">
            <h1 onclick="mostrarObservaciones()">Gestión de Datos y Validación</h1>
            <nav>
                <ul>
                    <li><a href="#" onclick="showSection('registro'); return false;">Registro</a></li>
                    <li><a href="#" onclick="showSection('editar'); return false;">Editar</a></li>
                    <li><a href="#" onclick="showSection('filtrar'); return false;">Buscar</a></li>
                    <li><a href="#" onclick="showSection('datos_ingresados'); return false;">Datos Ingresados</a>
                    </li>
                    <li><a href="#" onclick="showSection('#'); return false;"></a></li>
                </ul>
            </nav>
        </div>
        <!-- Icono del menú de usuario -->
        <div class="user-menu">
            <img src="{{ asset('images/icoauc.jpg') }}" alt="Usuario" class="user-icon" onclick="toggleUserMenu()">
            <div class="user-dropdown" id="user-dropdown">
                <a href="#">Mi perfil</a>
                <a href="#" onclick="logout(); return false;">Salir</a>
            </div>
        </div>

    </header>


    <main>

        <section id="observaciones" class="content" style="display: none;">
            <h2>Observaciones</h2>
            <!-- Filtros y vistas -->
            <div class="observaciones-header">
                <button class="btn btn-outline-primary" onclick="mostrarMapa()">Mapa</button>
                <button class="btn btn-outline-secondary" onclick="mostrarCuadricula()">Cuadrícula</button>
            </div>

            <!-- Contenedor de la vista de cuadrícula -->
            <div id="vista-cuadricula" class="observaciones-cuadricula">
                <!-- Las tarjetas se llenarán dinámicamente con JS -->
            </div>

            <!-- Contenedor del mapa -->
            <div id="vista-mapa" style="display: none;">
                <div id="map-observaciones" style="height: 500px; width: 100%; margin-top: 20px;"></div>
            </div>
        </section>
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Registro -->
        <section id="registro" class="content">
            <h2>Registro de Datos Taxonómicos</h2>
            <form id="form-registro" action="{{ route('especies.store') }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <div class="registro-contenedor">
                    <!-- Columna Izquierda -->
                    <div class="registro-izquierda">
                        <label for="nombre_comun">Nombre Común:</label>
                        <input type="text" name="nombre_comun" id="nombre_comun" required>

                        <label for="reino">Reino:</label>
                        <select name="reino" id="reino" class="form-select" required>
                        <option value="" selected disabled>Seleccione un Reino</option>
                        @foreach ($reinos as $reino)
                            <option value="{{ $reino }}">{{ $reino }}</option>
                        @endforeach
                        </select>

                        <label for="familia">Familia:</label>
                        <!--<select name="familia" id="familia" class="form-select" required>-->
                            <input type="text" name="familia" id="familia" required>

                            <!--<option value="" selected disabled>Seleccione una Familia</option>
                            @foreach ($familias as $familia)
                                <option value="{{ $familia }}">{{ $familia }}</option>
                            @endforeach-->
                       <!-- </select>   -->

                        <label for="genero">Género:</label>
                        <input type="text" name="genero" id="genero" required>
                        <!--<select name="genero" id="genero" class="form-select" required>
                            <option value="" selected disabled>Seleccione un Género</option>
                            @foreach ($generos as $genero)
                                <option value="{{ $genero }}">{{ $genero }}</option>
                            @endforeach
                        </select>-->

                        <label for="nombre_cientifico">Nombre Científico:</label>
                        <input type="text" name="nombre_cientifico" id="nombre_cientifico" required>

                        <label for="imagen">Cargar Imagen:</label>
                        <input type="file" name="imagen" id="imagen" accept="image/*" required>

                        <!-- Nuevo campo para Descripción de la Especie -->
                        <label for="descripcion">Descripción:</label>
                        <textarea name="descripcion" id="descripcion" rows="3" required></textarea>
                    </div>

                    <!-- Columna Derecha -->
                    <div class="registro-derecha">
                        <label for="latitud">Latitud:</label>
                        <input type="number" name="latitud" id="latitud" step="0.000001"
                            placeholder="Ejemplo: -12.0464" required>

                        <label for="longitud">Longitud:</label>
                        <input type="number" name="longitud" id="longitud" step="0.000001"
                            placeholder="Ejemplo: -77.0428" required>

                        <!-- Campo para Región de la Ubicación -->
                        <label for="region">Región de la Ubicación:</label>
                        <input type="text" name="region" id="region" maxlength="100"
                            placeholder="Ejemplo: Andes Centrales" required>

                        <!-- Campo para Descripción de la Ubicación -->
                        <label for="descripcion_ubicacion">Descripción de la Ubicación:</label>
                        <textarea name="descripcion_ubicacion" id="descripcion_ubicacion" rows="3"
                            placeholder="Ejemplo: Cerca del río principal..." required></textarea>

                        <button type="button" onclick="actualizarMapa()">Mostrar en el Mapa</button>
                        <div id="map" style="height: 300px;"></div>
                    </div>
                </div>
                <button type="submit" class="guardar-registro">Guardar Registro</button>
            </form>
        </section>



        <!-- Editar -->
        <section id="editar" class="content" style="display:none;">
            <h2>Editar Registro</h2>
            <form id="form-editar" action="{{ url('/especies/editar') }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="esp_id" id="editar_esp_id">

                <!-- Campo de Búsqueda -->
                <label for="buscar_nombre_comun">Buscar por Nombre Común:</label>
                <input type="text" id="buscar_nombre_comun" placeholder="Ingrese Nombre Común">
                <button type="button" id="buscar-btn">Buscar</button>

                <div class="registro-contenedor">
                    <!-- Columna Izquierda -->
                    <div class="registro-izquierda">
                        <label for="editar_nombre_comun">Nombre Común:</label>
                        <input type="text" name="nombre_comun" id="editar_nombre_comun" required>

                        <label for="editar_reino">Reino:</label>
                        <input type="text" name="reino" id="editar_reino" required>

                        <label for="editar_familia">Familia:</label>
                        <input type="text" name="familia" id="editar_familia" required>

                        <label for="editar_genero">Género:</label>
                        <input type="text" name="genero" id="editar_genero" required>

                        <label for="editar_nombre_cientifico">Nombre Científico:</label>
                        <input type="text" name="nombre_cientifico" id="editar_nombre_cientifico" required>

                        <label for="editar_imagen">Cargar Imagen:</label>
                        <input type="file" name="imagen" id="editar_imagen" accept="image/*">

                        <!-- Nuevo campo para Descripción de la Especie -->
                        <label for="editar_descripcion">Descripción:</label>
                        <textarea name="descripcion" id="editar_descripcion" rows="3" required></textarea>
                    </div>

                    <!-- Columna Derecha -->
                    <div class="registro-derecha">
                        <label for="editar_latitud">Latitud:</label>
                        <input type="number" name="latitud" id="editar_latitud" step="0.000001" required>

                        <label for="editar_longitud">Longitud:</label>
                        <input type="number" name="longitud" id="editar_longitud" step="0.000001" required>

                        <!-- Campo para Región de la Ubicación -->
                        <label for="editar_region">Región de la Ubicación:</label>
                        <input type="text" name="region" id="editar_region" maxlength="100" required>

                        <!-- Campo para Descripción de la Ubicación -->
                        <label for="editar_descripcion_ubicacion">Descripción de la Ubicación:</label>
                        <textarea name="descripcion_ubicacion" id="editar_descripcion_ubicacion" rows="3" required></textarea>


                        <button type="button" onclick="actualizarMapaEdicion()">Mostrar en el Mapa</button>
                        <div id="map-editar" style="height: 300px;"></div>
                    </div>
                </div>
                <button type="submit">Guardar Cambios</button>
            </form>
        </section>



        <!-- Filtrar -->
        <section id="filtrar" class="content" style="display:none;">
            <h2>Buscar Registros</h2>
            <label for="buscar">Buscar por Nombre, Ubicación o Fecha:</label>
            <input type="text" id="buscar" placeholder="Ingrese el criterio de búsqueda">
            <button onclick="filtrarDatos()">Buscar</button>
            <table id="tabla-filtrada">
                <thead>
                    <tr>
                        <th>Nombre Común</th>
                        <th>Reino</th>
                        <th>Familia</th>
                        <th>Género</th>
                        <th>Nombre Científico</th>
                        <th>Ubicación</th>
                        <th>Fecha</th>
                        <th>Imagen</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </section>

        <!-- Datos Ingresados -->

        <section id="datos_ingresados" class="content">
            <h2>Datos Ingresados</h2>
            <table id="tabla-datos" class="table table-striped">
                <thead>
                    <tr>
                        <th>Nombre Común</th>
                        <th>Reino</th>
                        <th>Familia</th>
                        <th>Género</th>
                        <th>Nombre Científico</th>
                        <th>Ubicación</th>
                        <th>Fecha de Registro</th>
                        <th>Imagen</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </section>

    </main>

    <script src="{{ asset('js/formInvest.js') }}"></script>
    <!-- Bootstrap JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous">
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
        integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous">
    </script>
</body>

</html>
