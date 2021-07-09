<?php
session_start();
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once("../post.php");

if (!isset($_SESSION["user_id"])) {
    http_response_code(401);
    echo json_encode(
        array("message" => "Permission denied.")
    );
    die();
}

$id = isset($_GET["id"]) ? $_GET["id"] : die();
$post = Post::single($id);

if ($post->user_id != "") {
    foreach (Post::COLUMNS as $column) {
        $postArr[$column] = $post->$column;
    }
    http_response_code(200);
    echo json_encode($postArr);
} else {
    http_response_code(404);
    echo json_encode(
        array("message" => "Post not found.")
    );
}