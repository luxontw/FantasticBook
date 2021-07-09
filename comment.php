<?php

require_once("../config/database.php");

class Comment
{
    const DB_TABLE = "comment";
    const COLUMNS = ["id", "user_id", "post_id", "text", "date_created", "date_updated"];

    public function __construct()
    {
        $this->id = "";
        $this->user_id = "";
        $this->post_id = "";
        $this->text = "";
        $this->date_created = "";
        $this->date_updated = "";
    }

    public static function all($post_id): mixed
    {
        $conn = Database::getConnection();
        $sqlQuery = "SELECT *
                    FROM
                        " . self::DB_TABLE . "
                    WHERE 
                        post_id = ?
                    ORDER BY
                        date_created DESC";

        $stmt = $conn->prepare($sqlQuery);
        $stmt->bindParam(1, $post_id);
        $stmt->execute();
        return $stmt;
    }

    public static function add($comment): int
    {
        $conn = Database::getConnection();
        $sqlQuery = "INSERT INTO
                        " . self::DB_TABLE . "
                    SET
                        user_id = :user_id,
                        post_id = :post_id,
                        text = :text,
                        date_created = :date_created,
                        date_updated = :date_updated";
        $stmt = $conn->prepare($sqlQuery);
        
        foreach (self::COLUMNS as $column) {
            if ($comment->$column != null) {
                $stmt->bindParam(":" . $column . "", $comment->$column);
            }
        }
        
        try {
            if ($stmt->execute()) {
                return 1;
            }
            return 0;
        } catch (PDOException) { // User or post not found
            return 2;
        }
    }

    public static function update($comment): bool
    {
        $conn = Database::getConnection();
        $sqlQuery = "UPDATE
                        " . self::DB_TABLE . "
                    SET
                        text = :text,
                        date_updated = :date_updated
                    WHERE
                        id = :id And user_id = :user_id"; // Check user and owner
        $stmt = $conn->prepare($sqlQuery);
        
        foreach (self::COLUMNS as $column) {
            if ($comment->$column != null) {
                $stmt->bindParam(":" . $column . "", $comment->$column);
            }
        }

        if ($stmt->execute() && $stmt->rowCount()) { // rowCount(): Check if any data be edited
            return true;
        }
        return false;
    }

    public static function delete($id, $user_id): bool
    {
        $conn = Database::getConnection();
        $sqlQuery = "DELETE FROM
                        " . self::DB_TABLE . "
                    WHERE
                        id = :id And user_id = :user_id"; // Check user and owner 
        $stmt = $conn->prepare($sqlQuery);

        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":user_id", $user_id);
        
        if ($stmt->execute() && $stmt->rowCount()) { // rowCount(): Check if any data be edited
            return true;
        }
        return false;
    }
}