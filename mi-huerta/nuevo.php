<?php
// Configuración de errores (ini_set)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Verificación de existencia del archivo de conexión
if (!file_exists("conexion.php")) {
    die("Error crítico: El archivo 'conexion.php' no existe en este directorio.");
}
require_once "conexion.php";

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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conexion = conectarBDD();

    if (!$conexion) {
        $mensaje = "<div class='msg error'>Error de conexión a la base de datos.</div>";
    } else {
        mysqli_set_charset($conexion, "utf8mb4");
        $conexion_status_msg = "<span style='color:#00a878;'>Conectado</span>";

        // Sanitización de inputs
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT) ?: null;
        $nombre = trim(filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_SPECIAL_CHARS));
        $tipo = trim(filter_input(INPUT_POST, 'tipo', FILTER_SANITIZE_SPECIAL_CHARS));
        $dias = filter_input(INPUT_POST, 'dias', FILTER_VALIDATE_INT) ?: 0;
        $ciclo = cicloCultivo($dias);

        if (empty($nombre) || empty($tipo) || $dias <= 0) {
            $mensaje = "<div class='msg error'>Por favor, completa los campos correctamente.</div>";
        } else {
            // SENTENCIAS PREPARADAS (Prepared Statements)
            if ($id !== null) {
                $sql = "INSERT INTO cultivos (id, nombre, tipo, dias_cosecha, ciclo_cultivos) VALUES (?, ?, ?, ?, ?)";
            } else {
                $sql = "INSERT INTO cultivos (nombre, tipo, dias_cosecha, ciclo_cultivos) VALUES (?, ?, ?, ?)";
            }

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
                    // Manejo de error de duplicado
                    if (mysqli_stmt_errno($stmt) == 1062) {
                        $mensaje = "<div class='msg warning'>El ID $id ya existe. Use otro o déjelo vacío.</div>";
                    } else {
                        $mensaje = "<div class='msg error'>Error SQL: " . mysqli_stmt_error($stmt) . "</div>";
                    }
                }
                mysqli_stmt_close($stmt);
            }
        }
        mysqli_close($conexion);
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Inserción de Cultivos</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@500;600;700&family=Playfair+Display:wght@700&display=swap"
        rel="stylesheet">

    <style>
        /* COLORES DE HUERTA ORGÁNICA VIBRANTE */
        :root {
            --verde-esmeralda: #00a878;
            /* Verde saturado y profundo */
            --naranja-tierra: #ff8c42;
            /* Naranja rojizo para acentos (frutos) */
            --verde-lima: #9aff4d;
            /* Para el brillo */
            --fondo-terracota: #f5f0e6;
            /* Fondo de la tarjeta (Terracota/Crema de Jardín) */
            --color-texto-oscuro: #1a1a1a;
            /* Degradado huerta para el botón */
            --degradado-huerta: linear-gradient(90deg, var(--verde-esmeralda), var(--naranja-tierra));
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            min-height: 100vh;
            /* Fondo que simula tierra fértil y verde bosque */
            background: linear-gradient(135deg, #2b1f00, #003314);
            color: #fff;
            overflow-x: hidden;
            position: relative;
        }

        .content {
            position: relative;
            z-index: 10;
            padding: 40px 20px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* MENSAJES DE ESTADO (Éxito, Error, Advertencia) */
        .msg {
            padding: 15px 25px;
            margin: 20px 0;
            border-radius: 10px;
            font-weight: 700;
            font-size: 1.1em;
            text-align: center;
            border: 2px solid;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
            max-width: 600px;
            width: 90%;
            color: var(--color-texto-oscuro);
        }

        .msg.success {
            background-color: var(--verde-lima);
            border-color: var(--verde-esmeralda);
            color: #004d35;
        }

        .msg.error {
            background-color: #ff6347;
            /* Rojo Tomate */
            border-color: #cc0000;
            color: white;
        }

        .msg.warning {
            background-color: var(--naranja-tierra);
            border-color: #e65c00;
            color: #582900;
        }

        /* TITULO PRINCIPAL */
        .tituloGestionCultivos {
            font-family: "Playfair Display", serif;
            font-size: 68px;
            font-weight: 900;
            text-align: center;
            margin: 40px 0 60px;
            /* Degradado verde-amarillo que simula luz solar */
            background: linear-gradient(90deg, var(--verde-lima), #fff, var(--naranja-tierra));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            /* Sombra cálida y brillante */
            text-shadow: 0 4px 30px rgba(255, 140, 66, 0.8);
            align-items: center;
            display: flex;
            justify-content: center;
            gap: 15px;
        }

        .tituloGestionCultivos::before {
            content: "";
            width: 100px;
            height: 100px;
            background: url("handGreenPlant.png") center/contain no-repeat;
            flex-shrink: 0;
            margin-right: -15px;
            /* Ajuste visual */
        }

        .tituloGestionCultivos::after {
            content: "";
            width: 100px;
            height: 100px;
            background: url("handGreenPlant.png") center/contain no-repeat;
            flex-shrink: 0;
            transform: scaleX(-1);
            /* Reflejar el icono */
            margin-left: -15px;
            /* Ajuste visual */
        }


        /* NUEVA TARJETA DE FORMULARIO - EFECTO CAJA DE JARDÍN */
        .card {
            width: 100%;
            max-width: 450px;
            padding: 30px;
            background-color: var(--fondo-terracota);
            border-radius: 20px;
            /* Borde que simula madera o maceta oscura */
            border: 5px solid #4a2c0f;
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.6);
            color: var(--color-texto-oscuro);
            /* Centrado absoluto */
            position: absolute;
            top: 50%;
            /* Centrado vertical */
            left: 50%;
            /* Centrado horizontal */
            transform: translate(-50%, -50%);
            /* Ajuste de centrado */
        }

        .card h1 {
            font-size: 2.2em;
            margin-bottom: 25px;
            color: #004d35;
            /* Verde profundo */
            border-bottom: 3px solid var(--naranja-tierra);
            padding-bottom: 10px;
            display: flex;
            justify-content: space-between;
            align-items: baseline;
        }

        .card h1 small {
            font-size: 0.4em;
            font-weight: normal;
            margin-left: 10px;
        }

        .field {
            margin-bottom: 20px;
        }

        .field label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
            color: var(--color-texto-oscuro);
        }

        .field input,
        .field select {
            width: 100%;
            padding: 12px;
            border: 2px solid #ccc;
            border-radius: 8px;
            font-size: 1em;
            transition: border-color 0.3s, box-shadow 0.3s;
            background-color: white;
            color: #333;
        }

        .field input:focus,
        .field select:focus {
            border-color: var(--verde-esmeralda);
            box-shadow: 0 0 10px rgba(0, 168, 120, 0.4);
            outline: none;
        }

        /* --- ESTILOS DEL NUEVO BOTÓN CON GRADIENTE Y NEON (Adaptados) --- */
        /* El contenedor exterior asegura el ancho completo y el borde degradado */
        .container {
            position: relative;
            padding: 3px;
            background: var(--degradado-huerta);
            /* Usa el degradado verde/naranja */
            border-radius: 0.9em;
            transition: all 0.4s ease;
            width: 100%;
            margin-top: 25px;
            /* Añadido un pequeño margen superior */
        }

        /* El botón real que se pulsa */
        .container .button {
            color: white;
            font-size: 1.1em;
            padding: 15px 25px;
            border-radius: 0.6em;
            border: none;
            background-color: #000000dd;
            /* Fondo claro de la tarjeta */
            cursor: pointer;
            box-shadow: none;
            width: 100%;
            font-weight: 700;
            transition: all 0.4s ease;
            text-transform: uppercase;
            /* Para que se vea más como botón de acción */
        }

        /* El pseudo-elemento que crea el efecto de brillo */
        .container::before {
            content: "";
            position: absolute;
            inset: 0;
            margin: auto;
            border-radius: 0.9em;
            z-index: -10;
            filter: blur(0);
            transition: filter 0.4s ease;
            background: var(--degradado-huerta);
        }

        /* Efecto NEON al pasar el ratón */
        .container:hover::before {
            background: var(--degradado-huerta);
            filter: blur(1.2em);
            opacity: 0.8;
        }

        /* Efecto de PRESIONAR */
        .container:active::before {
            filter: blur(0.2em);
        }

        .container:active .button {
            background-color: #000000ff;
            /* Se aclara al presionar */
            color: var(--verde-esmeralda);
        }

        /* MEDIA QUERIES (Ajustes para móviles) */
        @media (max-width: 768px) {
            .tituloGestionCultivos {
                font-size: 48px;
            }

            .tituloGestionCultivos::before,
            .tituloGestionCultivos::after {
                width: 70px;
                height: 70px;
            }

            .card {
                margin-top: 20px;
                padding: 20px;
                /* Revertimos el centrado absoluto en móvil para mejor scroll */
                position: relative;
                top: auto;
                left: auto;
                transform: none;
            }

            .msg {
                font-size: 1em;
            }
        }
    </style>
</head>

<body>
    <div class="content">
        <!-- Mensaje de éxito/error/warning (si existe) -->
        <?php echo $mensaje; ?>
        <div class="card">
            <h1>
                Insertar Cultivo
                <small><?php echo $conexion_status_msg; ?></small>
                <!-- Aquí se muestra 'Conectado' (verde) o 'Desconectado' (rojo) -->
            </h1>
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

                <!-- Botón de inserción con estilo -->
                <div class="container">
                    <button class="button" type="submit" name="insertarValoresSQL">Insertar Cultivo</button>

                    <script>
                        function volverAlInicio() {
                            window.location.href = 'index.php'; // Redirige a la página de inicio
                        }
                    </script>
                </div>
                <br>
                <div class="container">
                    <button onclick="volverAlInicio()" class="button" type="button">Volver al Inicio</button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>