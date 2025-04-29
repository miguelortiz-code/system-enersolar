<?php

require_once 'helpers/jwt.helper.php';
require_once 'helpers/response.php';


class AuthMiddleware{
    public static function handle(){
        // OBTENER HEADERS
        $headers = getallheaders();
        $authHeader = $headers['Authorization'] ?? null;

        // VALIDAR EXISTENCIA DEL TOKEN
        if(!$authHeader || !str_starts_with($authHeader, 'Bearer ')){
            Response::error('Token no proporcionado', 401);
            exit;
        }

        $token =  trim(str_replace('Bearer', '', $authHeader));

        // VERIFICAR EL TOKEN
        $decoded = JwtHelper::verifyToken($token);

        if(!$decoded){
            Response::error('Token invalido o expirado' , 401);
            exit;
        }

        // SI TODO SALE BIEN DEVUELVE LOS DATOS DEL USUARIO
        return $decoded->data;
    }
}