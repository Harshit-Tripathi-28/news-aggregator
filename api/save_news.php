<?php
session_start();
header('Content-Type: application/json');

include("../config/db.php");

if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        "success" => false,
        "message" => "User not logged in"
    ]);
    exit();
}

$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    echo json_encode([
        "success" => false,
        "message" => "Invalid JSON data"
    ]);
    exit();
}

$user_id = $_SESSION['user_id'];
$title = $data['title'] ?? '';
$description = $data['description'] ?? '';
$url = $data['url'] ?? '';
$image = $data['image'] ?? '';

if (trim($title) === '' || trim($url) === '') {
    echo json_encode([
        "success" => false,
        "message" => "Title and URL are required"
    ]);
    exit();
}

try {
    $check = $conn->prepare("SELECT id FROM saved_news WHERE user_id = ? AND url = ?");
    $check->bind_param("is", $user_id, $url);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        echo json_encode([
            "success" => false,
            "message" => "News already saved"
        ]);
        exit();
    }

    $stmt = $conn->prepare("INSERT INTO saved_news (user_id, title, description, url, image) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issss", $user_id, $title, $description, $url, $image);

    if ($stmt->execute()) {
        echo json_encode([
            "success" => true,
            "message" => "News saved successfully"
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Database insert failed: " . $stmt->error
        ]);
    }
} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "message" => "Error: " . $e->getMessage()
    ]);
}
?>