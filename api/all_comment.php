<?php
session_start();
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8"); 


require_once("../comment.php");

if (!isset($_SESSION["user_id"])) {
    http_response_code(401);
    echo json_encode(
        array("message" => "Permission denied.")
    );
    die();
}

$post_id = isset($_GET["post_id"]) ? $_GET["post_id"] : null;
$stmt = Comment::all($post_id);
$itemCount = $stmt->rowCount();

if ($itemCount > 0) {
    $commentArr = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        foreach (Comment::COLUMNS as $column) {
            $comment[$column] = $$column;
        }
        array_push($commentArr, $comment);
    }
    echo json_encode($commentArr);
} else {  
    http_response_code(404);
    echo json_encode(
        array("message" => "No comment found.")
    );
}