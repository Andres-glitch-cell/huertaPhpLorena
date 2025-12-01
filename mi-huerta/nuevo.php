<?php
// * Comentario estándar/resaltado (Verde)
// ! Advertencia o nota crítica (Rojo intenso, Negrita)
// ? Pregunta o duda sobre el código (Azul, Cursiva)
// TODO: Tarea pendiente o algo por completar (Naranja/Ámbar, Negrita, Subrayado)
// // Comentario obsoleto o tachado (Gris oscuro, Tachado) !!!!!!!!
// & Nota de seguimiento o especial (Morado)

// --- Nuevos Comentarios ---
// @ ¡IMPORTANTE! Revisar o acción crucial (Amarillo, Fondo Semitransparente, Negrita)
// # Referencia a un ticket, enlace o doc. (Gris claro, Fondo Sólido Oscuro)
// + Código recién añadido o nueva funcionalidad (Verde claro, Negrita, Cursiva)

require_once "/config/conexion.php";
function cicloCultivo(int $dias): string
{
    if ($dias < 20) {
        return "Corto";
    } elseif ($dias < 50) {
        return "Medio";
    } else {
        return "Tardío";
    }
}

// 1. PROCESAMIENTO DEL FORMULARIO
if ($_SERVER['REQUEST_METHOD'] === 'POST') { // Comprueba si el script se está ejecutando después de un envío de formulario (método POST).
    $conexion = conectarBBDD();
    // Esto es el formato de la BBDD para que "técnicamente" acepte acentos que no los acepta pero bueno
    // TODO: mysqli_set_charset($conexion, "utf8mb4");

    if (!$conexion) {
        $mensaje = "<div class='msg error'>No se pudo conectar a la base de datos.</div>";
    } else {
        // 2.1. LECTURA Y VALIDACIÓN DE DATOS
        // Lee el ID opcional. Usa null si no es un entero válido.
        $id_input = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        if ($id_input !== false && $id_input !== null) {
            // Si la entrada fue un número entero válido
            $id = (int) $id_input;
        } else {
            // Si la validación falló (false) o el campo estaba vacío (null)
            $id = null; // Lo ignora y guarda null
        }
        // (Obtiene 'nombre', sanitiza, elimina espacios, usa cadena vacía si es null.)
        $nombre = trim(filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? '');

        // Lee el TIPO. Ahora espera el nombre completo (Hortaliza, Fruto, etc.).
        $tipo = trim(filter_input(INPUT_POST, 'tipo', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? ''); // Obtiene 'tipo' (el nombre completo) y lo sanitiza.

        // Lee y valida los días.
        $dias_input = filter_input(INPUT_POST, 'dias', FILTER_VALIDATE_INT);                   // Intenta obtener el campo 'dias' y validarlo como entero.
        // Comprobación de si $dias_input es un valor de días válido.
        if ($dias_input !== false && $dias_input !== null) {
            $dias = (int) $dias_input;
        } else {
            $dias = 0;
        }
        // Llama a la función y le pasa como parámetros la variable $dias
        $ciclo = cicloCultivo($dias);

        // VALIDACIÓN FINAL
        if (empty($nombre) || empty($tipo) || $dias <= 0) {
            $mensaje = "<div class='msg error'>Rellena todos los campos obligatorios correctamente.</div>"; // Si la validación falla, establece un mensaje de error.
        } else {
            // @ 2.2. PREPARACIÓN E INSERCIÓN (Prepared Statements podrían usarse aquí para mayor seguridad)
            /*
            // ! Proteger cadenas con mysqli_real_escape_string.
            $nombre_esc = mysqli_real_escape_string($conexion, $nombre); // Escapa el nombre para prevenir inyecciones SQL.
            // ! USAMOS EL NOMBRE COMPLETO para la BD, que debe coincidir con el ENUM.
            $tipo_esc = mysqli_real_escape_string($conexion, $tipo);    // Escapa el tipo.
            $ciclo_esc = mysqli_real_escape_string($conexion, $ciclo);   // Escapa el ciclo.
            $dias_esc = (int) $dias;                                    // Confirma que días es un entero (aunque ya fue validado).
            */
            // Define si se incluye el ID explícitamente o se usa AUTO_INCREMENT.
            $columnas = "nombre, tipo, dias_cosecha, ciclo_cultivos";          // Define las columnas a insertar (sin 'id').
            $valores = "'$nombre_esc', '$tipo_esc', $dias_esc, '$ciclo_esc'"; // Define los valores correspondientes (sin 'id').

             // @ Verifica si el usuario introdujo un ID manualmente
            if ($id !== null) {                   
                $id_esc = (int) $id;
                 // & Añade 'id' a la lista de columnas (si en este caso se proporciona).
                $columnas = "id, " . $columnas;
                 // ? Añade el valor del ID al inicio de la lista de valores. 
                $valores = $id_esc . ", " . $valores; 
            }

            // TODO: QUITAR SENTENCIAS SIN PREPARED STMT -> $sql = "INSERT INTO cultivos ($columnas) VALUES ($valores)";
            // ? 1. Definimos la parte base de la consulta SQL (sentencia preparada).
            $sql_base = "INSERT INTO cultivos ($columnas) VALUES (?, ?, ?, ?)";

            // ? 2. Definimos una variable para el fragmento condicional.
            $campoAdicinalID = "";

            // ? 3. Aplicamos la lógica IF/ELSE para establecer el fragmento.
            if ($id !== null) {
                // @ Si $id NO es null, añadimos la coma y el ? para indicarle que ira otro valor.
                $campoAdicinalID = ", ?";
            } else {
                // @ Si $id ES null, queda vacío, se queda como esta para insertarlo con el ID AUTO_INCREMENT
                $campoAdicinalID = "";
            }

            // ? 4. Concatenamos para formar la consulta SQL completa.
            $sql_completa = $sql_base . $campoAdicinalID;
            // ? 5. Usamos la consulta completa en mysqli_prepare.
            $stmt = mysqli_prepare($conexion, $sql_completa);
            if (mysqli_query($conexion, $sql)) {                                                                                                                                                                                                                 // Intenta ejecutar la consulta SQL en la ba
                $new_id = $id ?? mysqli_insert_id($conexion);                                                                                                                                                                                                        // Determina el ID insertado: usa $id (manual) o el ID generado automáticamente.
                // Usamos $tipo (el nombre completo) para el mensaje de éxito.
                $mensaje = "<div class='msg success'>✅ Cultivo '" . htmlspecialchars($nombre) . "' (Tipo: " . htmlspecialchars($tipo) . ") insertado correctamente! (ID: " . (int) $new_id . ") - Ciclo: <strong>" . htmlspecialchars($ciclo) . "</strong></div>"; // Establece el mensaje de éxito.
            } else {
                // Manejo de error de la primera inserción.
                $errno = mysqli_errno($conexion); // Obtiene el código de error numérico de MySQL (ej. 1062 para duplicado).
                $error_sql = mysqli_error($conexion); // Obtiene el mensaje de error textual de MySQL.

                if ($errno === 1062 && $id !== null) {                                                                                                            // Verifica si el error es de clave duplicada (1062) Y si se intentó insertar con un ID manual.
                    $sql_fallback = "INSERT INTO cultivos (nombre, tipo, dias_cosecha, ciclo_cultivos) VALUES ('$nombre_esc', '$tipo_esc', $dias_esc, '$ciclo_esc')"; // Construye una consulta sin ID.

                    if (mysqli_query($conexion, $sql_fallback)) {                                                                                                                                                                                                                                                         // Intenta ejecutar la consulta de "fallback" (dejar que la BD asigne el ID).
                        $new_id = mysqli_insert_id($conexion);                                                                                                                                                                                                                                                                // Obtiene el nuevo ID generado automáticamente.
                        // Mensaje de advertencia de ID cambiado.
                        $mensaje = "<div class='msg warning'>⚠️ El ID solicitado (" . (int) $id_esc . ") ya estaba ocupado; se ha asignado el ID <strong>" . (int) $new_id . "</strong> al cultivo <strong>" . htmlspecialchars($nombre) . "</strong>. - Ciclo: <strong>" . htmlspecialchars($ciclo) . "</strong></div>"; // Establece el mensaje de advertencia.
                    } else {
                        // El fallback también falló.
                        $mensaje = "<div class='msg error'>❌ ERROR SQL: Fallo al intentar auto-asignar ID tras conflicto. " . htmlspecialchars($error_sql) . "</div>"; // Establece un mensaje de error si el fallback falla.
                    }
                } else {
                    // Otros errores SQL (sintaxis, datos incompatibles, etc.).
                    $mensaje = "<div class='msg error'>❌ ERROR SQL: Fallo al insertar. (Código: $errno): " . htmlspecialchars($error_sql) . "</div>"; // Establece un mensaje de error genérico con código y descripción SQL.
                }
            }
        }
    }
    // Asegura el cierre de la conexión después de terminar.
    if ($conexion) {
        mysqli_close($conexion); // Cierra la conexión a la base de datos.
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Inserción de Cultivos</title>
    <!-- Asumiendo que 'style-nuevo.css' contiene los estilos CSS proporcionados anteriormente -->
    <link rel="stylesheet" href="style-nuevo.css">
</head>

<body>
    <div class="card">
        <h1>
            Insertar Cultivo
            <small><?php echo $conexion_status_msg; ?></small>
        </h1>
        <?php echo $mensaje; ?>

        <form method="post" action="">
            <div class="field">
                <label for="id">ID (opcional):</label>
                <input type="number" name="id" id="id" placeholder="ID (opcional)" min="1">
            </div>
            <div class="field">
                <label for="nombre">Nombre del cultivo:</label>
                <input type="text" name="nombre" id="nombre" placeholder="Nombre del cultivo" required>
            </div>
            <div class="field">
                <label for="tipo">Tipo de cultivo:</label>
                <select name="tipo" id="tipo" required>
                    <option value="" disabled selected>Selecciona un tipo</option>
                    <option value="Hortaliza">Hortaliza</option>
                    <option value="Fruto">Fruto</option>
                    <option value="Aromática">Aromática</option>
                    <option value="Legumbre">Legumbre</option>
                    <option value="Tubérculo">Tubérculo</option>
                </select>
            </div>
            <div class="field">
                <label for="dias">Días hasta cosecha:</label>
                <input type="number" name="dias" id="dias" placeholder="Días hasta cosecha" min="1" required>
            </div>
            <button type="submit" name="insertarValoresSQL">INSERTAR CULTIVO</button>
        </form>
    </div>
</body>

</html>