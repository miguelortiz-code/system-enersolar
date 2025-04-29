<?php

require_once 'config/connection.php';

class UserModel
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Connection::getConnection();
    }

    public function createUser($data)
    {
        $query = "INSERT INTO users (code_user, first_name, second_name, first_surname, second_surname, email, password, phone, address)
                  VALUES (:code, :first_name, :second_name, :first_surname, :second_surname, :email, :password, :phone, :address)";
        try {
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':code', $data['code_user']);
            $stmt->bindParam(':first_name', $data['first_name']);
            $stmt->bindParam(':second_name', $data['second_name']);
            $stmt->bindParam(':first_surname', $data['first_surname']);
            $stmt->bindParam(':second_surname', $data['second_surname']);
            $stmt->bindParam(':email', $data['email']);
            $stmt->bindParam(':password', $data['password']);
            $stmt->bindParam(':phone', $data['phone']);
            $stmt->bindParam(':address', $data['address']);
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception('Error al registrar el usuario: ' . $e->getMessage());
        }
    }

    public function updateUser($id, $data)
    {
        $query = "UPDATE users SET  first_name = :first_name, second_name = :second_name, first_surname = :first_surname, 
        second_surname = :second_surname, image = :image, address = :address, email = :email, password = :password, phone = :phone, 
        id_rol = :id_rol, id_state = :id_state  WHERE id_user = :id";
        try {
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':first_name', $data['first_name']);
            $stmt->bindParam(':second_name', $data['second_name']);
            $stmt->bindParam(':first_surname', $data['first_surname']);
            $stmt->bindParam(':second_surname', $data['second_surname']);
            $stmt->bindParam(':image', $data['image']);
            $stmt->bindParam(':address', $data['address']);
            $stmt->bindParam(':email', $data['email']);
            $stmt->bindParam(':password', $data['password']);
            $stmt->bindParam(':phone', $data['phone']);
            $stmt->bindParam(':id_rol', $data['id_rol']);
            $stmt->bindParam(':id_state', $data['id_state']);
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception('Error al actualizar el usuario: ' . $e->getMessage());
        }
    }

    public function deleteUser($id)
    {
        $query = "DELETE FROM users WHERE id_user = :id";
        try {
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':id', $id);
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception('Error al eliminar el usuario: ' . $e->getMessage());
        }
    }

    public function getAllUsers($limit = 10, $offset = 0)
    {
        $query = "SELECT * FROM users LIMIT :limit OFFSET :offset";
        try {
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception('Error al obtener los usuarios: ' . $e->getMessage());
        }
    }

    public function getUserById($id)
    {
        $query = "SELECT code_user, first_name, second_name, first_surname, second_surname, email, phone, address FROM users WHERE id_user = :id";
        try {
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception('Error al obtener el usuario por ID: ' . $e->getMessage());
        }
    }

    public function getUsersByFilters($filters = [])
    {
        $query = "SELECT * FROM users WHERE 1=1";
        $params = [];

        if (isset($filters['email']) && trim($filters['email']) !== '') {
            $query .= " AND LOWER(email) LIKE :email";
            $params[':email'] = "%" . strtolower($filters['email']) . "%";
        }

        if (isset($filters['first_name']) && trim($filters['first_name']) !== '') {
            $query .= " AND LOWER(first_name) LIKE :first_name";
            $params[':first_name'] = "%" . strtolower($filters['first_name']) . "%";
        }

        if (isset($filters['second_name']) && trim($filters['second_name']) !== '') {
            $query .= " AND LOWER(second_name) LIKE :second_name";
            $params[':second_name'] = "%" . strtolower($filters['second_name']) . "%";
        }

        if (isset($filters['id_rol']) && trim($filters['id_rol']) !== '') {
            $query .= " AND id_rol = :id_rol";
            $params[':id_rol'] = $filters['id_rol'];
        }

        if (isset($filters['id_state']) && trim($filters['id_state']) !== '') {
            $query .= " AND id_state = :id_state";
            $params[':id_state'] = $filters['id_state'];
        }

        try {
            $stmt = $this->pdo->prepare($query);
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception('Error al filtrar usuarios: ' . $e->getMessage());
        }
    }

    public function isCodeUserExists($code)
    {
        $query = "SELECT COUNT(code_user) FROM users WHERE code_user = :code";
        try {
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':code', $code);
            $stmt->execute();
            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            throw new Exception('Error al verificar el código de usuario: ' . $e->getMessage());
        }
    }


    public function existingEmail($email)
    {
        $query = "SELECT 1 FROM users WHERE email = :email LIMIT 1";
        try {
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            throw new Exception('Error al verificar el correo del usuario: ' . $e->getMessage());
        }
    }
}
