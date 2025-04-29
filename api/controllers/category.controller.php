<?php

require_once 'models/category.model.php';
require_once 'helpers/response.php';
require_once 'middleware/auth.middleware.php';


class CategoryController
{
    private $model;

    public function __construct()
    {
        $this->model = new CategoryModel();
    }

    public function getAllCategories()
    {
        AuthMiddleware::handle();

        try {
            $categories = $this->model->getAllCategories();
            Response::success([
                'message' => 'Categorías obtenidas correctamente',
                'categories' => $categories
            ]);
        } catch (Exception $e) {
            Response::error('Error al obtener categorías: ' . $e->getMessage(), 500);
        }
    }


    public function createCategory()
    {
        AuthMiddleware::handle();

        $data = Response::json();

        $requied = ['category', 'slug'];
        foreach ($requied as $field) {
            if (empty($data[$field])) {
                Response::error("El campo $field es obligatorio", 400);
                return;
            }
        }

        $clearData = [
            'category' => htmlspecialchars(trim(strip_tags($data['category']))),
            'slug' => htmlspecialchars(trim(strip_tags($data['slug']))),
        ];

        $result  =  $this->model->insertCategory($clearData);


        try {
            if ($result) {
                Response::success(['Message', 'Categoria creada con exito']);
            }
        } catch (Exception $e) {
            throw new Exception('Error al crear la categoria ' . $e->getMessage());
        }
    }

    public function updateCategory($id)
    {
        AuthMiddleware::handle();

        $data = Response::json();

        if (!$id) {
            Response::error("ID de usuario no proporcionado", 400);
            return;
        }

        $category = $this->model->getCategoryById($id);
        if (!$category) {
            Response::error("No se ha encontrado ninguna categoria con el id: {$id}", 404);
            return;
        }

        $required = ['category', 'slug'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                Response::error("El campo $field es obligatorio", 400);
                return;
            }
        }

        $clearData = [
            'category' => htmlspecialchars(trim(strip_tags($data['category']))),
            'slug' => htmlspecialchars(trim(strip_tags($data['slug'])))
        ];

        try {
            $result = $this->model->updateCategory($id, $clearData);
            if ($result) {
                Response::success([
                    'message' => 'Categoría actualizada correctamente'
                ]);
            } else {
                Response::error('No se pudo actualizar la categoría', 500);
            }
        } catch (Exception $e) {
            Response::error('Error al actualizar la categoría: ' . $e->getMessage(), 500);
        }
    }

    public function deleteCategory($id)
    {
        AuthMiddleware::handle();

        if (!$id) {
            Response::error("ID de usuario no proporcionado", 400);
            return;
        }

        $category = $this->model->getCategoryById($id);
        if (!$category) {
            Response::error("No se ha encontrado ninguna categoria con el id: {$id}", 404);
            return;
        }   
        try {
            $result = $this->model->deleteCategory($id);
            if ($result) {
                Response::success([
                    'message' => 'Categoría eliminada correctamente'
                ]);
            } else {
                Response::error('No se pudo eliminar la categoría', 500);
            }
        } catch (Exception $e) {
            Response::error('Error al eliminar la categoría: ' . $e->getMessage(), 500);
        }
    }
}
