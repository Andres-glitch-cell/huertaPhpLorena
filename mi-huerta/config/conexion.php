<?php
/**
 * CONEXIÓN MEDIANTE parse_ini_file (LA FORMA CORTA Y NATIVA)
 */
function conectarBDD()
{
    // 1. Leemos el archivo .env y lo convertimos en un array ($config)
    // El '../' es porque conexion.php está en /config y el .env en la raíz
    $config = parse_ini_file(__DIR__ . '/../.env');

    if (!$config) {
        error_log("Error: No se pudo leer el archivo .env");
        return null;
    }

    // 2. Conectamos usando las claves del array
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

    // Configuración de caracteres
    mysqli_set_charset($conexion, "utf8mb4");

    return $conexion;
}
?>