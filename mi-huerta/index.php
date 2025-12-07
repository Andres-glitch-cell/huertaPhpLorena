<?php
/**
 * FASE 4: Manejo seguro de errores.
 * Se desactivan errores en pantalla para no revelar rutas técnicas.
 */
ini_set('display_errors', 0);
error_reporting(E_ALL);

// Carga segura de conexión
require_once "config/conexion.php";
$tabla_html = "";
/**
 * Listar cultivos al pulsar el botón
 */
if (isset($_POST['listarSentenciaSQL'])) {
    $conexion = conectarBDD();

    if (!$conexion) {
        $tabla_html = '<p style="text-align:center;color:#ff6347;font-weight:bold;">Servicio no disponible temporalmente.</p>';
    } else {
        mysqli_set_charset($conexion, "utf8mb4");

        $sql = "SELECT id, nombre, tipo, dias_cosecha FROM cultivos";
        $stmt = mysqli_prepare($conexion, $sql);

        if ($stmt) {
            mysqli_stmt_execute($stmt);
            $resultado = mysqli_stmt_get_result($stmt);

            if (mysqli_num_rows($resultado) > 0) {
                $tabla_html .= '<div class="table-container"><table class="styled-table">';
                $tabla_html .= '<thead><tr><th>ID</th><th>Nombre</th><th>Tipo</th><th>Días hasta cosecha</th></tr></thead><tbody>';

                while ($fila = mysqli_fetch_assoc($resultado)) {
                    $tabla_html .= "<tr>";
                    $tabla_html .= "<td>" . htmlspecialchars($fila['id'], ENT_QUOTES, 'UTF-8') . "</td>";
                    $tabla_html .= "<td><strong>" . htmlspecialchars($fila['nombre'], ENT_QUOTES, 'UTF-8') . "</strong></td>";
                    $tabla_html .= "<td>" . htmlspecialchars($fila['tipo'], ENT_QUOTES, 'UTF-8') . "</td>";
                    $tabla_html .= "<td>" . htmlspecialchars($fila['dias_cosecha'], ENT_QUOTES, 'UTF-8') . " días</td>";
                    $tabla_html .= "</tr>";
                }

                $tabla_html .= '</tbody></table></div>';
            } else {
                $tabla_html = '<p style="text-align:center;color:#95a5a6;margin:80px 0;font-size:1.2em;">No hay cultivos registrados todavía.</p>';
            }

            mysqli_stmt_close($stmt);
        } else {
            error_log("Error preparando consulta SELECT cultivos: " . mysqli_error($conexion));
            $tabla_html = '<p style="text-align:center;color:#ff6347;">Error interno del servidor.</p>';
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
    <title>Gestión de Cultivos</title>
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
            overflow-x: hidden;
            position: relative;
        }

        .content {
            position: relative;
            z-index: 10;
            padding: 40px 20px;
            min-height: 100vh;
        }

        .mensajeExito {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 3.2rem;
            font-weight: 800;
            background: linear-gradient(90deg, #d4ff00, #4dff9a);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            text-shadow: 0 0 25px rgba(212, 255, 0, 0.8);
            z-index: 9999;
            pointer-events: none;
            opacity: 0;
            transition: opacity 1s;
        }

        .tituloGestionCultivos {
            font-family: "Playfair Display", serif;
            font-size: 68px;
            font-weight: 900;
            text-align: center;
            margin: 40px 0 60px;
            background: linear-gradient(90deg, #9aff4d, #ccff80, #9aff4d);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            text-shadow: 0 4px 8px rgba(0, 0, 0, 0.4);
        }

        .bloqueBotones {
            text-align: center;
            margin-bottom: 80px;
        }

        .button {
            background: linear-gradient(135deg, var(--verde1), var(--verde2));
            color: #111;
            border: none;
            border-radius: 16px;
            padding: 18px 40px;
            margin: 12px;
            font-size: 18px;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 12px 30px rgba(0, 191, 255, 0.5);
            transition: all .3s ease;
        }

        .button:hover {
            transform: translateY(-8px);
            box-shadow: 0 25px 45px rgba(154, 255, 77, 0.8);
        }

        /* Bloques cristal */
        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 80px 0;
        }

        .container .glass {
            /* ... todo tu CSS cristal permanece igual ... */
            position: relative;
            width: 180px;
            height: 200px;
            background: linear-gradient(#fff2, transparent);
            border: 1px solid rgba(255, 255, 255, .1);
            box-shadow: 0 25px 25px rgba(0, 0, 0, .25);
            display: flex;
            justify-content: center;
            align-items: center;
            transition: .5s;
            border-radius: 10px;
            margin: 0 -45px;
            backdrop-filter: blur(10px);
            transform: rotate(calc(var(--r)*1deg));
            cursor: pointer;
        }

        .container .glass:hover {
            transform: rotate(0) translateY(-10px);
            margin: 0 20px;
            background: rgba(255, 255, 255, .2);
            box-shadow: 0 0 25px var(--verde1), 0 10px 30px rgba(0, 0, 0, .5);
            border: 1px solid var(--verde1);
        }

        .container .glass:hover svg {
            fill: var(--verde1);
            transform: scale(1.2);
            filter: drop-shadow(0 0 8px var(--verde1));
        }

        .container .glass::before {
            content: attr(data-text);
            position: absolute;
            bottom: 0;
            width: 100%;
            height: 40px;
            background: rgba(255, 255, 255, .05);
            display: flex;
            justify-content: center;
            align-items: center;
            color: #fff;
            font-weight: 600;
            font-size: 1.1em;
        }

        .container .glass svg {
            font-size: 2.5em;
            fill: #fff;
        }

        /* Reloj */
        .card {
            position: fixed;
            top: 20px;
            right: 20px;
            width: 280px;
            height: 150px;
            background: rgba(0, 51, 20, .6);
            border: 1px solid rgba(154, 255, 77, .3);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, .3), 0 0 20px rgba(154, 255, 77, .4);
            display: flex;
            flex-direction: column;
            justify-content: center;
            color: white;
            z-index: 9999;
            transition: all .3s;
        }

        .card:hover {
            box-shadow: 0 8px 32px rgba(0, 0, 0, .5), 0 0 30px rgba(154, 255, 77, .6);
        }

        .time-text {
            font-size: 50px;
            margin-left: 15px;
            font-weight: 600;
        }

        .time-sub-text {
            color: var(--verde1);
        }

        .day-text {
            font-size: 18px;
            margin-left: 15px;
            opacity: .8;
        }

        .moon {
            position: absolute;
            right: 15px;
            top: 15px;
            color: var(--verde1);
            font-size: 20px;
        }

        /* Tabla */
        .table-container {
            width: 90%;
            max-width: 1100px;
            margin: 60px auto;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0, 0, 0, .5);
            border: 1px solid rgba(154, 255, 77, .5);
            background: rgba(255, 255, 255, .97);
            backdrop-filter: blur(10px);
        }

        .styled-table {
            width: 100%;
            border-collapse: collapse;
            color: #111;
        }

        .styled-table thead {
            background: linear-gradient(135deg, #005c26, #003314);
            color: white;
        }

        .styled-table th,
        .styled-table td {
            padding: 20px;
            text-align: left;
        }

        .styled-table tbody tr:hover {
            background: rgba(154, 255, 77, .35);
        }

        @media (max-width:768px) {
            .tituloGestionCultivos {
                font-size: 48px;
            }

            .button {
                display: block;
                width: 90%;
                max-width: 340px;
                margin: 15px auto;
            }

            .card {
                width: 240px;
                height: 130px;
                top: 10px;
                right: 10px;
            }

            .time-text {
                font-size: 40px;
            }
        }
    </style>
</head>

<body>

    <div class="card">
        <p class="time-text">
            <span id="hours">00</span>:<span id="minutes">00</span>
        </p>
        <p class="day-text" id="date"></p>
        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 16 16" class="moon">
            <path
                d="M6 .278a.768.768 0 0 1 .08.858 7.208 7.208 0 0 0-.878 3.46c0 4.021 3.278 7.277 7.318 7.277.527 0 1.04-.055 1.533-.16a.787.787 0 0 1 .81.316.733.733 0 0 1-.031.893A8.349 8.349 0 0 1 8.344 16C3.734 16 0 12.286 0 7.71 0 4.266 2.114 1.312 5.124.06A.752.752 0 0 1 6 .278z" />
            <path
                d="M10.794 3.148a.217.217 0 0 1 .412 0l.387 1.162c.173.518.579.924 1.097 1.097l1.162.387a.217.217 0 0 1 0 .412l-1.162.387a1.734 1.734 0 0 0-1.097 1.097l-.387 1.162a.217.217 0 0 1-.412 0l-.387-1.162A1.734 1.734 0 0 0 9.31 6.593l-1.162-.387a.217.217 0 0 1 0-.412l1.162-.387a1.734 1.734 0 0 0 1.097-1.097l.387-1.162zM13.863.099a.145.145 0 0 1 .274 0l.258.774c.115.346.386.617.732.732l.774.258a.145.145 0 0 1 0 .274l-.774.258a1.156 1.156 0 0 0-.732.732l-.258.774a.145.145 0 0 1-.274 0l-.258-.774a1.156 1.156 0 0 0-.732-.732l-.774-.258a.145.145 0 0 1 0-.274l.774-.258c.346-.115.617-.386.732-.732L13.863.1z" />
        </svg>
    </div>

    <div class="mensajeExito" id="conexionMsg">¡Conexión exitosa!</div>

    <div class="content">
        <form method="POST">
            <div class="bloqueBotones">
                <h1 class="tituloGestionCultivos">Gestión de Cultivos</h1>

                <button class="button" type="submit" name="listarSentenciaSQL">Listar Todos los Cultivos</button>
                <button class="button" type="button" onclick="location.reload()">Recargar Página</button>
                <button class="button" type="button" onclick="location.href='nuevo.php'">Insertar Nuevo Cultivo</button>

                <div class="container">
                    <div onclick="llevarAGitHub()" data-text="Github" style="--r:-15;" class="glass">
                        <svg viewBox="0 0 496 512" height="1em" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M165.9 397.4c0 2-2.3 3.6-5.2 3.6-3.3.3-5.6-1.3-5.6-3.6 0-2 2.3-3.6 5.2-3.6 3-.3 5.6 1.3 5.6 3.6zm-31.1-4.5c-.7 2 1.3 4.3 4.3 4.9 2.6 1 5.6 0 6.2-2s-1.3-4.3-4.3-5.2c-2.6-.7-5.5.3-6.2 2.3zm44.2-1.7c-2.9.7-4.9 2.6-4.6 4.9.3 2 2.9 3.3 5.9 2.6 2.9-.7 4.9-2.6 4.6-4.6-.3-1.9-3-3.2-5.9-2.9zM244.8 8C106.1 8 0 113.3 0 252c0 110.9 69.8 205.8 169.5 239.2 12.8 2.3 17.3-5.6 17.3-12.1 0-6.2-.3-40.4-.3-61.4 0 0-70 15-84.7-29.8 0 0-11.4-29.1-27.8-36.6 0 0-22.9-15.7 1.6-15.4 0 0 24.9 2 38.6 25.8 21.9 38.6 58.6 27.5 72.9 20.9 2.3-16 8.8-27.1 16-33.7-55.9-6.2-112.3-14.3-112.3-110.5 0-27.5 7.6-41.3 23.6-58.9-2.6-6.5-11.1-33.3 2.6-67.9 20.9-6.5 69 27 69 27 20-5.6 41.5-8.5 62.8-8.5s42.8 2.9 62.8 8.5c0 0 48.1-33.6 69-27 13.7 34.7 5.2 61.4 2.6 67.9 16 17.7 25.8 31.5 25.8 58.9 0 96.5-58.9 104.2-114.8 110.5 9.2 7.9 17 22.9 17 46.4 0 33.7-.3 75.4-.3 83.6 0 6.5 4.6 14.4 17.3 12.1C428.2 457.8 496 362.9 496 252 496 113.3 383.5 8 244.8 8zM97.2 352.9c-1.3 1-1 3.3.7 5.2 1.6 1.6 3.9 2.3 5.2 1 1.3-1 1-3.3-.7-5.2-1.6-1.6-3.9-2.3-5.2-1zm-10.8-8.1c-.7 1.3.3 2.9 2.3 3.9 1.6 1 3.6.7 4.3-.7.7-1.3-.3-2.9-2.3-3.9-2-.6-3.6-.3-4.3.7zm32.4 35.6c-1.6 1.3-1 4.3 1.3 6.2 2.3 2.3 5.2 2.6 6.5 1 1.3-1.3.7-4.3-1.3-6.2-2.2-2.3-5.2-2.6-6.5-1zm-11.4-14.7c-1.6 1-1.6 3.6 0 5.9 1.6 2.3 4.3 3.3 5.6 2.3 1.6-1.3 1.6-3.9 0-6.2-1.4-2.3-4-3.3-5.6-2z" />
                        </svg>
                    </div>
                    <div onclick="llamaFuncionCodigo()" data-text="Code" style="--r:5;" class="glass">
                        <svg viewBox="0 0 640 512" height="1em" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M392.8 1.2c-17-4.9-34.7 5-39.6 22l-128 448c-4.9 17 5 34.7 22 39.6s34.7-5 39.6-22l128-448c4.9-17-5-34.7-22-39.6zm80.6 120.1c-12.5 12.5-12.5 32.8 0 45.3L562.7 256l-89.4 89.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0l112-112c12.5-12.5 12.5-32.8 0-45.3l-112-112c-12.5-12.5-32.8-12.5-45.3 0zm-306.7 0c-12.5-12.5-32.8-12.5-45.3 0l-112 112c-12.5 12.5-12.5 32.8 0 45.3l112 112c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L77.3 256l89.4-89.4c12.5-12.5 12.5-32.8 0-45.3z" />
                        </svg>
                    </div>
                    <div onclick="llevarAlSoporte()" data-text="Earn" style="--r:25;" class="glass">
                        <svg viewBox="0 0 576 512" height="1em" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M64 64C28.7 64 0 92.7 0 128V384c0 35.3 28.7 64 64 64H512c35.3 0 64-28.7 64-64V128c0-35.3-28.7-64-64-64H64zm64 320H64V320c35.3 0 64 28.7 64 64zM64 192V128h64c0 35.3-28.7 64-64 64zM448 384c0-35.3 28.7-64 64-64v64H448zm64-192c-35.3 0-64-28.7-64-64h64v64zM288 160a96 96 0 1 1 0 192 96 96 0 1 1 0-192z" />
                        </svg>
                    </div>
                </div>
            </div>
        </form>

        <!-- ¡¡AQUÍ SE MUESTRA LA TABLA!! -->
        <?php if (!empty($tabla_html)): ?>
            <?= $tabla_html ?>
        <?php endif; ?>

    </div>

    <script>
        function llevarAlSoporte(){
            window.location.href = "soporte.php";
        }
        // Reloj en formato 24h
        function updateClock() {
            const now = new Date();
            let hours = now.getHours().toString().padStart(2, '0');
            let minutes = now.getMinutes().toString().padStart(2, '0');
            document.getElementById('hours').textContent = hours;
            document.getElementById('minutes').textContent = minutes;

            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            const fecha = now.toLocaleDateString('es-ES', options);
            document.getElementById('date').textContent = fecha.charAt(0).toUpperCase() + fecha.slice(1);
        }
        updateClock();
        setInterval(updateClock, 1000);

        // Mensaje de éxito
        window.addEventListener("load", () => {
            const msg = document.getElementById("conexionMsg");
            if (msg) {
                msg.style.opacity = "1";
                setTimeout(() => msg.style.opacity = "0", 2000);
            }
        });

        function llevarAGitHub() {
            window.location.href = "https://github.com/Andres-glitch-cell/huertaPhpLorena";
        }
        function llamaFuncionCodigo() {
            window.location.href = "code.php";
        }
    </script>
</body>

</html>