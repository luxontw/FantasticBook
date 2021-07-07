<?php

require_once("../config/database.php");

class Post
{
    const DB_TABLE = "post";
    const COLUMNS = ["id", "user_id", "title", "text", "date_created", "date_updated"];
    const TEXT_LIMIT = 140;
    const POST_LIMIT = 100;

    public function __construct()
    {
        $this->id = "";
        $this->user_id = "";
        $this->title = "";
        $this->text = "";
        $this->date_created = "";
        $this->date_updated = "";
    }

    public static function all($user_id): mixed
    {
        $conn = Database::getConnection();
        $user_id_set = !empty($user_id) ? "WHERE user_id = ?" : "";
        $sqlQuery = "SELECT *
                    FROM
                        " . self::DB_TABLE . "
                        " . $user_id_set . " 
                    ORDER BY
                        date_created DESC
                    LIMIT " . self::POST_LIMIT . "";

        $stmt = $conn->prepare($sqlQuery);
        if(!empty($user_id)) {
            $stmt->bindParam(1, $user_id);
        }
        $stmt->execute();
        return $stmt;
    }

    public static function single($id): Post
    {
        $conn = Database::getConnection();
        $post = new Post();
        $sqlQuery = "SELECT *
                    FROM
                        " . self::DB_TABLE . "
                    WHERE
                        id = ?
                    LIMIT 0,1";
        $stmt = $conn->prepare($sqlQuery);
        $stmt->bindParam(1, $id);
        $stmt->execute();

        if ($dataRow = $stmt->fetch(PDO::FETCH_ASSOC)) {
            foreach (self::COLUMNS as $column) {
                $post->$column = $dataRow[$column];
            }
        }
        return $post;
    }

    public static function add($post): int
    {
        $conn = Database::getConnection();
        $sqlQuery = "INSERT INTO
                        " . self::DB_TABLE . "
                    SET
                        user_id = :user_id,
                        title = :title,
                        text = :text,
                        date_created = :date_created,
                        date_updated = :date_updated";
        $stmt = $conn->prepare($sqlQuery);
        
        foreach (self::COLUMNS as $column) {
            if ($post->$column != null) {
                $stmt->bindParam(":" . $column . "", $post->$column);
            }
        }
        
        try {
            if ($stmt->execute()) {
                return 1;
            }
            return 0;
        } catch (PDOException) { // User not found
            return 2;
        }
    }

    public static function update($post): bool
    {
        $conn = Database::getConnection();
        $title_set = !empty($post->title) ? ",title = :title" : "";
        $text_set = !empty($post->text) ? ",text = :text" : "";
        $sqlQuery = "UPDATE
                        " . self::DB_TABLE . "
                    SET
                        date_updated = :date_updated
                        " . $title_set . "
                        " . $text_set . "
                    WHERE
                        id = :id And user_id = :user_id"; // Check user and owner
        $stmt = $conn->prepare($sqlQuery);
        
        foreach (self::COLUMNS as $column) {
            if ($post->$column != null) {
                $stmt->bindParam(":" . $column . "", $post->$column);
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