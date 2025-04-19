<?php
require_once  'models/users/user.model.php';

class userController{
    public function getAllUsers(){
        $userModel = new userModel();
        try{
            $users = $userModel->getAllUsers();
            return $users;
        }catch(PDOException $e){
            throw new Exception('Error al obtener todos los usuarios ' . $e->getMessage());
        }
    }
}