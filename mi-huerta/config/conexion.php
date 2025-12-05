<?php
// TODO: CAMBIAR METHOD DE LA CONEXIÓN
// ! COMENTARIOS
// ? Comentarios 2

function conectarBDD()
{
    // ! CREDENCIALES DE CONEXIÓN SIMPLES **
    // Se obtienen las variables de entorno cargadas previamente.
    $host = getenv('DB_HOST');
    $user = getenv('DB_USERNAME');
    $pass = getenv('DB_PASSWORD');
    $db_name = getenv('DB_NAME');

    // Muestra un error si falta el nombre de la BDD (para depuración)
    if (empty($db_name)) {
        error_log("Fallo de conexión a MySQL: DB_NAME no está definido en el archivo .env.");
        return null;
    }

    $conexion = mysqli_connect($host, $user, $pass, $db_name);

    if (!$conexion) {
        // Registra el error internamente y retorna nulo.
        error_log("Fallo de conexión a MySQL: " . mysqli_connect_error());
        return null;
    }
    return $conexion;
}
?>