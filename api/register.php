<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once("../user.php");

$user = new User();

// User input
$data = json_decode(file_get_contents("php://input"));
$user->username = $data->username;
$user->email = $data->email;
$user->password = $data->password;

// Auto generate
$user->date_created = date('Y-m-d H:i:s');
$user->date_updated = '0000-00-00 00:00:00';
if ($user->add($user)) {
    echo json_encode(array("message" => "User created successfully."));
} else {
    http_response_code(400);
    echo json_encode(array("message" => "User created failed."));
}