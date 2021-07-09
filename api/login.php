<?php
session_start();
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once("../user.php");

// User input
$data = json_decode(file_get_contents("php://input"));
$data->username;
$data->password;

if (User::login($data->username, $data->password)) {
    $user = User::info($data->username);
    $_SESSION["user_id"] = $user->id;

    echo json_encode(
        array("message" => "Login successful.")
    );
} else {
    http_response_code(401);
    echo json_encode(array("message" => "Login failed."));
}