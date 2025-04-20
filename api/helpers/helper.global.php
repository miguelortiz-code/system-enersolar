<?php

// Función para decodificar JSON desde la entrada
function json(){
    return json_decode(file_get_contents('php://input'), true);
}

// Función para convertir un plural a singular (de manera básica)
function singularize($plural){
    // Reemplazar los guiones bajos por espacios
    $name = str_replace('_', ' ', $plural);

    // Eliminar el sufijo 'es' en plural (por ejemplo, 'productos' -> 'producto')
    if (preg_match('/es$/', $name)) {
        $name = preg_replace('/es$/', '', $name);
    }
    // Eliminar el sufijo 's' en plural (por ejemplo, 'usuarios' -> 'usuario')
    else if (preg_match('/s$/', $name)) {
        $name = preg_replace('/s$/', '', $name);
    }

    // Devolver la primera letra en mayúscula
    return ucfirst($name);
}

// Función para traducir nombres de tablas a español
function translateTableToSpanish($table)
{
    $traducciones = [
        'products' => 'Producto',
        'categories' => 'Categoría',
        'users' => 'Usuario',
        'orders' => 'Orden',
        'clients' => 'Cliente',
        'states' => 'Estado',
        'roles' => 'Rol',
        // Agrega más traducciones según lo necesites
    ];

    // Si no existe traducción, se devuelve el nombre original de la tabla
    return $traducciones[$table] ?? $table;
}