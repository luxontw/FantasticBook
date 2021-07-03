<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';
include_once '../post.php';

// For test
date_default_timezone_set("Asia/Taipei");

// Connect to database
$database = new Database();
$db = $database->getConnection();
$item = new Post($db);
// User input
$data = json_decode(file_get_contents("php://input"));
$item->user_id = $data->user_id;
$item->title = $data->title;
$item->text = $data->text;
// Auto generate
$item->date_created = date('Y-m-d H:i:s');
$item->date_updated = '0000-00-00 00:00:00';

if ($item->addPost()) {
    echo json_encode("Post created successfully.");
} else {
    echo json_encode("Post could not be created.");
}