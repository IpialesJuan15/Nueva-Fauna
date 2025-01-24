<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Panel del Taxónomo</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/taxonomo.css') }}" />
</head>
<body>
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

    <main>
        <h1>Gestión de Datos Taxonómicos</h1>

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
                            <img src="{{ asset('storage/' . $especie->imagenes->first()->img_ruta) }}" alt="{{ $especie->esp_nombre_comun }}" class="bird-image">
                        </td>
                        <td>{{ $especie->esp_nombre_comun }}</td>
                        <td>{{ $especie->esp_nombre_cientifico }}</td>
                        <td>{{ $especie->esp_descripcion }}</td>
                        <td>
                            <!-- Formulario único con comentarios -->
                            <form action="{{ route('especies.validar', ['id' => $especie->esp_id]) }}" method="POST">
                                @csrf
                                <textarea name="comentarios" placeholder="Comentarios (opcional)" rows="3"></textarea>
                                <div style="margin-top: 10px;">
                                    <button type="submit" name="estado" value="Aprobado" class="btn btn-success">Aprobar</button>
                                    <button type="submit" name="estado" value="Rechazado" class="btn btn-danger">Rechazar</button>
                                </div>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 Observador de Aves - Ibarra, Ecuador</p>
    </footer>
</body>
</html>
