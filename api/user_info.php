<?php
Session_Start();
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once("../user.php");

if (!isset($_SESSION["user_id"])) {
    http_response_code(401);
    echo json_encode(
        array("message" => "Permission denied.")
    );
    die();
}

$item = new User();
$username = isset($_GET["username"]) ? $_GET["username"] : die();
$user = $item->info($username);

if ($user->username != "") {
    foreach (User::COLUMNS as $column) {
        if($column != "password") {
            $userArr[$column] = $user->$column;
        }
    }
    http_response_code(200);
    echo json_encode($userArr);
} else {
    http_response_code(404);
    echo json_encode(
        array("message" => "User not found.")
    );
}