<?php
/**
 * FASE 4: CONFIGURACIÓN DE ERRORES PROFESIONAL
 * Desactivamos reporte de errores a pantalla para evitar fugas de información.
 */
ini_set('display_errors', 0);
error_reporting(E_ALL);

// Forzamos a MySQLi a lanzar excepciones para poder capturarlas con try-catch
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// CONTROL DE RUTAS [cite: 137]
$ruta_conexion = "config/conexion.php";
if (!file_exists($ruta_conexion)) {
    error_log("Archivo de conexión no encontrado.");
    die("Servicio temporalmente fuera de línea.");
}
require_once $ruta_conexion;

/**
 * FASE 3: Lógica de negocio (Funciones puras)
 */
function cicloCultivo(int $dias): string
{
    if ($dias < 20)
        return "Corto";
    if ($dias < 50)
        return "Medio";
    return "Tardío";
}

$conexion_status_msg = "<span style='color:#ff6347;'>Desconectado</span>";
$mensaje = "";

/**
 * PROCESAMIENTO SEGURO (FASE 2 Y 4)
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $conexion = conectarBDD();
        if (!$conexion)
            throw new Exception("No se pudo conectar a la BD");

        mysqli_set_charset($conexion, "utf8mb4");
        $conexion_status_msg = "<span style='color:#9aff4d;'>Conectado</span>";

        // ✅ SANITIZACIÓN (FASE 5)
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT) ?: null;
        $nombre = htmlspecialchars(trim($_POST['nombre'] ?? ''), ENT_QUOTES, 'UTF-8');
        $tipo = trim($_POST['tipo'] ?? '');
        $dias = filter_input(INPUT_POST, 'dias', FILTER_VALIDATE_INT) ?: 0;
        $ciclo = cicloCultivo($dias);

        if (empty($nombre) || $dias <= 0) {
            $mensaje = "<div class='msg error'>Datos incompletos.</div>";
        } else {
            // Sentencia preparada para evitar Inyección SQL [cite: 38, 101]
            $sql = ($id !== null)
                ? "INSERT INTO cultivos (id, nombre, tipo, dias_cosecha, ciclo_cultivos) VALUES (?, ?, ?, ?, ?)"
                : "INSERT INTO cultivos (nombre, tipo, dias_cosecha, ciclo_cultivos) VALUES (?, ?, ?, ?)";

            $stmt = mysqli_prepare($conexion, $sql);

            if ($id !== null)
                mysqli_stmt_bind_param($stmt, "issis", $id, $nombre, $tipo, $dias, $ciclo);
            else
                mysqli_stmt_bind_param($stmt, "ssis", $nombre, $tipo, $dias, $ciclo);

            mysqli_stmt_execute($stmt); // Esto lanzará una excepción si el ID está repetido

            $new_id = $id ?? mysqli_insert_id($conexion);
            $mensaje = "<div class='msg success'>Cultivo guardado (ID: $new_id)</div>";
            mysqli_stmt_close($stmt);
        }
        mysqli_close($conexion);

    } catch (mysqli_sql_exception $e) {
        /**
         * FASE 4: Captura de error de ID DUPLICADO (Código 1062)
         * Evitamos el error 500 capturando el fallo aquí.
         */
        if ($e->getCode() == 1062) {
            $mensaje = "<div class='msg warning'>¡Atención! El ID <strong>$id</strong> ya existe. Deja el campo vacío para autogenerar uno.</div>";
        } else {
            error_log("Error de BD: " . $e->getMessage()); // Registro interno [cite: 92]
            $mensaje = "<div class='msg error'>Error interno del sistema.</div>";
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