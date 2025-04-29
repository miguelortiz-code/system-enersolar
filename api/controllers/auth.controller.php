<?php
require_once 'models/auth.model.php';
require_once 'helpers/response.php';
require_once 'helpers/jwt.helper.php';
require_once 'middleware/auth.middleware.php';

class AuthController
{
    private $model;

    public function __construct()
    {
        $this->model = new AuthModel();
    }

    // Función para generar un código de usuario único
    public function generateCode()
    {
        do {
            $code =  random_int(100, 999); // Genera un código entre 100 y 999
        } while ($this->model->isCodeUserExists($code)); // Asegura que el código sea único
        return $code;
    }

    // Función para validar el inicio de sesión
    public function validateLogin()
    {
        $data =  Response::json(); // Obtiene los datos JSON de la solicitud

        // Validar que los campos requeridos no estén vacíos
        if (empty($data['email']) || empty($data['password'])) {
            Response::error('El email y la contraseña son obligatorios', 400);
        }

        // Intentar obtener el usuario por su email
        $user = $this->model->login($data['email']);
        if(!$user){
            Response::error('El usuario no se encuentra registrado', 400);
        }

        // Verificar si la contraseña es correcta
        if ($user && password_verify($data['password'], $user['password'])) {
            // Preparar el payload para el JWT
            $payload = [
                'id_user' => $user['id_user'],
                'email' => $user['email'],
                'first_name' => $user['first_name'],
                'id_rol' => $user['id_rol']
            ];

            // Generar el token JWT
            $token = JwtHelper::generateToken($payload);

            // Verificar el token para obtener su fecha de expiración
            $decoded = JwtHelper::verifyToken($token);
            $exp =  $decoded ? $decoded->exp : null;

            // Actualizar el token en la base de datos
            $this->model->updateToken($token, $exp, $user['id_user']);

            // Responder con éxito
            Response::success([
                'message' => 'Login éxitoso',
                'token' => $token,
                'user' => $payload
            ]);
        } else {
            Response::error('Usuario o contraseña incorrecta', 401);
        }
    }

    // Función para registrar un nuevo usuario
    public function register()
    {
        $data = Response::json(); // Obtiene los datos JSON de la solicitud

        // Campos requeridos
        $required = ['first_name', 'second_name', 'first_surname', 'second_surname', 'email', 'password', 'phone', 'address'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                Response::error("El campo $field es obligatorio", 400);
                return;
            }
        }

        // Limpiar y sanitizar los datos de entrada
        $clearData = [
            'code_user' => $this->generateCode(),
            'first_name'  =>  htmlspecialchars(trim(strip_tags($data['first_name']))),
            'second_name' =>  htmlspecialchars(trim(strip_tags($data['second_name']))),
            'first_surname' =>  htmlspecialchars(trim(strip_tags($data['first_surname']))),
            'second_surname' =>  htmlspecialchars(trim(strip_tags($data['second_surname']))),
            'email' =>  htmlspecialchars(trim(strip_tags($data['email']))),
            'password' =>  filter_var(trim(strip_tags($data['password'])), FILTER_SANITIZE_EMAIL),
            'phone' =>  preg_replace('/[^0-9]/', '', $data['phone']),
            'address' =>  htmlspecialchars(trim(strip_tags($data['address']))),
        ];

        // Validar el formato del correo
        if (!filter_var($clearData['email'], FILTER_VALIDATE_EMAIL)) {
            Response::error('El correo no tiene un formato valido', 400);
        }

        // Hashear la contraseña
        $clearData['password'] =  password_hash($data['password'], PASSWORD_BCRYPT);

        // Validar si el correo ya está registrado
        if ($this->model->existingEmail($clearData['email'])) {
            Response::error('El correo ya se encuentra registrado', 409);
        }

        // Registrar al usuario
        $result = $this->model->register($clearData);

        // Verificar si el registro fue exitoso
        if ($result) {
            Response::success(['message' => 'Usuario registrado con éxito']);
        } else {
            Response::error('No se pudo registrar el usuario', 500);
        }
    }

    // Función para obtener el perfil del usuario autenticado
    public function profile()
    {
        // Llamar al middleware para verificar el token
        $userData = AuthMiddleware::handle();

        // Responder con los datos del perfil
        Response::success([
            'Message' => 'Perfil de usuario obtenido',
            'user' => $userData
        ]);
    }
}