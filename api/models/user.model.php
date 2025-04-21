<?php

require_once 'config/connection.php';

class userModel
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Connection::getConnection();
    }

    // =====================================================
    //                   CREAR UN USUARIO
    // =====================================================
    public function insertUser($data)
    {
        try {
            $query = "INSERT INTO users (
                code_user, first_name, second_name, first_surname, second_surname,
                image, address, email, password, phone, token, method,
                id_rol, id_state, super_root
            ) VALUES (
                :code, :first_name, :second_name, :first_surname, :second_surname,
                :image, :address, :email, :password, :phone, :token, :method,
                :id_rol, :id_state, :super_root
            )";
            $stmt = $this->pdo->prepare($query);

            $stmt->bindParam(':code', $data['code_user']);
            $stmt->bindParam(':first_name', $data['first_name']);
            $stmt->bindParam(':second_name', $data['second_name']);
            $stmt->bindParam(':first_surname', $data['first_surname']);
            $stmt->bindParam(':second_surname', $data['second_surname']);
            $stmt->bindParam(':image', $data['image']);
            $stmt->bindParam(':address', $data['address']);
            $stmt->bindParam(':email', $data['email']);
            $stmt->bindParam(':password', $data['password']);
            $stmt->bindParam(':phone', $data['phone']);
            $stmt->bindParam(':token', $data['token']);
            $stmt->bindParam(':method', $data['method']);
            $stmt->bindParam(':id_rol', $data['id_rol']);
            $stmt->bindParam(':id_state', $data['id_state']);
            $stmt->bindParam(':super_root', $data['super_root']);

            return $stmt->execute();
        } catch (PDOException $e) {
            // Log or handle error
            return false;
        }
    }

    // =====================================================
    //                   OBTENER TODOS LOS USUARIOS
    // =====================================================
    public function getAllUsers()
    {
        try {
            $query = "SELECT * FROM users";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    // =====================================================
    //                   OBTENER USUARIO POR ID
    // =====================================================
    public function getUserById($id)
    {
        try {
            $query = "SELECT * FROM users WHERE id_user = :id";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return null;
        }
    }

    // =====================================================
    //                   ACTUALIZAR USUARIO
    // =====================================================
    public function updateUser($id, $data)
    {
        try {
            $query = "UPDATE users SET
                code_user = :code, first_name = :first_name, second_name = :second_name,
                first_surname = :first_surname, second_surname = :second_surname,
                image = :image, address = :address, email = :email, password = :password,
                phone = :phone, token = :token, method = :method, id_rol = :id_rol,
                id_state = :id_state, super_root = :super_root
                WHERE id_user = :id";

            $stmt = $this->pdo->prepare($query);

            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':code', $data['code_user']);
            $stmt->bindParam(':first_name', $data['first_name']);
            $stmt->bindParam(':second_name', $data['second_name']);
            $stmt->bindParam(':first_surname', $data['first_surname']);
            $stmt->bindParam(':second_surname', $data['second_surname']);
            $stmt->bindParam(':image', $data['image']);
            $stmt->bindParam(':address', $data['address']);
            $stmt->bindParam(':email', $data['email']);
            $stmt->bindParam(':password', $data['password']);
            $stmt->bindParam(':phone', $data['phone']);
            $stmt->bindParam(':token', $data['token']);
            $stmt->bindParam(':method', $data['method']);
            $stmt->bindParam(':id_rol', $data['id_rol']);
            $stmt->bindParam(':id_state', $data['id_state']);
            $stmt->bindParam(':super_root', $data['super_root']);

            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    // =====================================================
    //                   ELIMINAR USUARIO
    // =====================================================
    public function deleteUser($id)
    {
        try {
            $query = "DELETE FROM users WHERE id_user = :id";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':id', $id);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    // =====================================================
    //                   VERIFICAR EMAIL
    // =====================================================
    public function emailExists($email)
    {
        try {
            $query = "SELECT COUNT(*) FROM users WHERE email = :email";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            return false;
        }
    }

    // =====================================================
    //                  BUSQUEDA CON FILTROS
    // =====================================================
    public function getUsersByFilters($filters)
    {
        $sql = "SELECT * FROM users WHERE 1=1";
        $params = [];

        if (!empty($filters['first_name'])) {
            $sql .= " AND first_name LIKE ?";
            $params[] = "%" . $filters['first_name'] . "%";
        }

        if (!empty($filters['second_name'])) {
            $sql .= " AND second_name LIKE ?";
            $params[] = "%" . $filters['second_name'] . "%";
        }

        if (!empty($filters['first_surname'])) {
            $sql .= " AND first_surname LIKE ?";
            $params[] = "%" . $filters['first_surname'] . "%";
        }

        if (!empty($filters['second_surname'])) {
            $sql .= " AND second_surname LIKE ?";
            $params[] = "%" . $filters['second_surname'] . "%";
        }

        if (!empty($filters['email'])) {
            $sql .= " AND email LIKE ?";
            $params[] = "%" . $filters['email'] . "%";
        }

        if (!empty($filters['id_rol'])) {
            $sql .= " AND id_rol = ?";
            $params[] = $filters['id_rol'];
        }

        if (!empty($filters['id_state'])) {
            $sql .= " AND id_state = ?";
            $params[] = $filters['id_state'];
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // =====================================================
    //                  OBTENER USUARIO POR EMAIL
    // =====================================================
    public function getUserByEmail($email){    
    try{
        $query = "SELECT id_user, first_name, email, password, token FROM users WHERE email = :email";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }catch (PDOException $e) {
        return false;
    }
}
}