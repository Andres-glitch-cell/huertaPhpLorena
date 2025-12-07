<?php
// logic/cultivos.php
function cicloCultivo(int $dias): string
{
    if ($dias < 20)
        return "Corto";
    if ($dias < 50)
        return "Medio";
    return "Tardío";
}