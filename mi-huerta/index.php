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
            --verde1: #73ffcc;
            --verde2: #4696ff;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #001a0d, #002b1a);
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
            background: linear-gradient(90deg, #00ff9d, #00d0ff);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            text-shadow: 0 0 20px rgba(0,255,150,0.6);
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
            background: linear-gradient(90deg, #73ffcc, #ffffff, #73ffcc);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            text-shadow: 0 4px 20px rgba(115,255,204,0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 28px;
        }

        .tituloGestionCultivos::before {
            content: "";
            width: 100px;
            height: 100px;
            background: url("handGreenPlant.png") center/contain no-repeat;
            flex-shrink: 0;
        }

        .bloqueBotones { text-align: center; margin-bottom: 80px; }

        .button {
            background: linear-gradient(135deg, var(--verde1), var(--verde2));
            color: white;
            border: none;
            border-radius: 16px;
            padding: 18px 40px;
            margin: 12px;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 10px 30px rgba(70,150,255,0.4);
            transition: all 0.3s ease;
        }

        .button:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(70,150,255,0.6);
        }

        .table-container {
            width: 90%;
            max-width: 1100px;
            margin: 60px auto;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0,0,0,0.5);
            border: 1px solid rgba(115,255,204,0.5);
            background: rgba(255,255,255,0.97);
            backdrop-filter: blur(10px);
        }

        .styled-table { width: 100%; border-collapse: collapse; color: #111; }
        .styled-table thead { background: linear-gradient(135deg, #003314, #001a0d); color: white; }
        .styled-table th, .styled-table td { padding: 20px; text-align: left; }
        .styled-table tbody tr:hover { background: rgba(115,255,204,0.25); }

        /* RELOJ DIGITAL PRECIOSO */
        .card {
            position: fixed;
            top: 20px;
            right: 20px;
            width: 280px;
            height: 150px;
            background: linear-gradient(to right, rgb(20, 30, 48), rgb(36, 59, 85));
            border-radius: 15px;
            box-shadow: rgb(0,0,0,0.7) 5px 10px 50px, rgb(0,0,0,0.7) -5px 0px 250px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            color: white;
            z-index: 9999;
            cursor: default;
            transition: all 0.3s ease-in-out;
            overflow: hidden;
        }

        .card:hover {
            box-shadow: rgb(0,0,0) 5px 10px 50px, rgb(0,0,0) -5px 0px 250px;
        }

        .time-text {
            font-size: 50px;
            margin-left: 15px;
            font-weight: 600;
            font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
        }

        .time-sub-text {
            font-size: 15px;
            margin-left: 5px;
        }

        .day-text {
            font-size: 18px;
            margin-left: 15px;
            font-weight: 500;
            font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
        }

        .moon {
            font-size: 20px;
            position: absolute;
            right: 15px;
            top: 15px;
            transition: all 0.3s ease-in-out;
        }

        .card:hover > .moon {
            font-size: 23px;
        }

        @media (max-width: 768px) {
            .tituloGestionCultivos { font-size: 48px; }
            .tituloGestionCultivos::before { width: 70px; height: 70px; }
            .button { display: block; width: 90%; max-width: 340px; margin: 15px auto; }
            .mensajeExito { font-size: 2.4rem; }
            .card { width: 240px; height: 130px; top: 10px; right: 10px; }
            .time-text { font-size: 40px; }
            .day-text { font-size: 16px; }
        }
    </style>
</head>
<body>

    <!-- Reloj digital precioso -->
    <div class="card">
        <p class="time-text"><span id="hours">00</span>:<span id="minutes">00</span> <span class="time-sub-text" id="ampm">AM</span></p>
        <p class="day-text" id="date"></p>
        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" stroke="currentColor" class="moon">
            <path d="M6 .278a.768.768 0 0 1 .08.858 7.208 7.208 0 0 0-.878 3.46c0 4.021 3.278 7.277 7.318 7.277.527 0 1.04-.055 1.533-.16a.787.787 0 0 1 .81.316.733.733 0 0 1-.031.893A8.349 8.349 0 0 1 8.344 16C3.734 16 0 12.286 0 7.71 0 4.266 2.114 1.312 5.124.06A.752.752 0 0 1 6 .278z"></path>
            <path d="M10.794 3.148a.217.217 0 0 1 .412 0l.387 1.162c.173.518.579.924 1.097 1.097l1.162.387a.217.217 0 0 1 0 .412l-1.162.387a1.734 1.734 0 0 0-1.097 1.097l-.387 1.162a.217.217 0 0 1-.412 0l-.387-1.162A1.734 1.734 0 0 0 9.31 6.593l-1.162-.387a.217.217 0 0 1 0-.412l1.162-.387a1.734 1.734 0 0 0 1.097-1.097l.387-1.162zM13.863.099a.145.145 0 0 1 .274 0l.258.774c.115.346.386.617.732.732l.774.258a.145.145 0 0 1 0 .274l-.774.258a1.156 1.156 0 0 0-.732.732l-.258.774a.145.145 0 0 1-.274 0l-.258-.774a1.156 1.156 0 0 0-.732-.732l-.774-.258a.145.145 0 0 1 0-.274l.774-.258c.346-.115.617-.386.732-.732L13.863.1z"></path>
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
            </div>
        </form>

        <?php
        require_once "config/conexion.php";
        conectarBBDD();

        if (isset($_POST['listarSentenciaSQL'])) {
            $conexion = conectarBBDD();
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
        // Reloj en tiempo real
        function updateClock() {
            const now = new Date();
            let hours = now.getHours();
            const minutes = now.getMinutes().toString().padStart(2, '0');
            const ampm = hours >= 12 ? 'PM' : 'AM';
            hours = hours % 12;
            hours = hours ? hours : 12; // 0 → 12
            hours = hours.toString().padStart(2, '0');

            document.getElementById('hours').textContent = hours;
            document.getElementById('minutes').textContent = minutes;
            document.getElementById('ampm').textContent = ampm;

            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            document.getElementById('date').textContent = now.toLocaleDateString('es-ES', options);
        }

        updateClock();
        setInterval(updateClock, 1000);

        // Mensaje de éxito
        window.addEventListener("load", () => {
            const msg = document.getElementById("conexionMsg");
            msg.style.opacity = "1";
            setTimeout(() => msg.style.opacity = "0", 4000);
        });
    </script>
</body>
</html>