<?php
// TODO: CAMBIAR METHOD DE LA CONEXIÓN
// ! COMENTARIOS
// ? Comentarios 2
function conectarBBDD()
{
    $server = "localhost";
    $user = "administrador";
    $pass = "R00tR00t*12345";
    $db = "huerta_db";

    $conexion = mysqli_connect($server, $user, $pass, $db);

    if (!$conexion) {
        error_log("Error de conexión MySQL: " . mysqli_connect_error());
        return false;
    }
    return $conexion;
}
?>