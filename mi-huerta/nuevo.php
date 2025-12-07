<?php
/**
 * CONFIGURACIÓN DE ERRORES (FASE 4)
 */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

/**
 * CONTROL DE RUTAS: 
 * Según tu 'tree', conexion.php está en 'config/conexion.php'
 */
$ruta_conexion = "config/conexion.php";

if (!file_exists($ruta_conexion)) {
    die("<div style='color:red; font-family:sans-serif;'>Error crítico: No se encuentra '{$ruta_conexion}'. Verifica la ubicación.</div>");
}
require_once $ruta_conexion;

/**
 * LÓGICA DE NEGOCIO (FASE 3)
 */
function cicloCultivo(int $dias): string
{
    if ($dias < 20)
        return "Corto";
    if ($dias < 50)
        return "Medio";
    return "Tardío";
}

// Inicialización de variables de estado
$conexion_status_msg = "<span style='color:#ff6347;'>Desconectado</span>";
$mensaje = "";

/**
 * PROCESAMIENTO (FASE 2: Sentencias Preparadas)
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conexion = conectarBDD();

    if (!$conexion) {
        $mensaje = "<div class='msg error'>Error: No hay conexión con la base de datos.</div>";
    } else {
        mysqli_set_charset($conexion, "utf8mb4");
        $conexion_status_msg = "<span style='color:#9aff4d;'>Conectado</span>";

        // ✅ Sanitización estricta (FASE 5)
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT) ?: null;
        $nombre = htmlspecialchars(trim($_POST['nombre'] ?? ''), ENT_QUOTES, 'UTF-8');
        $tipo = trim($_POST['tipo'] ?? '');
        $dias = filter_input(INPUT_POST, 'dias', FILTER_VALIDATE_INT) ?: 0;
        $ciclo = cicloCultivo($dias);

        if (empty($nombre) || empty($tipo) || $dias <= 0) {
            $mensaje = "<div class='msg error'>Por favor, rellena los campos correctamente.</div>";
        } else {
            // ✅ Uso de prepared statements para blindar contra Inyección SQL
            $sql = ($id !== null)
                ? "INSERT INTO cultivos (id, nombre, tipo, dias_cosecha, ciclo_cultivos) VALUES (?, ?, ?, ?, ?)"
                : "INSERT INTO cultivos (nombre, tipo, dias_cosecha, ciclo_cultivos) VALUES (?, ?, ?, ?)";

            $stmt = mysqli_prepare($conexion, $sql);

            if ($stmt) {
                if ($id !== null) {
                    mysqli_stmt_bind_param($stmt, "issis", $id, $nombre, $tipo, $dias, $ciclo);
                } else {
                    mysqli_stmt_bind_param($stmt, "ssis", $nombre, $tipo, $dias, $ciclo);
                }

                if (mysqli_stmt_execute($stmt)) {
                    $new_id = $id ?? mysqli_insert_id($conexion);
                    $mensaje = "<div class='msg success'>Cultivo '<strong>$nombre</strong>' guardado (ID: $new_id)</div>";
                } else {
                    if (mysqli_stmt_errno($stmt) == 1062) {
                        $mensaje = "<div class='msg warning'>El ID $id ya existe. Prueba con otro.</div>";
                    } else {
                        error_log("Error SQL: " . mysqli_stmt_error($stmt));
                        $mensaje = "<div class='msg error'>Error interno del servidor.</div>";
                    }
                }
                mysqli_stmt_close($stmt);
            }
        }
        mysqli_close($conexion);
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
    <style>
        :root {
            --verde1: #9aff4d;
            --verde2: #00bfff;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #003314, #005020);
            font-family: 'Inter', sans-serif;
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .content {
            width: 100%;
            max-width: 500px;
            padding: 20px;
        }

        /* ESTILO CRISTAL PARA EL FORMULARIO */
        .card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 25px 45px rgba(0, 0, 0, 0.2);
        }

        h1 {
            font-family: 'Playfair Display', serif;
            font-size: 2.5rem;
            text-align: center;
            margin-bottom: 30px;
            background: linear-gradient(90deg, var(--verde1), #fff);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .field {
            margin-bottom: 20px;
        }

        .field label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--verde1);
        }

        /* INPUTS ESTILO GLASS */
        .field input,
        .field select {
            width: 100%;
            padding: 12px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            color: #fff;
            font-size: 1rem;
            outline: none;
            transition: 0.3s;
        }

        .field input:focus,
        .field select:focus {
            border-color: var(--verde1);
            box-shadow: 0 0 10px var(--verde1);
        }

        .field option {
            background: #003314;
            /* Fondo para que se vea el texto en el select */
        }

        /* BOTONES NEÓN */
        .button {
            width: 100%;
            background: linear-gradient(135deg, var(--verde1), var(--verde2));
            color: #111;
            border: none;
            border-radius: 12px;
            padding: 15px;
            font-size: 1.1rem;
            font-weight: 700;
            cursor: pointer;
            transition: 0.3s;
            box-shadow: 0 5px 15px rgba(154, 255, 77, 0.3);
        }

        .button:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px var(--verde1);
        }

        .button-secondary {
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
            border: 1px solid rgba(255, 255, 255, 0.2);
            margin-top: 15px;
        }

        .button-secondary:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        /* MENSAJES DE ESTADO */
        .msg {
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            text-align: center;
            font-weight: 600;
        }

        .msg.success {
            background: rgba(154, 255, 77, 0.2);
            border: 1px solid var(--verde1);
            color: var(--verde1);
        }

        .msg.error {
            background: rgba(255, 99, 71, 0.2);
            border: 1px solid #ff6347;
            color: #ff6347;
        }

        .msg.warning {
            background: rgba(255, 140, 66, 0.2);
            border: 1px solid #ff8c42;
            color: #ff8c42;
        }
    </style>
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