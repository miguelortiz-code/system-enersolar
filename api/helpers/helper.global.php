<?php

// Función para decodificar JSON desde la entrada
function json(){
    return json_decode(file_get_contents('php://input'), true);
}