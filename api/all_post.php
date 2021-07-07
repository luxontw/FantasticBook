<?php
Session_Start();
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8"); 


require_once("../post.php");

if (!isset($_SESSION["user_id"])) {
    http_response_code(401);
    echo json_encode(
        array("message" => "Permission denied.")
    );
    die();
}

$item = new Post();
$user_id = isset($_GET["user_id"]) ? $_GET["user_id"] : null;
$stmt = $item->all($user_id);
$itemCount = $stmt->rowCount();

if ($itemCount > 0) {
    $postArr = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        foreach (Post::COLUMNS as $column) {
            $post[$column] = $$column;
        }
        array_push($postArr, $post);
    }
    echo json_encode($postArr);
} else {  
    http_response_code(404);
    echo json_encode(
        array("message" => "No post found.")
    );
}