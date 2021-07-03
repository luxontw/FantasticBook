<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8"); 

include_once '../config/database.php';
include_once '../post.php';

// Connect to database
$database = new Database();
$db = $database->getConnection();
$items = new Post($db);
// Get all post
$stmt = $items->allPost();
// Number of post
$itemCount = $stmt->rowCount();

if ($itemCount > 0) {   
    $postArr = array();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $e = array(
            "id" => $id,
            "user_id" => $user_id,
            "title" => $title,
            "text" => $text,
            "date_created" => $date_created,
            "date_updated" => $date_updated
        );
        array_push($postArr, $e);
    }
    echo json_encode($postArr);
} else {
    http_response_code(404);
    echo json_encode(
        array("message" => "No post found.")
    );
}