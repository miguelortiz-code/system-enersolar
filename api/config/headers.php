<?php
// Permitir peticiones desde cualquier origen (desarrollo)
header('Access-Control-Allow-Origin: *');

// Indicar que las respuestas serán en formato JSON
header('Content-Type: application/json');

// Métodos permitidos
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');

// Headers personalizados permitidos (útil para tokens o headers personalizados)
header('Access-Control-Allow-Headers: Content-Type, Authorization');