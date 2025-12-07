<?php
/**
 * ! Desactivamos reporte de errores a pantalla para evitar fugas de información.
 */
ini_set('display_errors', 0);
error_reporting(E_ALL);

// Forzamos a MySQLi a lanzar excepciones para poder capturarlas con try-catch
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

require_once 'config/conexion.php';
require_once 'logic/cultivos.php';

$conexion_status_msg = "<span style='color:#ff6347;'>Desconectado</span>";
$mensaje = "";

/**
 * & Procesamos el formulario al enviarse (método POST)
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $conexion = conectarBDD();
        if (!$conexion)
            throw new Exception("No se pudo conectar a la BD");
        // TODO: Establecer conjunto de caracteres UTF-8mb4
        mysqli_set_charset($conexion, "utf8mb4");
        $conexion_status_msg = "<span style='color:#9aff4d;'>Conectado</span>";

        // ! Procesamiento seguro de datos del formulario
        // TODO: CHULETA ? : === IF (X) ? A : B
        // ? 1. ¿Qué significa el ?:?
        // ? Esa estructura evalúa la parte de la izquierda.
        // ! Si es "Verdadera": Se queda con el valor original (el resultado de filter_input).
        // # Si es "Falsa": Pasa a lo que hay a la derecha (null).

        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT) ?: null;
        // ? ENT_QUOTES para evitar inyección de comillas simples y dobles
        $nombre = htmlspecialchars(trim($_POST['nombre'] ?? ''), ENT_QUOTES, 'UTF-8');
        $tipo = trim($_POST['tipo'] ?? '');
        $dias = filter_input(INPUT_POST, 'dias', FILTER_VALIDATE_INT) ?: 0;
        $ciclo = cicloCultivo($dias);

        if (empty($nombre) || $dias <= 0) {
            $mensaje = "<div class='msg error'>Datos incompletos.</div>";
        } else {
            // !!!! IMPORTANTE !!!!
            // TODO: Sentencia preparada para evitar Inyección SQL [cite: 38, 101]
            $sql = ($id !== null)
                // ? EN este caso de si el campo ID es null o no (lo autoincrementará AUTOMATICÁMENTE)
                ? "INSERT INTO cultivos (id, nombre, tipo, dias_cosecha, ciclo_cultivos) VALUES (?, ?, ?, ?, ?)"
                : "INSERT INTO cultivos (nombre, tipo, dias_cosecha, ciclo_cultivos) VALUES (?, ?, ?, ?)";
            $stmt = mysqli_prepare($conexion, $sql);

            // ! Depende de si el ID es null o no, vinculamos los parámetros correspondientes
            if ($id !== null)
                mysqli_stmt_bind_param($stmt, "issis", $id, $nombre, $tipo, $dias, $ciclo);
            else
                mysqli_stmt_bind_param($stmt, "ssis", $nombre, $tipo, $dias, $ciclo);

            // ? Y ejecutamos la consulta
            mysqli_stmt_execute($stmt); // Esto lanzará una excepción si el ID está repetido

            // TODO: Obtener el ID insertado (si se autogeneró), para mostrarlo en el mensaje
            $new_id = $id ?? mysqli_insert_id($conexion);
            $mensaje = "<div class='msg success'>Cultivo guardado (ID: $new_id)</div>";
            mysqli_stmt_close($stmt);
        }
        mysqli_close($conexion);

    } catch (mysqli_sql_exception $e) {
        /**
         * ! Captura de error de ID DUPLICADO (Código 1062)
         * Evitamos el error 500 capturando el fallo aquí.
         */
        // ? Código de error 1062 indica un ID duplicado
        // TODO: (Código sacado de la documentación oficial de MySQL)
        if ($e->getCode() == 1062) {
            $mensaje = "<div class='msg warning'>¡Atención! El ID <strong>$id</strong> ya existe. Deja el campo vacío para autogenerar uno.</div>";
        } else {
            // ! Este mensaje es por si salta otro error diferente al de ID duplicado (Mirar Andrés en clase)
            $mensaje = "<div class='msg error'>Error de base de datos: " . $e->getMessage() . "</div>";
        }
    } catch (Exception $e) {
        $mensaje = "<div class='msg error'>Error: " . $e->getMessage() . "</div>";
    }
} else {
    $conexion = conectarBDD();
    if ($conexion) {
        $conexion_status_msg = "<span style='color:#9aff4d;'>Conectado</span>";
        mysqli_close($conexion);
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Cultivo - Mi Huerta</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@500;600;700&family=Playfair+Display:wght@700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="style-nuevo.css">
</head>

<body>
    <div class="content">
        <?php echo $mensaje; ?>
        <div class="card">
            <h1>Insertar Cultivo</h1>
            <form method="post">
                <div class="field">
                    <label>ID (opcional):</label>
                    <input type="number" name="id" min="1" placeholder="Ej: 101">
                </div>
                <div class="field">
                    <label>Nombre del Cultivo:</label>
                    <input type="text" name="nombre" required placeholder="Ej: Tomate Cherry">
                </div>
                <div class="field">
                    <label>Tipo:</label>
                    <select name="tipo" required>
                        <option value="" disabled selected>Selecciona un tipo</option>
                        <option value="Hortaliza">Hortaliza</option>
                        <option value="Fruto">Fruto</option>
                        <option value="Aromática">Aromática</option>
                        <option value="Legumbre">Legumbre</option>
                        <option value="Tubérculo">Tubérculo</option>
                    </select>
                </div>
                <div class="field">
                    <label>Días para Cosecha:</label>
                    <input type="number" name="dias" min="1" required placeholder="Ej: 60">
                </div>

                <button class="button" type="submit">Guardar Cultivo</button>
                <button type="button" class="button button-secondary" onclick="location.href='index.php'">Volver al
                    Inicio</button>
            </form>
            <p style="text-align: center; margin-top: 20px; font-size: 0.8rem; opacity: 0.6;">
                Estado BD: <?php echo $conexion_status_msg; ?>
            </p>
        </div>
    </div>
</body>

</html>