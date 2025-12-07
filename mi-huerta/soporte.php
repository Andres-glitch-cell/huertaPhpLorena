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
    <link rel="stylesheet" href="style-soporte.css">
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