<?php 

class Database 
{
    private static $conn;
    
    public static function getConnection()
    {
        $host = "127.0.0.1";
        $db_name = "ftbook";
        $username = "kkk";
        $password = "kkk";
        if (!empty(self::$conn)) {
            return self::$conn;
        }
        try {
            self::$conn = new PDO("mysql:host=$host;dbname=$db_name", $username, $password, array(
                PDO::ATTR_PERSISTENT => true
            ));
            self::$conn->exec("set names utf8");
        } catch (PDOException $e) {
            echo json_encode(
                array("Database could not be connected" => $e->getMessage())
            );
        }
        return self::$conn;
    }
 }