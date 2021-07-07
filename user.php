<?php

require_once("../config/database.php");

class User
{
    const DB_TABLE = "user";
    const COLUMNS = ["id", "username", "email", "password", "date_created", "date_updated"];
    
    public function __construct()
    {
        $this->id = "";
        $this->username = "";
        $this->email = "";
        $this->password = "";
        $this->date_created = "";
        $this->date_updated = "";
    }

    public static function login($username, $password): bool
    {
        $user = self::info($username);
        if ($user && password_verify($password, $user->password)) {
            return true;
        }
        return false;
    }

    public static function info($user_info): User
    {
        $conn = Database::getConnection();
        $user = new User();
        $user_id_set = is_integer($user_info) ? "id = ?" : "";
        $username_set = is_string($user_info) ? "username = ?" : "";
        
        $sqlQuery = "SELECT *
                    FROM
                        " . self::DB_TABLE . "
                    WHERE
                        " . $user_id_set . "
                        " . $username_set . "";

        $stmt = $conn->prepare($sqlQuery);
        $stmt->bindParam(1, $user_info);
        $stmt->execute();

        if ($dataRow = $stmt->fetch(PDO::FETCH_ASSOC)) {
            foreach (self::COLUMNS as $column) {
                $user->$column = $dataRow[$column];
            }
        }
        return $user;
    }

    public static function add($user): bool
    {
        $item = $user->info($user->username);
        if (!empty($item->id)) {
            return false;
        }
        $conn = Database::getConnection();
        $sqlQuery = "INSERT INTO
                        " . self::DB_TABLE . "
                    SET
                        username = :username,
                        email = :email,
                        password = :password,
                        date_created = :date_created,
                        date_updated = :date_updated";
        $stmt = $conn->prepare($sqlQuery);
        
        $user->password = password_hash($user->password, PASSWORD_DEFAULT);
        foreach (self::COLUMNS as $column) {
            if ($user->$column != null) {
                $stmt->bindParam(":" . $column . "", $user->$column);
            }
        }
        
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public static function update($user): bool
    {
        $conn = Database::getConnection();
        $username_set = !empty($user->username) ? ",username = :username" : "";
        $email_set = !empty($user->email) ? ",email = :email" : "";
        $password_set = !empty($user->password) ? ",password = :password" : "";
        $sqlQuery = "UPDATE
                        " . self::DB_TABLE . "
                    SET
                        date_updated = :date_updated
                        " . $username_set . "
                        " . $email_set . "
                        " . $password_set . "
                    WHERE
                        id = :id";
        $stmt = $conn->prepare($sqlQuery);
        
        $user->password = password_hash($user->password, PASSWORD_DEFAULT);
        foreach (self::COLUMNS as $column) {
            if ($user->$column != null) {
                $stmt->bindParam(":" . $column . "", $user->$column);
            }
        }

        if ($stmt->execute() && $stmt->rowCount()) {
            return true;
        }
        return false;
    }

    public static function delete($id): bool
    {
        $conn = Database::getConnection();
        $sqlQuery = "DELETE FROM
                        " . self::DB_TABLE . "
                    WHERE
                        id = :id";
        $stmt = $conn->prepare($sqlQuery);

        $stmt->bindParam(":id", $id);
        
        if ($stmt->execute() && $stmt->rowCount()) {
            return true;
        }
        return false;
    }
}