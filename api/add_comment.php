<?php
session_start();
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once("../comment.php");

$item = new Comment();

// User input
$data = json_decode(file_get_contents("php://input"));
if (mb_strlen($data->text) > 100) {
    echo json_encode(
        array("message" => "The text is more than 100 words.")
    );
    die();
}
$item->post_id = $data->post_id;
$item->text = $data->text;

// Auto generate
$item->user_id = $_SESSION["user_id"];
$item->date_created = date('Y-m-d H:i:s');
$item->date_updated = '0000-00-00 00:00:00';

$status = Comment::add($item);

if ($status == 1) {
    echo json_encode(
        array("message" => "Comment created successfully.")
    );
} else if ($status == 0) {
    echo json_encode(
        array("message" => "Comment could not be created.")
    );
} else {
    echo json_encode(
        array("message" => "User or post not found, comment could not be created.")
    );
}