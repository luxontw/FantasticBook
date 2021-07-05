<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8"); 

include_once '../config/database.php';
include_once '../post.php';

$database = new Database();
$db = $database->getConnection();

$item = new Post($db);
$stmt = $item->all();
$itemCount = $stmt->rowCount();

if ($itemCount > 0) {
    $postArr = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        foreach (Post::COLUMNS as $column) {
            $post[$column] = $$column;
        }
        array_push($postArr, $post);
    }
    echo json_encode($postArr);
} else {  
    http_response_code(404);
    echo json_encode(
        array("message" => "No post found.")
    );
}