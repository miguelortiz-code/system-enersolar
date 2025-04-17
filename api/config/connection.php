<?php 

class Connection{
    public static function getConnection(){
        $user = 'root';
        $password = '';
        $server = 'localhost';
        $database = 'enersolar';
        try{
        $connection = new PDO("mysql:dbname=$database;charset=utf8; host=$server", $user, $password);
        return $connection;
        exit;
    }catch(Exception $e){
            die('Error al conectarse a la base de datos: ' . $database . $e->getMessage());
        }
    } 
}

