<?php
session_start();
header('Content-Type: application/json');
include("../config/db.php");

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "message" => "Unauthorized"]);
    exit();
}

$data = json_decode(file_get_contents("php://input"), true);

$id = $data['id'] ?? 0;
$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("DELETE FROM saved_news WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $id, $user_id);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Deleted successfully"]);
} else {
    echo json_encode(["success" => false, "message" => "Delete failed"]);
}
?>