<?php
// ... (Tu código PHP permanece sin cambios) ...
require_once "./config/conexion.php"; // Incluye el archivo de conexión a la base de datos.
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
        $conexion_status_msg = "<span style='color:#ff6347;'>Error de Conexión</span>";
        $mensaje = "<div class='msg error'>No se pudo conectar a la base de datos.</div>";
    } else {
        $conexion_status_msg = "<span style='color:#00a878;'>Conectado</span>";

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

        // ! PROCESAMIENTO CON SENTENCIAS NO PREPARADAS (PARA MANTENER LA ESTRUCTURA ORIGINAL)
        // VALIDACIÓN FINAL
        if (empty($nombre) || empty($tipo) || $dias <= 0) {
            $mensaje = "<div class='msg error'>Rellena todos los campos obligatorios correctamente.</div>"; // Si la validación falla, establece un mensaje de error.
        } else {
            // @ 2.2. PREPARACIÓN E INSERCIÓN (Para usar mysqli_query, debemos escapar)
            $nombre_esc = mysqli_real_escape_string($conexion, $nombre);
            $tipo_esc = mysqli_real_escape_string($conexion, $tipo);
            $ciclo_esc = mysqli_real_escape_string($conexion, $ciclo);
            $dias_esc = (int) $dias;

            // Define las columnas y valores básicos
            $columnas = "nombre, tipo, dias_cosecha, ciclo_cultivos";
            $valores = "'$nombre_esc', '$tipo_esc', $dias_esc, '$ciclo_esc'";
            $sql = "INSERT INTO cultivos ($columnas) VALUES ($valores)";

            // Verifica si el usuario introdujo un ID manualmente
            if ($id !== null) {
                $id_esc = (int) $id;
                $sql = "INSERT INTO cultivos (id, nombre, tipo, dias_cosecha, ciclo_cultivos) VALUES ($id_esc, '$nombre_esc', '$tipo_esc', $dias_esc, '$ciclo_esc')";
            }

            if (mysqli_query($conexion, $sql)) {
                $new_id = $id ?? mysqli_insert_id($conexion);
                $mensaje = "<div class='msg success'>✅ Cultivo '" . htmlspecialchars($nombre) . "' (Tipo: " . htmlspecialchars($tipo) . ") insertado correctamente! (ID: " . (int) $new_id . ") - Ciclo: <strong>" . htmlspecialchars($ciclo) . "</strong></div>";
            } else {
                // Manejo de error de la primera inserción.
                $errno = mysqli_errno($conexion);
                $error_sql = mysqli_error($conexion);

                if ($errno === 1062 && $id !== null) {
                    // ! Fallback: Intenta insertar sin ID para que la BD lo asigne
                    $sql_fallback = "INSERT INTO cultivos (nombre, tipo, dias_cosecha, ciclo_cultivos) VALUES ('$nombre_esc', '$tipo_esc', $dias_esc, '$ciclo_esc')";

                    if (mysqli_query($conexion, $sql_fallback)) {
                        $new_id = mysqli_insert_id($conexion);
                        $mensaje = "<div class='msg warning'>⚠️ El ID solicitado (" . (int) $id_esc . ") ya estaba ocupado; se ha asignado el ID <strong>" . (int) $new_id . "</strong> al cultivo <strong>" . htmlspecialchars($nombre) . "</strong>. - Ciclo: <strong>" . htmlspecialchars($ciclo) . "</strong></div>";
                    } else {
                        // El fallback también falló.
                        $mensaje = "<div class='msg error'>❌ ERROR SQL: Fallo al intentar auto-asignar ID tras conflicto. " . htmlspecialchars($error_sql) . "</div>";
                    }
                } else {
                    // Otros errores SQL
                    $mensaje = "<div class='msg error'>❌ ERROR SQL: Fallo al insertar. (Código: $errno): " . htmlspecialchars($error_sql) . "</div>";
                }
            }
        }
    }
    // Asegura el cierre de la conexión después de terminar.
    if ($conexion) {
        mysqli_close($conexion); // Cierra la conexión a la base de datos.
    }
} else {
    // Estado inicial si no hay POST (Conectado o Desconectado)
    $conexion = conectarBBDD();
    if ($conexion) {
        $conexion_status_msg = "<span style='color:#00a878;'>Conectado</span>";
        mysqli_close($conexion);
    } else {
        $conexion_status_msg = "<span style='color:#ff6347;'>Error de Conexión</span>";
    }
    $mensaje = ""; // Sin mensaje inicial
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
            margin-top: 50px;
            /* Centrado absoluto */
            position: absolute;
            top: 43%;
            left: 50%;
            transform: translate(-50%, -50%);
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

        /* BOTÓN DE INSERCIÓN */
        .card button {
            width: 100%;
            background: linear-gradient(135deg, var(--verde-esmeralda), #33cc66);
            color: white;
            border: none;
            border-radius: 10px;
            padding: 15px 25px;
            font-size: 1.1em;
            font-weight: 700;
            cursor: pointer;
            /* Sombra de tierra y sombra verde */
            box-shadow: 0 8px 0 #4a2c0f, 0 12px 15px rgba(0, 168, 120, 0.5);
            transition: all 0.2s ease;
            margin-top: 10px;
        }

        .card button:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 0 #4a2c0f, 0 15px 20px rgba(0, 168, 120, 0.7);
        }

        .card button:active {
            transform: translateY(4px);
            box-shadow: 0 4px 0 #4a2c0f;
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
                /* Eliminamos el centrado absoluto si hay mucho contenido antes */
                position: relative;
                top: auto;
                left: auto;
                transform: none;
            }

            .msg {
                font-size: 1em;
            }
        }

        /* From Uiverse.io by mrhyddenn */
        button {
            background: transparent;
            color: #fff;
            font-size: 17px;
            text-transform: uppercase;
            font-weight: 600;
            border: none;
            padding: 20px 30px;
            cursor: pointer;
            perspective: 30rem;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.308);
        }

        button::before {
            content: "";
            display: block;
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            border-radius: 10px;
            background: linear-gradient(320deg,
                    rgba(0, 140, 255, 0.678),
                    rgba(128, 0, 128, 0.308));
            z-index: 1;
            transition: background 3s;
        }

        button:hover::before {
            animation: rotate 1s;
            transition: all 0.5s;
        }

        @keyframes rotate {
            0% {
                transform: rotateY(180deg);
            }

            100% {
                transform: rotateY(360deg);
            }
        }
    </style>
</head>

<body>
    <div class="content">
        <div class="card">
            <!-- From Uiverse.io by mrhyddenn -->
            <button>
                <a>Insertar Cultivos me</a><small><?php echo $conexion_status_msg; ?></small>
            </button>
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
    </div>
</body>
</html>