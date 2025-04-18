<?php 
class Connection {
    public static function getConnection() {
        $user = 'root';
        $password = '';
        $server = 'localhost';
        $database = 'enersolar';
        try {
            $connection = new PDO("mysql:host=$server;dbname=$database;charset=utf8", $user, $password);
            $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $connection;
        } catch (PDOException $e) {
            die("Error al conectarse a la base de datos: " . $e->getMessage());
        }
    }
}