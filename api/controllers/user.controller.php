<?php
require_once 'models/user.model.php';
require_once 'helpers/helper.global.php';

class userController
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new userModel();
    }

    // =====================================================
    //                   CREAR UN USUARIO
    // =====================================================
    public function createUser()
    {
        $data = json();

        // Lista de campos requeridos
        $requiredFields = [
            'code_user',
            'first_name',
            'second_name',
            'first_surname',
            'second_surname',
            'image',
            'address',
            'email',
            'password',
            'phone',
            'token',
            'method',
            'id_rol',
            'id_state',
            'super_root'
        ];

        foreach ($requiredFields as $field) {
            if (!array_key_exists($field, $data)) {
                echo json_encode([
                    "Status" => 400,
                    "Error" => true,
                    "Message" => "Campo requerido faltante: $field"
                ]);
                return;
            }
        }

        // Procesamiento de datos
        $codeUser = htmlspecialchars(trim($data['code_user']));
        $first_name = htmlspecialchars(trim($data['first_name']));
        $second_name = htmlspecialchars(trim($data['second_name']));
        $first_surname = htmlspecialchars(trim($data['first_surname']));
        $second_surname = htmlspecialchars(trim($data['second_surname']));
        $image = htmlspecialchars(trim($data['image']));
        $address = htmlspecialchars(trim($data['address']));
        $email = htmlspecialchars(trim($data['email']));
        $password = htmlspecialchars(trim($data['password']));
        $phone = htmlspecialchars(trim($data['phone']));
        $token = !empty($data['token']) ? htmlspecialchars(trim($data['token'])) : '';
        $method = !empty($data['method']) ? htmlspecialchars(trim($data['method'])) : '';
        $id_rol = htmlspecialchars(trim($data['id_rol']));
        $id_state = htmlspecialchars(trim($data['id_state']));
        $super_root = htmlspecialchars(trim($data['super_root']));

        // Validación del email
        if ($this->userModel->emailExists($email)) {
            echo json_encode([
                "Status" => 400,
                "Error" => true,
                "Message" => "El correo ya se encuentra registrado"
            ]);
            return;
        }

        // Validación de contraseña
        if (empty($password)) {
            echo json_encode([
                "Status" => 400,
                "Error" => true,
                "Message" => "La contraseña no puede estar vacía"
            ]);
            return;
        }
        $hashPassword = password_hash($password, PASSWORD_DEFAULT);

        // Datos del usuario
        $userData = [
            'code_user' => $codeUser,
            'first_name' => $first_name,
            'second_name' => $second_name,
            'first_surname' => $first_surname,
            'second_surname' => $second_surname,
            'image' => $image,
            'address' => $address,
            'email' => $email,
            'password' => $hashPassword,
            'phone' => $phone,
            'token' => $token,
            'method' => $method,
            'id_rol' => $id_rol,
            'id_state' => $id_state,
            'super_root' => $super_root
        ];

        // Insertar usuario
        $result = $this->userModel->insertUser($userData);

        if ($result) {
            echo json_encode([
                "Status" => 200,
                "Success" => true,
                "Message" => "Usuario registrado con éxito"
            ]);
        } else {
            echo json_encode([
                "Status" => 400,
                "Error" => true,
                "Message" => "Error al registrar el usuario"
            ]);
        }
    }
    // =====================================================
    //               OBTENER TODOS LOS USUARIOS
    // =====================================================
    public function index()
    {
        $users = $this->userModel->getAllUsers();
        echo json_encode([
            'Status' => 200,
            'Success' => true,
            'total' => count($users),
            'Data' => $users
        ]);
    }

    // =====================================================
    //               OBTENER USUARIO POR ID
    // =====================================================
    public function show($params)
    {
        $id = $params['id'];
        $user = $this->userModel->getUserById($id);


        if (!$user) {
            echo json_encode([
                'Status' => 404,
                'Error' => true,
                'Message' => 'Usuario no encontrado'
            ]);
            return;
        }

        echo json_encode([
            'Status' => 200,
            'Success' => true,
            'Data' => $user
        ]);
    }

    // =====================================================
    //               ACTUALIZAR USUARIO
    // =====================================================
    public function updateUser($params)
    {
        $id = $params['id'];
        $data = json(); // <- Aquí recuperamos los datos directamente

        $userData = [
            'code_user' => htmlspecialchars(trim($data['code_user'] ?? '')),
            'first_name' => htmlspecialchars(trim($data['first_name'] ?? '')),
            'second_name' => htmlspecialchars(trim($data['second_name'] ?? '')),
            'first_surname' => htmlspecialchars(trim($data['first_surname'] ?? '')),
            'second_surname' => htmlspecialchars(trim($data['second_surname'] ?? '')),
            'image' => htmlspecialchars(trim($data['image'] ?? '')),
            'address' => htmlspecialchars(trim($data['address'] ?? '')),
            'email' => htmlspecialchars(trim($data['email'] ?? '')),
            'password' => isset($data['password']) ? password_hash(trim($data['password']), PASSWORD_DEFAULT) : '',
            'phone' => htmlspecialchars(trim($data['phone'] ?? '')),
            'token' => htmlspecialchars(trim($data['token'] ?? '')),
            'method' => htmlspecialchars(trim($data['method'] ?? '')),
            'id_rol' => htmlspecialchars(trim($data['id_rol'] ?? '')),
            'id_state' => htmlspecialchars(trim($data['id_state'] ?? '')),
            'super_root' => htmlspecialchars(trim($data['super_root'] ?? ''))
        ];

        $result = $this->userModel->updateUser($id, $userData);

        if (!$result) {
            echo json_encode([
                'Status' => 400,
                'Error' => true,
                'Message' => 'No se pudo actualizar el usuario'
            ]);
            return;
        }

        echo json_encode([
            'Status' => 200,
            'Success' => true,
            'Message' => 'Usuario actualizado correctamente'
        ]);
    }


    // =====================================================
    //               ELIMINAR USUARIO
    // =====================================================
    public function deleteUser($params)
    {
        $id = $params['id'];

        $result = $this->userModel->deleteUser($id);

        if (!$result) {
            echo json_encode([
                'Status' => 400,
                'Error' => true,
                'Message' => 'No se pudo eliminar el usuario'
            ]);
            return;
        }

        echo json_encode([
            'Status' => 200,
            'Success' => true,
            'Message' => 'Usuario eliminado correctamente'
        ]);
    }

    // =====================================================
    //               BUSQUEDA CON FILTROS
    // =====================================================
    public function filterUsers()
    {
        $filters = $_GET;

        $users = $this->userModel->getUsersByFilters($filters);

        if ($users) {
            echo json_encode([
                'Status' => 200,
                'Success' => true,
                'total' => count($users),
                'Data' => $users
            ]);
        } else {
            echo json_encode([
                'Status' => 400,
                'error' => true,
                'total' => count($users),
                'Message' => 'No se han encontrado usuarios con esos parametros'
            ]);
        }
    }

    public function loginUser()
    {
        $data = json();
        if (!isset($data['email']) && !isset($data['password'])) {
            echo json_encode([
                'Status' => 400,
                'error' => true,
                'Message' => 'El correo y la contraseña son requeridos'
            ]);
            return;
        }

        $email =  htmlspecialchars(trim($data['email']));
        $password = htmlspecialchars(trim($data['password']));
        $user =  $this->userModel->getUserByEmail($email);

        if (!$user) {
            echo json_encode([
                'Status' => 400,
                'error' => true,
                'Message' => 'Usuario no se encuentra registrado'
            ]);
            return;
        }

        if (password_verify($password, $user['password'])) {
            echo json_encode([
                'Status' => 200,
                'success' => true,
                'Message' => 'Login exitoso',
                'Data' => [
                    'user_id' => $user['id_user'],
                    'email' => $user['email'],
                    'first_name' => $user['first_name']
                ]
            ]);
        } else {
            echo json_encode([
                'Status' => 400,
                'error' => true,
                'Message' => 'La contraseña es incorrecta'
            ]);
        }
    }
}
