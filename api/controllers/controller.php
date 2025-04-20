<?php

require_once 'models/models.php';
require_once 'helpers/helper.global.php';

class Controller
{
    private $model;
    private $table;
    private $method;

    public function __construct($table)
    {
        $this->model = new Model($table);
        $this->table = $table;
        $this->method = $_SERVER['REQUEST_METHOD'];
    }

    // ===================================================================
    //                   IMPRIMIR TODOS LOS REGISTROS
    // ===================================================================
    public function index()
    {
        $data =  $this->model->getAll();
        echo json_encode([
            'success' => true,
            'total' => is_array($data) ? count($data) : 0,
            'data' => $data,
        ]);
    }

    // ===================================================================
    //                   IMPRIMIR REGISTRO POR ID
    // ===================================================================
    public function show($id)
    {
        $item =  $this->model->getById($id);
        if ($item) {
            echo json_encode([
                'success' => true,
                'data' => $item
            ]);
        } else {
            http_response_code(404);
            echo json_encode([
                'error' => true,
                'message' => "El item no existe en la tabla {$this->table}",
                'id' => $id
            ]);
        }
    }

    // ===================================================================
    //                   CREAR REGISTRO
    // ===================================================================
    public function create($data)
    {
        if (!$this->validate($data)) return;
        $id = $this->model->insert($data);
        $message = $this->getCreateMessage();

        echo json_encode([
            'success' => true,
            'total' => $this->model->Count(),
            'id' => (int)$id,
            'message' => $message
        ]);
    }

    // ===================================================================
    //                   ACTUALIZAR REGISTRO
    // ===================================================================
    public function update($id, $data)
    {
        // Primero, verifiquemos si el registro existe antes de intentar actualizarlo
        if(!$this->model->getById($id)){
            http_response_code(404);
            echo json_encode([
                'Error' => true,
                'Message' => "No se encontró el registro con el id $id"
            ]);
            return;
        }

        // ENCRIPTAR LA CONTRASEÑA ANTES DE ACTUALIZAR

        if(isset($data['password'])){
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }


        // ACTUALIZAR EL REGISTRO
        if($this->model->update($id, $data)){
            $message = $this->getCreateMessage();
            echo json_encode([
                'success' => true,
                'total' => $this->model->Count(),
                'id' => (int)$id,
                'message' => $message
            ]);
        } else {
            http_response_code(400);
            echo json_encode([
                'Error' => true,
                'Message' => "No se pudo actualizar el registro con el id $id"
            ]);
        }
    }

    // ===================================================================
    //                   ELIMINAR REGISTRO
    // ===================================================================
    public function delete($id)
    {
        // Verificamos si el registro existe antes de intentar eliminarlo
        if(!$this->model->getById($id)){
            http_response_code(404);
            echo json_encode([
                'Error' => true,
                'Message' => "No se encontró el registro con el id $id",
            ]);
            return;
        }

        // Procedemos a eliminar el registro
        if($this->model->delete($id)){
            $message = $this->getCreateMessage();
            echo json_encode([
                'success' => true,
                'id' => (int)$id,
                'message' => $message,
                'total' => $this->model->Count(),
            ]);
        } else {
            http_response_code(400);
            echo json_encode([
                'Error' => true,
                'Message' => "No se pudo eliminar el registro con el id $id",
            ]);
        }
    }

    // ===================================================================
    //                   VALIDAR LOGIN DE USUARIOS
    // ===================================================================
    public function login($email, $password)
    {
        $userModel  = new Model('users');
        $user = $userModel->getUserByEmail($email);

        if (!$user) {
            echo json_encode([
                'status' => 400,
                'error' => true,
                'Message' => 'El correo no se encuentra registrado'
            ]);
            return;
        }

        if (password_verify($password, $user['password'])) {
            session_start();
            $_SESSION['user'] = $user;
            echo json_encode([
                'status' => 200,
                'success' => true,
                'Message' => 'Inicio de sesión exitoso'
            ]);
        } else {
            echo json_encode([
                'status' => 400,
                'error' => true,
                'Message' => 'Contraseña incorrecta'
            ]);
        }
    }

    // ===================================================================
    //                   VALIDAR  REGISTRO DE USUARIOS
    // ===================================================================

    public function register($data)
    {
        $userModel = new Model('users');
        $exitingUser = $userModel->getUserByEmail($data['email']);

        if ($exitingUser) {
            echo json_encode([
                'status' => 400,
                'error' => true,
                'Message' => 'El correo ya se encuentra registrado'
            ]);
            return;
        }

        // Encriptar la contraseña
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

        $data = $this->validate($data);

        $userId = $userModel->insert($data);


        if ($userId) {
            echo json_encode([
                'status' => 200,
                'success' => true,
                'Message' => "Se ha registrado con exito",
            ]);
        } else {
            echo json_encode([
                'status' => 400,
                'error' => true,
                'Message' => 'Error al registrar el usuario',
                'data' => $data
            ]);
        }
    }

    // ===================================================================
    //                   VALIDAR REGISTRO
    // ===================================================================
    public function validate($data)
    {
        if (empty($data) || !is_array($data)) {
            error_log(print_r($data, true));
            http_response_code(400);
            echo json_encode([
                'error' => true,
                'message' => 'Datos incorrectos',
                'data' => $data,
            ]);
            return false;
        }


        if($data['email'] && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)){
            http_response_code(400);
            echo json_encode([
                'error' => true,
                'message' => 'El correo ingresado no es valido',
                'data' => $data,
            ]);
            return false;
        }





        return true;
    }

    private function getCreateMessage()
    {
        $actions = [
            'POST' => 'creado',
            'PUT' => 'actualizado',
            'DELETE' => 'eliminado'
        ];

        $action = $actions[$this->method] ?? 'Procesado';
        $name = translateTableToSpanish($this->table);

        return "$name $action correctamente";
    }
}