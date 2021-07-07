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
    echo json_encode(
        array("message" => "The text is more than " . Post::TEXT_LIMIT . " words.")
    );
    die();
}
$item->title = $data->title;
$item->text = $data->text;

// Auto generate
$item->user_id = $_SESSION["user_id"];
$item->date_created = date('Y-m-d H:i:s');
$item->date_updated = '0000-00-00 00:00:00';

$status = $item->add($item);

if ($status == 1) {
    echo json_encode(
        array("message" => "Post created successfully.")
    );
} else if ($status == 0) {
    echo json_encode(
        array("message" => "Post could not be created.")
    );
} else {
    http_response_code(401);
    echo json_encode(
        array("message" => "User not found, post could not be created.")
    );
}