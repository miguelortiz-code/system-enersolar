<?php
require_once  'models/products/product.model.php';
class productController{    
    
    public function getAllProducts(){
        $productModel = new productModel();
        try{
            $products  = $productModel->getAllProducts();
            return $products;
        }catch(Exception $e){
            throw new Exception('Error al obtener todos los productos ' . $e->getMessage());
        }
    }
}