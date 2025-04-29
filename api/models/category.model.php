<?php

require_once 'config/connection.php';

class CategoryModel
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Connection::getConnection();
    }

    public function insertCategory($data)
    {
        $query  = "INSERT INTO categories (category, slug) VALUES 
        (:category, :slug)";
        try {
            $stmt =  $this->pdo->prepare($query);
            $stmt->bindParam(':category', $data['category']);
            $stmt->bindParam(':slug', $data['slug']);
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception('Error al registrar la categoria ' . $e->getMessage());
        }
    }

    public function updateCategory($id, $data)
    {
        $query = "UPDATE categories SET category = :category, slug = :slug WHERE id_category = :id";
        try {
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':category', $data['category']);
            $stmt->bindParam(':slug', $data['slug']);
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception('Error al actualizar la categoria ' . $e->getMessage());
        }
    }

    public function deleteCategory($id)
    {
        $query = "DELETE FROM categories WHERE id_category = :id";
        try {
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':id', $id);
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception('Error al eliminar la categoria ' . $e->getMessage());
        }
    }

    public function getAllCategories()
    {
        $query = "SELECT id_category, category, slug, id_state FROM categories";
        try {
            $stmt = $this->pdo->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception('Error al obtener todas las categorías: ' . $e->getMessage());
        }
    }

    public function getCategoryById($id)
    {
        $query = "SELECT id_category, category, slug FROM categories WHERE id_category = :id";
        try {
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception('Error al obtener la categoría: ' . $e->getMessage());
        }
    }
}