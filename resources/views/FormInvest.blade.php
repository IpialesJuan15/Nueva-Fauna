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
        <h1>Gestión de Datos y Validacion</h1>
        <nav>
            <ul>
                <li><a href="#" onclick="showSection('registro'); return false;">Registro</a></li>
                <li><a href="#" onclick="showSection('editar'); return false;">Editar</a></li>
                <li><a href="#" onclick="showSection('filtrar'); return false;">Buscar</a></li>
                <li><a href="#" onclick="showSection('datos_ingresados'); return false;">Datos Ingresados</a></li>
                <li><a href="#" onclick="showSection('salir'); return false;">Salir</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <!-- Registro -->
        <section id="registro" class="content">
            <h2>Registro de Datos Taxonómicos</h2>
            <form id="form-registro" class="registro-form" action="{{ url('/especies') }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <!-- Contenedor para dividir en dos columnas -->
                <div class="registro-contenedor">
                    <!-- Columna Izquierda -->
                    <div class="registro-izquierda">
                        <label for="nombre_comun">Nombre Común:</label>
                        <input type="text" name="nombre_comun" id="nombre_comun" required>
                        <label for="reino">Reino:</label>
                        <input type="text" name="reino" id="reino" required>
                        <label for="familia">Familia:</label>
                        <input type="text" name="familia" id="familia" required>
                        <label for="genero">Género:</label>
                        <input type="text" name="genero" id="genero" required>
                        <label for="nombre_cientifico">Nombre Científico:</label>
                        <input type="text" name="nombre_cientifico" id="nombre_cientifico" required>
                        <label for="imagen">Cargar Imagen:</label>
                        <input type="file" name="imagen" id="imagen" accept="image/*" required>
                    </div>

                    <!-- Columna Derecha -->
                    <div class="registro-derecha">
                        <label for="latitud">Latitud:</label>
                        <input type="number" name="latitud" id="latitud" step="0.000001"
                            placeholder="Ejemplo: -12.0464" required>
                        <label for="longitud">Longitud:</label>
                        <input type="number" name="longitud" id="longitud" step="0.000001"
                            placeholder="Ejemplo: -77.0428" required>
                        <button type="button" onclick="actualizarMapa()">Mostrar en el Mapa</button>
                        <div id="map"></div>
                    </div>
                </div>
                <button type="submit" class="guardar-registro">Guardar Registro</button>
            </form>
        </section>



        <!-- Editar -->
        <section id="editar" class="content" style="display:none;">
            <h2>Editar Registro</h2>
            <form id="form-editar" action="{{ url('/especies/editar') }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="esp_id" id="editar_esp_id">

                <label for="buscar_nombre_comun">Buscar por Nombre Común:</label>
                <input type="text" id="buscar_nombre_comun" placeholder="Ingrese Nombre Común">
                <button type="button" id="buscar-btn">Buscar</button>

                <label for="editar_nombre_comun">Nuevo Nombre Común:</label>
                <input type="text" name="nombre_comun" id="editar_nombre_comun">
                <label for="editar_reino">Nuevo Reino:</label>
                <input type="text" name="reino" id="editar_reino">
                <label for="editar_familia">Nueva Familia:</label>
                <input type="text" name="familia" id="editar_familia">
                <label for="editar_genero">Nuevo Género:</label>
                <input type="text" name="genero" id="editar_genero">
                <label for="editar_nombre_cientifico">Nuevo Nombre Científico:</label>
                <input type="text" name="nombre_cientifico" id="editar_nombre_cientifico">
                <label for="observacion">Observación:</label>
                <textarea name="observacion" id="observacion" placeholder="Razón de la modificación..." required></textarea>
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
        <section id="datos_ingresados" class="content" style="display:none;">
            <h2>Datos Ingresados</h2>
            <table id="tabla-datos">
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
