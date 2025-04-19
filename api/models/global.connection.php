<?php

require_once 'config/connection.php';

/* ========================================================== 
                OBTENER CONEXION A LA BASE DE DATOS
    ==========================================================*/

function ConnectionDB()
{
    return Connection::getConnection();
}

$pdo = ConnectionDB();