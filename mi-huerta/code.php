<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visor de Código - Pro Edition</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/themes/prism-tomorrow.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/plugins/line-numbers/prism-line-numbers.min.css"
        rel="stylesheet" />
    <style>
        :root {
            --verde1: #9aff4d;
            --verde2: #00bfff;
        }

        body {
            background-color: #0f0f0f;
            margin: 0;
            padding: 40px 20px;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
        }

        .code-container {
            max-width: 95%;
            margin: 0 auto;
            background-color: #1e1e1e;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 30px 60px -12px rgba(0, 0, 0, 0.8);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }

        .window-header {
            background-color: #252526;
            padding: 14px 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .accent-line {
            height: 1px;
            background: linear-gradient(90deg, transparent, #27c93f, transparent);
            opacity: 0.3;
        }

        .dots {
            display: flex;
            gap: 8px;
            position: absolute;
            left: 20px;
        }

        .dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
        }

        .dot-red {
            background: #ff5f56;
        }

        .dot-yellow {
            background: #ffbd2e;
        }

        .dot-green {
            background: #27c93f;
        }

        .file-name {
            font-size: 13px;
            color: #969696;
            letter-spacing: 0.5px;
        }

        pre[class*="language-"] {
            margin: 0 !important;
            border-radius: 0 !important;
            height: 82vh;
            background: #1e1e1e !important;
        }

        code[class*="language-"] {
            font-family: "SF Mono", "Menlo", monospace !important;
            font-size: 15px;
            line-height: 1.8;
        }

        /* CONTENEDOR DEL BOTÓN INFERIOR */
        .footer-actions {
            display: flex;
            justify-content: center;
            padding: 40px 0;
            /* Margen profesional arriba y abajo */
        }

        .button {
            background: linear-gradient(135deg, var(--verde1), var(--verde2));
            color: #111;
            border: none;
            border-radius: 16px;
            padding: 18px 40px;
            font-size: 18px;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 12px 30px rgba(0, 191, 255, 0.3);
            transition: all 0.3s ease;
        }

        .button:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(154, 255, 77, 0.4);
        }

        /* Scrollbar macOS */
        pre::-webkit-scrollbar {
            width: 12px;
        }

        pre::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            border: 3px solid #1e1e1e;
        }
    </style>
</head>

<body>

    <div class="code-container">
        <div class="window-header">
            <div class="dots">
                <div class="dot dot-red"></div>
                <div class="dot dot-yellow"></div>
                <div class="dot dot-green"></div>
            </div>
            <div class="file-name">index.php — Visual Studio Code</div>
        </div>

        <div class="accent-line"></div>

        <pre class="language-php line-numbers"><code><?php
        $codigo_a_mostrar = <<<'CODE'
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Cultivos</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@500;600;700&family=Playfair+Display:wght=700&display=swap"
        rel="stylesheet">
    <style>
    /* COLORES DE HUERTA VIBRANTES */
    :root {
        --verde1: #9aff4d;
        /* Lima Brillante */
        --verde2: #00bfff;
        /* Azul Cielo Brillante */
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        min-height: 100vh;
        /* Fondo más saturado y profundo */
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
        /* Degradado más amarillo/verde intenso */
        background: linear-gradient(90deg, #d4ff00, #4dff9a);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
        /* Sombra más intensa y brillante */
        text-shadow: 0 0 25px rgba(212, 255, 0, 0.8);
        z-index: 9999;
        pointer-events: none;
        opacity: 0;
        transition: opacity 1s;
        text-align: center;
    }

    .tituloGestionCultivos {
        font-family: "Playfair Display", serif;
        font-size: 68px;
        font-weight: 900;
        text-align: center;
        margin: 40px 0 60px;

        /* Degradado lima intenso sin blanco que ensucie */
        background: linear-gradient(90deg, #9aff4d, #ccff80, #9aff4d);
        -webkit-background-clip: text;
        background-clip: text;
        -webkit-text-fill-color: transparent;
        color: transparent;

        /* QUITAMOS EL BLUR → sombra nítida y elegante */
        text-shadow: 0 4px 8px rgba(0, 0, 0, 0.4);
        /* opcional: si quieres un brillo sutil sin borrosidad */
        /* text-shadow: 0 4px 8px rgba(16, 25, 9, 0.6); */
    }

    .bloqueBotones {
        text-align: center;
        margin-bottom: 80px;
    }

    .button {
        /* Uso de las nuevas variables de color */
        background: linear-gradient(135deg, var(--verde1), var(--verde2));
        color: #111;
        /* Color de texto oscuro para mayor contraste */
        border: none;
        border-radius: 16px;
        padding: 18px 40px;
        margin: 12px;
        font-size: 18px;
        font-weight: 700;
        /* Más grueso */
        cursor: pointer;
        /* Sombra que usa los colores de acento */
        box-shadow: 0 12px 30px rgba(0, 191, 255, 0.5);
        transition: all 0.3s ease;
    }

    .button:hover {
        transform: translateY(-8px);
        box-shadow: 0 25px 45px rgba(154, 255, 77, 0.8);
    }

    /* TUS 3 BLOQUES DE CRISTAL */
    .container {
        position: relative;
        display: flex;
        justify-content: center;
        align-items: center;
        margin: 80px 0;
    }

    .container .glass {
        position: relative;
        width: 180px;
        height: 200px;
        background: linear-gradient(#fff2, transparent);
        border: 1px solid rgba(255, 255, 255, 0.1);
        box-shadow: 0 25px 25px rgba(0, 0, 0, 0.25);
        display: flex;
        justify-content: center;
        align-items: center;
        transition: 0.5s;
        border-radius: 10px;
        margin: 0 -45px;
        backdrop-filter: blur(10px);
        transform: rotate(calc(var(--r) * 1deg));
    }

    .container:hover .glass {
        transform: rotate(0deg);
        margin: 0 10px;
    }

    .container .glass::before {
        content: attr(data-text);
        position: absolute;
        bottom: 0;
        width: 100%;
        height: 40px;
        background: rgba(255, 255, 255, 0.05);
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

    /* RELOJ DIGITAL */
    .card {
        position: fixed;
        top: 20px;
        right: 20px;
        width: 280px;
        height: 150px;
        /* Fondo de la tarjeta ajustado al fondo del body */
        background: rgba(0, 51, 20, 0.6);
        /* Borde más claro para el efecto cristal */
        border: 1px solid rgba(154, 255, 77, 0.3);
        backdrop-filter: blur(10px);
        border-radius: 15px;
        /* Sombra que usa los colores de acento */
        box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.3), 0 0 20px rgba(154, 255, 77, 0.4);
        display: flex;
        flex-direction: column;
        justify-content: center;
        color: white;
        z-index: 9999;
        transition: all 0.3s ease-in-out;
        overflow: hidden;
    }

    .card:hover {
        /* Un poco más de brillo al pasar el ratón */
        box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.5), 0 0 30px rgba(154, 255, 77, 0.6);
    }

    .time-text {
        font-size: 50px;
        margin-left: 15px;
        font-weight: 600;
        font-family: 'Inter', sans-serif;
    }

    .time-sub-text {
        font-size: 15px;
        margin-left: 5px;
        /* Color de acento para el AM/PM */
        color: var(--verde1);
    }

    .day-text {
        font-size: 18px;
        margin-left: 15px;
        font-weight: 500;
        font-family: 'Inter', sans-serif;
        opacity: 0.8;
    }

    .moon {
        font-size: 20px;
        position: absolute;
        right: 15px;
        top: 15px;
        /* Color de acento para el icono */
        color: var(--verde1);
        transition: 0.3s;
    }

    .card:hover>.moon {
        font-size: 23px;
    }

    /* FIN RELOJ DIGITAL */

    .table-container {
        width: 90%;
        max-width: 1100px;
        margin: 60px auto;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
        /* Borde más vibrante */
        border: 1px solid rgba(154, 255, 77, 0.5);
        background: rgba(255, 255, 255, 0.97);
        backdrop-filter: blur(10px);
    }

    .styled-table {
        width: 100%;
        border-collapse: collapse;
        color: #111;
    }

    .styled-table thead {
        /* Encabezado con degradado verde profundo */
        background: linear-gradient(135deg, #005c26, #003314);
        color: white;
    }

    .styled-table th,
    .styled-table td {
        padding: 20px;
        text-align: left;
    }

    .styled-table tbody tr:hover {
        /* Hover más claro y lima */
        background: rgba(154, 255, 77, 0.35);
    }

    @media (max-width: 768px) {
        .tituloGestionCultivos {
            font-size: 48px;
        }

        .tituloGestionCultivos::before {
            width: 70px;
            height: 70px;
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

        .day-text {
            font-size: 16px;
        }

        .container .glass {
            width: 140px;
            height: 160px;
            margin: 0 -30px;
        }

        .container .glass svg {
            font-size: 2em;
        }
    }
    </style>
</head>

<body>

    <div class="card">
        <p class="time-text">
            <span id="hours">18</span>:<span id="minutes">56</span>
            <span class="time-sub-text" id="ampm"></span>
        </p>
        <p class="day-text" id="date">viernes, 5 de diciembre de 2025</p>
        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor"
            class="moon">
            <path
                d="M6 .278a.768.768 0 0 1 .08.858 7.208 7.208 0 0 0-.878 3.46c0 4.021 3.278 7.277 7.318 7.277.527 0 1.04-.055 1.533-.16a.787.787 0 0 1 .81.316.733.733 0 0 1-.031.893A8.349 8.349 0 0 1 8.344 16C3.734 16 0 12.286 0 7.71 0 4.266 2.114 1.312 5.124.06A.752.752 0 0 1 6 .278z">
            </path>
            <path
                d="M10.794 3.148a.217.217 0 0 1 .412 0l.387 1.162c.173.518.579.924 1.097 1.097l1.162.387a.217.217 0 0 1 0 .412l-1.162.387a1.734 1.734 0 0 0-1.097 1.097l-.387 1.162a.217.217 0 0 1-.412 0l-.387-1.162A1.734 1.734 0 0 0 9.31 6.593l-1.162-.387a.217.217 0 0 1 0-.412l1.162-.387a1.734 1.734 0 0 0 1.097-1.097l.387-1.162zM13.863.099a.145.145 0 0 1 .274 0l.258.774c.115.346.386.617.732.732l.774.258a.145.145 0 0 1 0 .274l-.774.258a1.156 1.156 0 0 0-.732.732l-.258.774a.145.145 0 0 1-.274 0l-.258-.774a1.156 1.156 0 0 0-.732-.732l-.774-.258a.145.145 0 0 1 0-.274l.774-.258c.346-.115.617-.386.732-.732L13.863.1z">
            </path>
        </svg>
    </div>
    <div class="mensajeExito" id="conexionMsg">¡Conexión exitosa!</div>
    <div class="content">
        <form method="POST">
            <div class="bloqueBotones">
                <h1 style="margin-top: 5%; font-size: 6em;" class="tituloGestionCultivos">Gestión de Cultivos</h1>
                <button class="button" type="submit" name="listarSentenciaSQL">Listar Todos los Cultivos</button>
                <button class="button" type="button" onclick="location.reload()">Recargar Página</button>
                <button class="button" type="button" onclick="location.href='nuevo.php'">Insertar Nuevo Cultivo</button>

                <div class="container">
                    <div data-text="Github" style="--r:-15;" class="glass">
                        <svg viewBox="0 0 496 512" height="1em" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M165.9 397.4c0 2-2.3 3.6-5.2 3.6-3.3.3-5.6-1.3-5.6-3.6 0-2 2.3-3.6 5.2-3.6 3-.3 5.6 1.3 5.6 3.6zm-31.1-4.5c-.7 2 1.3 4.3 4.3 4.9 2.6 1 5.6 0 6.2-2s-1.3-4.3-4.3-5.2c-2.6-.7-5.5.3-6.2 2.3zm44.2-1.7c-2.9.7-4.9 2.6-4.6 4.9.3 2 2.9 3.3 5.9 2.6 2.9-.7 4.9-2.6 4.6-4.6-.3-1.9-3-3.2-5.9-2.9zM244.8 8C106.1 8 0 113.3 0 252c0 110.9 69.8 205.8 169.5 239.2 12.8 2.3 17.3-5.6 17.3-12.1 0-6.2-.3-40.4-.3-61.4 0 0-70 15-84.7-29.8 0 0-11.4-29.1-27.8-36.6 0 0-22.9-15.7 1.6-15.4 0 0 24.9 2 38.6 25.8 21.9 38.6 58.6 27.5 72.9 20.9 2.3-16 8.8-27.1 16-33.7-55.9-6.2-112.3-14.3-112.3-110.5 0-27.5 7.6-41.3 23.6-58.9-2.6-6.5-11.1-33.3 2.6-67.9 20.9-6.5 69 27 69 27 20-5.6 41.5-8.5 62.8-8.5s42.8 2.9 62.8 8.5c0 0 48.1-33.6 69-27 13.7 34.7 5.2 61.4 2.6 67.9 16 17.7 25.8 31.5 25.8 58.9 0 96.5-58.9 104.2-114.8 110.5 9.2 7.9 17 22.9 17 46.4 0 33.7-.3 75.4-.3 83.6 0 6.5 4.6 14.4 17.3 12.1C428.2 457.8 496 362.9 496 252 496 113.3 383.5 8 244.8 8zM97.2 352.9c-1.3 1-1 3.3.7 5.2 1.6 1.6 3.9 2.3 5.2 1 1.3-1 1-3.3-.7-5.2-1.6-1.6-3.9-2.3-5.2-1zm-10.8-8.1c-.7 1.3.3 2.9 2.3 3.9 1.6 1 3.6.7 4.3-.7.7-1.3-.3-2.9-2.3-3.9-2-.6-3.6-.3-4.3.7zm32.4 35.6c-1.6 1.3-1 4.3 1.3 6.2 2.3 2.3 5.2 2.6 6.5 1 1.3-1.3.7-4.3-1.3-6.2-2.2-2.3-5.2-2.6-6.5-1zm-11.4-14.7c-1.6 1-1.6 3.6 0 5.9 1.6 2.3 4.3 3.3 5.6 2.3 1.6-1.3 1.6-3.9 0-6.2-1.4-2.3-4-3.3-5.6-2z">
                            </path>
                        </svg>
                    </div>
                    <div onclick="llamaFuncionCodigo()" data-text="Code" style="--r:5;" class="glass">
                        <svg viewBox="0 0 640 512" height="1em" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M392.8 1.2c-17-4.9-34.7 5-39.6 22l-128 448c-4.9 17 5 34.7 22 39.6s34.7-5 39.6-22l128-448c4.9-17-5-34.7-22-39.6zm80.6 120.1c-12.5 12.5-12.5 32.8 0 45.3L562.7 256l-89.4 89.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0l112-112c12.5-12.5 12.5-32.8 0-45.3l-112-112c-12.5-12.5-32.8-12.5-45.3 0zm-306.7 0c-12.5-12.5-32.8-12.5-45.3 0l-112 112c-12.5 12.5-12.5 32.8 0 45.3l112 112c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L77.3 256l89.4-89.4c12.5-12.5 12.5-32.8 0-45.3z">
                            </path>
                        </svg>
                    </div>
                    <div data-text="Earn" style="--r:25;" class="glass">
                        <svg viewBox="0 0 576 512" height="1em" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M64 64C28.7 64 0 92.7 0 128V384c0 35.3 28.7 64 64 64H512c35.3 0 64-28.7 64-64V128c0-35.3-28.7-64-64-64H64zm64 320H64V320c35.3 0 64 28.7 64 64zM64 192V128h64c0 35.3-28.7 64-64 64zM448 384c0-35.3 28.7-64 64-64v64H448zm64-192c-35.3 0-64-28.7-64-64h64v64zM288 160a96 96 0 1 1 0 192 96 96 0 1 1 0-192z">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>
        </form>

        <?php
        require_once "config/conexion.php";
        conectarBDD();
        if (isset($_POST['listarSentenciaSQL'])) {
            $conexion = conectarBDD();
            $sql = "SELECT * FROM cultivos";
            $resultado = mysqli_query($conexion, $sql);
            if (!$resultado) {
                echo '<p style="text-align:center;color:#e74c3c;font-weight:bold;">[ERROR] ' . mysqli_error($conexion) . '</p>';
            } elseif (mysqli_num_rows($resultado) > 0) {
                echo '<div class="table-container"><table class="styled-table">
                    <thead><tr><th>ID</th><th>Nombre</th><th>Tipo</th><th>Días hasta cosecha</th></tr></thead>
                    <tbody>';
                while ($fila = mysqli_fetch_assoc($resultado)) {
                    echo "<tr><td>{$fila['id']}</td><td><strong>" . htmlspecialchars($fila['nombre']) . "</strong></td><td>" . htmlspecialchars($fila['tipo']) . "</td><td>" . htmlspecialchars($fila['dias_cosecha']) . "</td></tr>";
                }
                echo '</tbody></table></div>';
            } else {
                echo '<p style="text-align:center;color:#95a5a6;font-size:1.8em;margin:80px 0;">No hay cultivos aún... ¡planta algo!</p>';
            }
            mysqli_close($conexion);
        }
        ?>
    </div>

    <script>
    document.addEventListener("DOMContentLoaded", function() {
        function updateClock() {
            const now = new Date();
            // Capturamos las horas en formato 24h (0-23)
            let hours = now.getHours();
            // Los minutos se quedan igual, asegurando dos dígitos
            const minutes = now.getMinutes().toString().padStart(2, '0');
            // Formateamos las horas a dos dígitos (ej. 09 en lugar de 9)
            hours = hours.toString().padStart(2, '0');
            // Eliminamos la lógica de AM/PM
            document.getElementById('hours').textContent = hours;
            document.getElementById('minutes').textContent = minutes;
            // Ocultamos o limpiamos el texto AM/PM
            document.getElementById('ampm').textContent = "";

            const options = {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            };
            const fecha = now.toLocaleDateString('es-ES', options);
            document.getElementById('date').textContent = fecha.charAt(0).toUpperCase() + fecha.slice(1);
        }

        updateClock();
        setInterval(updateClock, 1000);
    });

    // Mensaje de éxito
    window.addEventListener("load", () => {
        const msg = document.getElementById("conexionMsg");
        if (msg) {
            msg.style.opacity = "1";
            setTimeout(() => msg.style.opacity = "0", 4000);
        }
    });

    function llamaFuncionCodigo() {
        window.location.href = "code.php"
    }
    </script>
</body>

</html>
CODE;
        echo htmlspecialchars($codigo_a_mostrar);
        ?></code></pre>
    </div>

    <div class="footer-actions">
        <button class="button" type="button" onclick="location.href='index.php'">Volver al Índice</button>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/prism.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/plugins/line-numbers/prism-line-numbers.min.js">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-markup-templating.min.js">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-php.min.js"></script>
</body>

</html>