<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtHelper
{
    private static $secretKey = 'g5$^S2#@1sdf09v@Jkl32kd!@df0932lksdf023'; // Se recomienda cargarlo de un archivo de configuración
    private static $encrypt = ['HS256'];
    private static $expiration = 3600;  // Tiempo de expiración en segundos

    // ============================================================
    //                 GENERAR TOKEN
    // ============================================================
    public static function generateToken(array $payload)
    {
        $issuedAt = time();
        $expire = $issuedAt + self::$expiration;

        $token = [
            'iat' => $issuedAt,
            'exp' => $expire,
            'data' => $payload
        ];

        try {
            // Generamos y retornamos el token
            return JWT::encode($token, self::$secretKey, self::$encrypt[0]);
        } catch (\Exception $e) {
            // Capturamos el error en la generación del token y retornamos null
            return null;
        }
    }

    // ============================================================
    //                 VALIDAR TOKEN
    // ============================================================
    public static function verifyToken($token)
    {
        if (!$token) {
            return [
                'success' => false,
                'error' => 'Token no proporcionado'
            ];
        }

        try {
            // Decodificamos el token y lo validamos con la clave secreta
            $decoded = JWT::decode($token, new Key(self::$secretKey, self::$encrypt[0]));


            // Si la validación es exitosa, retornamos los datos del token
            return [
                'success' => true,
                'data' => $decoded->data
            ];
        } catch (\Firebase\JWT\ExpiredException $e) {
            // El token ha expirado
            return [
                'success' => false,
                'error' => 'Token expirado'
            ];
        } catch (\Firebase\JWT\SignatureInvalidException $e) {
            // El token tiene una firma inválida
            return [
                'success' => false,
                'error' => 'Firma del token inválida'
            ];
        } catch (\Exception $e) {
            // Otro error general con el token
            return [
                'success' => false,
                'error' => 'Token inválido: ' . $e->getMessage()
            ];
        }
    }
}