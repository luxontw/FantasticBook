<?php

class Post
{
    // Connection
    private $conn;
    // Table
    private $db_table = "post";
    // Columns
    public $id;
    public $user_id;
    public $title;
    public $text;
    public $date_created;
    public $date_updated;

    // DB connection
    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Read all post
    public function allPost(): mixed
    {
        $sqlQuery = "SELECT 
                        id,
                        user_id,
                        title,
                        text,
                        date_created,
                        date_updated
                    FROM 
                        " . $this->db_table . "";
        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->execute();
        return $stmt;
    }

    // Read single post
    public function singlePost(): bool
    {
        $sqlQuery = "SELECT
                        id, 
                        user_id, 
                        title, 
                        text, 
                        date_created, 
                        date_updated
                    FROM
                        ". $this->db_table ."
                    WHERE 
                        id = ?
                    LIMIT 0,1";
        $stmt = $this->conn->prepare($sqlQuery);
        // Bind data
        $stmt->bindParam(1, $this->id);
        // Get Data
        $stmt->execute();
        // Check if any data be selected
        $rowCount = $this->conn->prepare("SELECT FOUND_ROWS()"); 
        $rowCount->execute();
        if (!$rowCount->fetchColumn()) {
            return false;
        }
        // Data to Row
        $dataRow = $stmt->fetch(PDO::FETCH_ASSOC);
        // Save to class variables   
        $this->user_id = $dataRow['user_id'];
        $this->title = $dataRow['title'];
        $this->text = $dataRow['text'];
        $this->date_created = $dataRow['date_created'];
        $this->date_updated = $dataRow['date_updated'];
        return true;
    }

    // Create post
    public function addPost(): bool
    {
        $sqlQuery = "INSERT INTO
                        ". $this->db_table ."
                    SET
                        user_id = :user_id, 
                        title = :title, 
                        text = :text, 
                        date_created = :date_created,
                        date_updated = :date_updated";
        $stmt = $this->conn->prepare($sqlQuery);
        // Sanitize
        $this->user_id = htmlspecialchars(strip_tags($this->user_id));
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->text = htmlspecialchars(strip_tags($this->text));
        $this->date_created = htmlspecialchars(strip_tags($this->date_created));
        $this->date_updated = htmlspecialchars(strip_tags($this->date_updated));
        // Bind data
        $stmt->bindParam(":user_id", $this->user_id);
        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":text", $this->text);
        $stmt->bindParam(":date_created", $this->date_created);
        $stmt->bindParam(":date_updated", $this->date_updated);
        
        try {
            $stmt->execute();
            return true;
        } catch (PDOException) {
            echo json_encode("User could not be found.");
            return false;
        }
    }

    // Update post
    public function updatePost(): bool
    {
        $sqlQuery = "UPDATE
                        ". $this->db_table ."
                    SET
                        title = :title, 
                        text = :text, 
                        date_updated = :date_updated
                    WHERE 
                        id = :id And user_id = :user_id"; // Check user and owner
        $stmt = $this->conn->prepare($sqlQuery);
        // Sanitize
        $this->id = htmlspecialchars(strip_tags($this->id));
        $this->user_id = htmlspecialchars(strip_tags($this->user_id));
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->text = htmlspecialchars(strip_tags($this->text));
        $this->date_updated = htmlspecialchars(strip_tags($this->date_updated));
        // Bind data
        $stmt->bindParam(":id", $this->id);
        $stmt->bindParam(":user_id", $this->user_id);
        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":text", $this->text);
        $stmt->bindParam(":date_updated", $this->date_updated);
        if ($stmt->execute() && $stmt->rowCount()) { // rowCount(): Check if any data be edited
            return true;
        }
        return false;
    }

    // Delete post
    public function deletePost(): bool
    {
        $sqlQuery = "DELETE FROM 
                        " . $this->db_table . "
                    WHERE 
                        id = :id And user_id = :user_id"; // Check user and owner 
        $stmt = $this->conn->prepare($sqlQuery);
        // Sanitize
        $this->id = htmlspecialchars(strip_tags($this->id));
        $this->user_id = htmlspecialchars(strip_tags($this->user_id));
        // Bind data
        $stmt->bindParam(":id", $this->id);
        $stmt->bindParam(":user_id", $this->user_id);
        if ($stmt->execute() && $stmt->rowCount()) { // rowCount(): Check if any data be edited
            return true;
        }
        return false;
    }
}