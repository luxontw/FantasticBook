<?php

class Post
{
    private $conn;
    const DB_TABLE = "post";
    const COLUMNS = ["id", "user_id", "title", "text", "date_created", "date_updated"];
    const TEXT_LIMIT = 140;
    const POST_LIMIT = 100;

    public $id;
    public $user_id;
    public $title;
    public $text;
    public $date_created;
    public $date_updated;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function all(): mixed
    {
        $sqlQuery = "SELECT
                        id,
                        user_id,
                        title,
                        text,
                        date_created,
                        date_updated
                    FROM
                        " . self::DB_TABLE . "
                    ORDER BY
                        date_created DESC
                    LIMIT " . self::POST_LIMIT . "";
        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->execute();
        return $stmt;
    }

    public function single($id): bool
    {
        $sqlQuery = "SELECT
                        id,
                        user_id,
                        title,
                        text,
                        date_created,
                        date_updated
                    FROM
                        " . self::DB_TABLE . "
                    WHERE
                        id = ?
                    LIMIT 0,1";
        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->bindParam(1, $id);
        $stmt->execute();

        $rowCount = $this->conn->prepare("SELECT FOUND_ROWS()"); 
        $rowCount->execute();
        if (!$rowCount->fetchColumn()) {
            return false;
        }

        $dataRow = $stmt->fetch(PDO::FETCH_ASSOC);
        foreach (self::COLUMNS as $column) {
            $this->$column = $dataRow[$column];
        }
        return true;
    }

    public function add(): int
    {
        $sqlQuery = "INSERT INTO
                        " . self::DB_TABLE . "
                    SET
                        user_id = :user_id,
                        title = :title,
                        text = :text,
                        date_created = :date_created,
                        date_updated = :date_updated";
        $stmt = $this->conn->prepare($sqlQuery);
        
        foreach (self::COLUMNS as $key => $column) {
            if ($key) {
                $stmt->bindParam(":" . $column . "", $this->$column);
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

    public function update(): bool
    {
        $sqlQuery = "UPDATE
                        " . self::DB_TABLE . "
                    SET
                        title = :title,
                        text = :text,
                        date_updated = :date_updated
                    WHERE
                        id = :id And user_id = :user_id"; // Check user and owner
        $stmt = $this->conn->prepare($sqlQuery);
        
        foreach (self::COLUMNS as $key => $column) {
            if ($key != 4) {
                $stmt->bindParam(":" . $column . "", $this->$column);
            }
        }

        if ($stmt->execute() && $stmt->rowCount()) { // rowCount(): Check if any data be edited
            return true;
        }
        return false;
    }

    public function delete(): bool
    {
        $sqlQuery = "DELETE FROM
                        " . self::DB_TABLE . "
                    WHERE
                        id = :id And user_id = :user_id"; // Check user and owner 
        $stmt = $this->conn->prepare($sqlQuery);

        $stmt->bindParam(":id", $this->id);
        $stmt->bindParam(":user_id", $this->user_id);
        
        if ($stmt->execute() && $stmt->rowCount()) { // rowCount(): Check if any data be edited
            return true;
        }
        return false;
    }
}