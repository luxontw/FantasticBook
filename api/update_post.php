<?php
Session_Start();
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    
require_once("../post.php");

$item = new Post();

// User input
$data = json_decode(file_get_contents("php://input"));
if (mb_strlen($data->text) > Post::TEXT_LIMIT) {
    echo json_encode("The text is more than " . Post::TEXT_LIMIT . " words.");
    die();
}
$item->id = $data->id;
$item->user_id = $_SESSION["user_id"];
$item->title = $data->title;
$item->text = $data->text;

// Auto generate
$item->date_updated = date('Y-m-d H:i:s');

if ($item->update($item)) {
    echo json_encode(
        array("message" => "Post updated successfully.")
    );
} else {
    http_response_code(401);
    echo json_encode(
        array("message" => "Post could not be updated.")
    );
}