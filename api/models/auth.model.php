<?php

require_once 'config/connection.php';

class AuthModel {
    private $pdo;

    public function __construct()
    {
        $this->pdo = Connection::getConnection();
    }

    public function login($email){
        $query = "SELECT id_user, first_name, email, id_rol, password FROM users WHERE email = :email LIMIT 1";
        try {
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        } catch(PDOException $e) {
            throw new Exception('Error al consultar el usuario: ' . $e->getMessage());
        }
    }

    public function register($data){
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
        } catch(PDOException $e) {
            throw new Exception('Error al registrar el usuario: ' . $e->getMessage());
        }
    }

    public function existingEmail($email){
        $query = "SELECT 1 FROM users WHERE email = :email LIMIT 1";
        try {
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            return $stmt->fetchColumn() > 0;
        } catch(PDOException $e) {
            throw new Exception('Error al verificar el correo del usuario: ' . $e->getMessage());
        }
    }

    public function updateToken($token, $exp_token, $id){
        $query = "UPDATE users SET token = :token, exp_token = :exp_token WHERE id_user = :id";
        try {
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':token', $token);
            $stmt->bindParam(':exp_token', $exp_token);
            $stmt->bindParam(':id', $id);
            return $stmt->execute();
        } catch(PDOException $e) {
            throw new Exception('Error al actualizar el token del usuario: ' . $e->getMessage());
        }
    }

    public function isCodeUserExists($code){
        $query = "SELECT COUNT(code_user) FROM users WHERE code_user = :code";
        try {
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':code', $code);
            $stmt->execute();
            return $stmt->fetchColumn() > 0;
        } catch(PDOException $e) {
            throw new Exception('Error al verificar el código de usuario: ' . $e->getMessage());
        }
    }
}