<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';
include_once '../post.php';

$database = new Database();
$db = $database->getConnection();

$item = new Post($db);
$id = isset($_GET["id"]) ? $_GET["id"] : die();

if ($item->single($id)) {
    foreach (Post::COLUMNS as $column) {
        $postArr[$column] = $item->$column;
    }
    http_response_code(200);
    echo json_encode($postArr);
} else {
    http_response_code(404);
    echo json_encode(
        array("message" => "Post not found.")
    );
}