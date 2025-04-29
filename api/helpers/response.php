<?php

class Response {
    public static function success($data = [], $code = 200) {
        http_response_code($code);
        echo json_encode([
            'status' => 'success',
            'data' => $data
        ]);
        exit;
    }

    public static function error($message = 'Error interno del servidor', $code = 500) {
        http_response_code($code);
        echo json_encode([
            'status' => 'error',
            'message' => $message
        ]);
        exit;
    }

    public static function json() {
        return json_decode(file_get_contents('php://input'), true);
    }
}