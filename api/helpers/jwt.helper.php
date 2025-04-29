<?php

require 'vendor/autoload.php';
require 'helpers/env.helper.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;


class JwtHelper
{
    private static $secret;


    public static function init()
    {
        EnvHelper::load();
        self::$secret = getenv('JWT_SECRET');

        if (!self::$secret || !is_string(self::$secret)) {
            throw new Exception('JWT_SECRET NO DEFINIDO O INVALIDO');
        }
    }

    // GENERAR TOKEN JWT
    public static function generateToken($user)
    {
        self::init();

        $playload = [
            'iat' => time(),
            'exp' => time() + (60 * 60 * 24),
            'data' => [
                'id_user' => $user['id_user'],
                'first_name' => $user['first_name'],
                'email' => $user['email'],
                'id_rol' => $user['id_rol'],

            ]
        ];

        return JWT::encode($playload, self::$secret, 'HS256');
    }

    // VERIFICA EL TOKEN JWT

    public static function verifyToken($token)
    {
        self::init();
    
        try {
            return JWT::decode($token, new Key(self::$secret, 'HS256'));
        } catch (Exception $e) {
            return false;
        }
    }    
}