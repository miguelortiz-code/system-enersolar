<?php
require_once 'models/user.model.php';
require_once 'helpers/response.php';
require_once 'middleware/auth.middleware.php';

class UserController
{
    private $model;

    public function __construct()
    {
        $this->model = new userModel();
    }

    // ==========================================
    // OBTENER USUARIO POR ID
    // ==========================================
    public function getUserById($id)
    {

        // Asegurar que el usuario está autenticado
        AuthMiddleware::handle();

        $data = Response::json();

        if (!$id) {
            Response::error("ID de usuario no proporcionado", 400);
            return;
        }

        $user = $this->model->getUserById($id);
        if (!$user) {
            Response::error("No se ha encontrado ningun usuario con el id: {$id}", 404);
            return;
        }

        Response::success([
            'message' => 'Usuario encontrado',
            'data' => $user
        ]);
    }

    // ==========================================
    // CREAR UN USUARIO
    // ==========================================
    public function createUser()
    {

        AuthMiddleware::handle();
        $data = Response::json();

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
        $result = $this->model->createUser($clearData);

        // Verificar si el registro fue exitoso
        if ($result) {
            Response::success(['message' => 'Usuario registrado con éxito']);
        } else {
            Response::error('No se pudo registrar el usuario', 500);
        }
    }


    // ==========================================
    // ACTUALIZAR UN USUARIO
    // ==========================================
    public function updateUser($id)
    {
        // Asegurar que el usuario está autenticado
        AuthMiddleware::handle();

        $data = Response::json();

        if (!$id) {
            Response::error("ID de usuario no proporcionado", 400);
            return;
        }

        $user = $this->model->getUserById($id);
        if (!$user) {
            Response::error("No se ha encontrado ningun usuario con el id: {$id}", 404);
            return;
        }

        // Validar que se proporcionen los campos necesarios
        $required = ['first_name', 'second_name', 'first_surname', 'second_surname', 'email', 'password', 'phone', 'address'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                Response::error("El campo $field es obligatorio", 400);
                return;
            }
        }

        // Limpiar datos y asignar valores
        $clearData = [
            'code_user' => $data['code_user'],
            'first_name'  =>  htmlspecialchars(trim(strip_tags($data['first_name']))),
            'second_name' =>  htmlspecialchars(trim(strip_tags($data['second_name']))),
            'first_surname' =>  htmlspecialchars(trim(strip_tags($data['first_surname']))),
            'second_surname' =>  htmlspecialchars(trim(strip_tags($data['second_surname']))),
            'email' =>  htmlspecialchars(trim(strip_tags($data['email']))),
            'phone' =>  preg_replace('/[^0-9]/', '', $data['phone']),
            'address' =>  htmlspecialchars(trim(strip_tags($data['address']))),
        ];

        // Si la contraseña está presente, entonces la hasheamos
        if (!empty($data['password'])) {
            $clearData['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        } else {
            // Si no hay nueva contraseña, mantenemos la anterior
            $clearData['password'] = $user['password'];
        }

        // Actualizar usuario
        $result = $this->model->updateUser($id, $clearData);

        if ($result) {
            Response::success(['message' => 'Usuario actualizado exitosamente']);
        } else {
            Response::error('No se pudo actualizar el usuario', 500);
        }
    }


    // ==========================================
    // ELIMINAR UN USUARIO
    // ==========================================
    public function deleteUser($id)
    {
        // Asegurar que el usuario está autenticado
        AuthMiddleware::handle();

        if (!$id) {
            Response::error("ID de usuario no proporcionado", 400);
            return;
        }

        $user = $this->model->getUserById($id);
        if (!$user) {
            Response::error("No se ha encontrado ningun usuario con el id: {$id}", 404);
            return;
        }

        $result = $this->model->deleteUser($id);

        if ($result) {
            Response::success(['message' => 'Usuario eliminado exitosamente']);
        } else {
            Response::error('No se pudo eliminar el usuario', 500);
        }
    }

    // Obtener todos los usuarios con paginación
    public function getAllUsers($page = 1)
    {
        // Asegurar que el usuario está autenticado
        AuthMiddleware::handle();

        $limit = 10;  // Número de usuarios por página
        $offset = ($page - 1) * $limit;

        $users = $this->model->getAllUsers($limit, $offset);

        Response::success([
            'message' => 'Usuarios obtenidos exitosamente',
            'users' => $users
        ]);
    }

    // ==========================================
    // FILTRO DE USUARIOS
    // ==========================================
    public function getUsersByFilters()
    {
        // Asegurar que el usuario está autenticado
        AuthMiddleware::handle();


        $filters = $_GET;

        if (isset($_GET['email']) && trim($_GET['email']) !== '') {
            $filters['email'] = $_GET['email'];
        }

        if (isset($_GET['first_name']) && trim($_GET['first_name']) !== '') {
            $filters['first_name'] = $_GET['first_name'];
        }

        if (isset($_GET['id_rol']) && trim($_GET['id_rol']) !== '') {
            $filters['id_rol'] = $_GET['id_rol'];
        }

        if (isset($_GET['id_state']) && trim($_GET['id_state']) !== '') {
            $filters['id_state'] = $_GET['id_state'];
        }

        $users = $this->model->getUsersByFilters($filters);

        Response::success([
            'message' => 'Usuarios filtrados obtenidos',
            'users' => $users
        ]);
    }

    // ==========================================
    // GENERAR CODIGO PARA USUARIO
    // ==========================================
    private function generateCode()
    {
        do {
            $code =  random_int(100, 999); // Genera un código entre 100 y 999
        } while ($this->model->isCodeUserExists($code)); // Asegura que el código sea único
        return $code;
    }
}
