<?php
session_start();
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    
require_once("../post.php");

// User input
$data = json_decode(file_get_contents("php://input"));

if (Post::delete($data->id, $_SESSION["user_id"])) {
    echo json_encode(
        array("message" => "Post deleted successfully.")
    );
} else {
    http_response_code(401);
    echo json_encode(
        array("message" => "Post could not be deleted.")
    );
}