<?php
require_once 'models/categories/category.model.php';
class categoryController{
 public function getAllCategories(){
    $categoryModel = new categoryModel();
    try{
        $categories = $categoryModel->getAllCategories();
        return $categories;
    }catch(Exception $e){
        throw new Exception('Error al obtener todas las categorias ' . $e->getMessage());
    }
 }   
}