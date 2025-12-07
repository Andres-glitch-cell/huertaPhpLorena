// * Comentario estándar/resaltado (Verde)
// ! Advertencia o nota crítica (Rojo intenso, Negrita)
// ? Pregunta o duda sobre el código (Azul, Cursiva)
// TODO: Tarea pendiente o algo por completar (Naranja/Ámbar, Negrita, Subrayado)
// // Comentario obsoleto o tachado (Gris oscuro, Tachado)
// & Nota de seguimiento o especial (Morado)

// --- Nuevos Comentarios ---
// @ ¡IMPORTANTE! Revisar o acción crucial (Amarillo, Fondo Semitransparente, Negrita)
// # Referencia a un ticket, enlace o doc. (Gris claro, Fondo Sólido Oscuro)
// + Código recién añadido o nueva funcionalidad (Verde claro, Negrita, Cursiva)

<?php
function conectarBDD()
{
    // ! 1. Leemos el archivo .env y lo convertimos en un array ($config)
    // El '../' es porque conexion.php está en /config y el .env en la raíz
    $config = parse_ini_file(__DIR__ . '/../.env');

    if (!$config) {
        error_log("Error: No se pudo leer el archivo .env");
        return null;
    }

    // ! 2. Conectamos usando las claves del array
    $conexion = mysqli_connect(
        $config['DB_HOST'],
        $config['DB_USERNAME'],
        $config['DB_PASSWORD'],
        $config['DB_NAME']
    );

    if (!$conexion) {
        error_log("Fallo de conexión a MySQL: " . mysqli_connect_error());
        return null;
    }

    // ! 3. Configuración de caracteres
    mysqli_set_charset($conexion, "utf8mb4");

    return $conexion;
}
?>