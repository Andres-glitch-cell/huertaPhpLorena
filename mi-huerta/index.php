<!DOCTYPE html>

<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Cultivos</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@500;600;700&family=Playfair+Display:wght@700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="style-index.css">
</head>

<body>
    <div class="mensajeExito" id="conexionMsg">¡Conexión MySQLi exitosa!</div>
    <div class="main-content-wrapper">
        <form method="POST">
            <div class="bloqueBotones">
                <h1 class="tituloGestionCultivos">Gestión de Cultivos</h1>
                <button class="button" type="submit" name="listarSentenciaSQL">Listar Todos los Cultivos</button>
                <button class="button" type="button" onclick="location.reload()">Recargar Página</button>
                <button class="button" type="button" onclick="location.href='nuevo.php'">Insertar Nuevo Cultivo</button>
                <button onclick="funcionNuevoSinphp()"> boton para acceder al archivo nuevo.php</button>
                <script>
                    function funcionNuevoSinphp() {
                        window.location.href = "nuevo.php";
                    }
                </script>
            </div>
        </form>

        <?php
        require_once "config/conexion.php";
        conectarBBDD();
        if (isset($_POST['listarSentenciaSQL'])) {  // Comprueba si se ha enviado el formulario por POST y si el botón con 'name="listarSentenciaSQL"' fue presionado.
            $conexion = conectarBBDD();                // Llama a la función para establecer la conexión a la base de datos MySQL.
            $sql = "SELECT * FROM cultivos";      // Define la sentencia SQL para recuperar todas las columnas (*) de la tabla 'cultivos'.
            $resultado = mysqli_query($conexion, $sql); // Ejecuta la consulta SQL ($sql) en la conexión establecida ($conexion) y almacena el resultado.
        
            if (!$resultado) { // Verifica si la ejecución de la consulta falló.
                // Muestra un párrafo de error estilizado con el mensaje de error específico de MySQL.
                echo '<p style="text-align:center;color:#e74c3c;font-weight:bold;">[ERROR] ' . mysqli_error($conexion) . '</p>';
            } elseif (mysqli_num_rows($resultado) > 0) { // Si la consulta fue exitosa, comprueba si el número de filas devueltas es mayor que cero.
                // Inicia la salida de HTML para la tabla de resultados.
                echo '<div class="table-container"><table class="styled-table">
            <thead><tr><th>ID</th><th>Nombre</th><th>Tipo</th><th>Días hasta cosecha</th></tr></thead>
            <tbody>';

                while ($fila = mysqli_fetch_assoc($resultado)) { // Itera sobre el conjunto de resultados, obteniendo cada fila como un array asociativo.
                    echo "<tr>
                <td>{$fila['id']}</td>
                <td><strong>" . htmlspecialchars($fila['nombre']) . "</strong></td>
                <td>" . htmlspecialchars($fila['tipo']) . "</td>
                <td>" . htmlspecialchars($fila['dias_cosecha']) . "</td>
            </tr>";
                }
                echo '</tbody></table></div>'; // Cierra el cuerpo de la tabla (<tbody>), la tabla (</table>) y el contenedor (</div>).
            } else {
                // Si la consulta fue exitosa pero no devolvió ninguna fila.
                echo '<p style="text-align:center;color:#e74c3c;font-size:1.5em;margin-top:40px;">No hay cultivos en la base de datos (0 filas).</p>';
            }
            mysqli_close($conexion); // Cierra explícitamente la conexión a la base de datos, liberando el recurso.
        }                        // Cierre del bloque 'if' que comprueba el envío del formulario.
        ?>
    </div>
</body>

</html>