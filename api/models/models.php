<?php

require_once  'config/connection.php';

class Model
{
    private $db;
    private $table;
    private $primaryKey;

    public function __construct($table)
    {
        $this->db = Connection::getConnection();
        $this->table = $table;
        $this->primaryKey = $this->getPrimaryKey();
    }

    public function getAll()
    {
        try {
            $stmt = $this->db->query("SELECT * FROM {$this->table}");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return [];
        }
    }

    public function getById($id)
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE {$this->primaryKey} = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ?: null;
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return null;
        }
    }

    public function insert($data)
    {
        try {
            $columns = implode(',', array_keys($data));
            $placeholders = implode(',', array_map(fn($col) => ":$col", array_keys($data)));
            $stmt = $this->db->prepare("INSERT INTO {$this->table} ($columns) VALUES ($placeholders)");
            foreach ($data as $key => $value) {
                $stmt->bindValue(":$key", $value);
            }
            $stmt->execute();
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function update($id, $data)
    {
        try {
            $set = implode(',', array_map(fn($col) => "$col = :$col", array_keys($data)));
            $stmt = $this->db->prepare("UPDATE {$this->table} SET $set WHERE {$this->primaryKey} = :id");
            foreach ($data as $key => $value) {
                $stmt->bindValue(":$key", $value);
            }
            $stmt->bindValue(":id", $id);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function delete($id)
    {
        try {
            $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE {$this->primaryKey} = :id");
            $stmt->bindParam(":id", $id);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    private function getPrimaryKey()
    {
        try {
            $stmt = $this->db->prepare("SHOW KEYS FROM {$this->table} WHERE Key_name = 'PRIMARY'");
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['Column_name'] ?? 'id';
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return 'id';
        }
    }

    public function Count(){
        try{
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM {$this->table}");
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return (int)$result['COUNT(*)'];
        }catch(PDOException $e){
            error_log($e->getMessage());
            return 0;
        }
    }

    public function getUserByEmail($email){
        try{    
            $stmt = $this->db->prepare("SELECT id_user, email, password, id_rol FROM {$this->table} WHERE email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);

        }catch(PDOException $e){
            error_log($e->getMessage());
            return false;
        }
    }


    public function updateTokenUser($id_user, $token){
        try{
            $stmt = $this->db->prepare("UPDATE {$this->table} SET token = :token WHERE {$this->primaryKey} = :id ");
            $stmt->bindParam(':token', $token);
            $stmt->bindParam(':id', $id_user);
            return $stmt->execute();
        }catch(PDOException $e){
            error_log($e->getMessage());
            return false;
        }
    }
}