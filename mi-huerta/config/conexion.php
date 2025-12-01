<?php
// TODO: CAMBIAR METHOD DE LA CONEXIÓN
// ! COMENTARIOS
// ? Comentarios 2
function conectarBBDD()
{
    $server = getenv('DB_HOST');
    $user = getenv('DB_USERNAME');
    $pass = getenv('DB_PASSWORD');
    $db = getenv('DB_NAME');
    $conexion = mysqli_connect($server, $user, $pass, $db);

    if (!$conexion) {
        error_log("Error de conexión MySQL: " . mysqli_connect_error());
        return false;
    }
    return $conexion;
}
?>