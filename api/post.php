<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';
include_once '../post.php';

// Connect to database
$database = new Database();
$db = $database->getConnection();
// Get select post
$item = new Post($db);
$item->id = isset($_GET['id']) ? $_GET['id'] : die();

if ($item->singlePost()) {
    // create array
    $postArr = array(
        "id" =>  $item->id,
        "user_id" => $item->user_id,
        "title" => $item->title,
        "text" => $item->text,
        "date_created" => $item->date_created,
        "date_updated" => $item->date_updated
    );  
    http_response_code(200);
    echo json_encode($postArr);
} else {
    http_response_code(404);
    echo json_encode("Post not found.");
}