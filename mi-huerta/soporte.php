<?php
/**
 * FASE 4: Manejo seguro de errores[cite: 72, 73].
 * Desactivamos la visualización de errores técnicos al usuario.
 */
ini_set('display_errors', 0);
error_reporting(E_ALL);

/**
 * FASE 5: Sanitización de salida.
 * Definimos los datos del creador escapándolos para evitar XSS[cite: 146].
 */
$nombre_creador = htmlspecialchars("Andrés", ENT_QUOTES, 'UTF-8');
$github_url = "https://github.com/Andres-glitch-cell";
// AQUÍ PUEDES PONER TU IMAGEN: puede ser una ruta local o un enlace de GitHub
$avatar_url = "image.png";
$mensaje_bonito = "Gracias por explorar este proyecto. No olvides apoyar al creador para seguir cultivando código de calidad.";
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Soporte - Creador de Mi Huerta</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&family=Playfair+Display:wght@700&display=swap"
        rel="stylesheet">
    <style>
        :root {
            --gh-bg: #0d1117;
            --gh-card: #161b22;
            --gh-border: #30363d;
            --gh-text: #c9d1d9;
            --gh-blue: #58a6ff;
            --verde-neon: #9aff4d;
        }

        body {
            background-color: var(--gh-bg);
            color: var(--gh-text);
            font-family: 'Inter', sans-serif;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .gh-card {
            background-color: var(--gh-card);
            border: 1px solid var(--gh-border);
            border-radius: 12px;
            padding: 40px;
            max-width: 500px;
            width: 90%;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
        }

        /* AJUSTE PARA MOSTRAR IMAGEN EN EL AVATAR */
        .avatar {
            width: 120px;
            height: 120px;
            border-radius: 30%;
            border: 3px solid var(--verde-neon);
            margin-bottom: 10px;
            display: inline-block;
            background-image: url('<?php echo $avatar_url; ?>');
            background-size: cover;
            background-position: center;
            margin-left: 10px;
            box-shadow: 0 0 15px rgba(154, 255, 77, 0.2);
        }

        h1 {
            font-size: 1.8rem;
            margin: 10px 0;
            color: #fff;
        }

        .handle {
            color: var(--gh-blue);
            font-size: 1.1rem;
            text-decoration: none;
            display: block;
            margin-bottom: 20px;
        }

        .quote {
            border-left: 4px solid var(--verde-neon);
            background: rgba(154, 255, 77, 0.05);
            padding: 15px;
            font-style: italic;
            border-radius: 4px;
            margin: 20px 0;
        }

        .btn-github {
            background-color: #238636;
            color: white;
            padding: 12px 24px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            display: inline-block;
            transition: 0.2s;
        }

        .btn-github:hover {
            background-color: #2ea043;
            transform: scale(1.05);
        }

        .footer-link {
            margin-top: 30px;
            display: block;
            color: var(--gh-text);
            opacity: 0.7;
            font-size: 0.9rem;
            text-decoration: none;
        }
    </style>
</head>

<body>

    <div class="gh-card">
        <div class="avatar"></div>

        <h1><?php echo $nombre_creador; ?></h1>
        <a href="<?php echo $github_url; ?>" class="handle" target="_blank">@Andres-glitch-cell</a>

        <div style="margin: 20px 0; line-height: 1.6;">
            <?php echo htmlspecialchars($mensaje_bonito, ENT_QUOTES, 'UTF-8'); ?>
        </div>

        <div class="quote">
            "No olvides apoyar al creador"
        </div>

        <a href="<?php echo $github_url; ?>" class="btn-github" target="_blank">Visitar GitHub Oficial</a>

        <a href="index.php" class="footer-link">← Volver al Sistema de Huerta</a>
    </div>

</body>

</html>