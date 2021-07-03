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
$item = new Post($db);

// User input
$data = json_decode(file_get_contents("php://input"));
$item->id = $data->id;
$item->user_id = $data->user_id;

if($item->deletePost()){
    echo json_encode("Post deleted.");
} else{
    echo json_encode("Post could not be deleted");
}