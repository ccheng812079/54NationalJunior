<?php
header("Content-Type: application/json");
include './db.php';
$method = $_SERVER['REQUEST_METHOD'];


if ($method == 'GET' && isset($_GET['type'])&&  $_GET['type'] == 'count') {
    $sql = "SELECT COUNT(*) as count FROM participants WHERE bus_number IS NULL";
    $stmt = $pdo->query($sql);
    $count = $stmt->fetch(PDO::FETCH_ASSOC);
    echo json_encode($count);
} else if ($method == 'GET') {
    $sql = "SELECT id, email FROM participants";
    $stmt = $pdo->query($sql);
    $participants = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($participants);
} else if ($method == 'DELETE') {
    $data = json_decode(file_get_contents("php://input"), true);
    if (isset($data['id'])) {
        $id = $data['id'];
        $sql = "DELETE FROM participants WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        echo json_encode(["message" => "參與者已刪除"]);
    }
} else {
    echo json_encode(["message" => "Invalid request method"]);
}
?>
