<?php
header("Content-Type: application/json");
include './db.php';
$method = $_SERVER['REQUEST_METHOD'];
if ($method == 'GET' && isset($_GET['email'])) {
    $email = $_GET['email'];
    $settings = $pdo->query("SELECT email_list FROM settings WHERE id = 1")->fetch(PDO::FETCH_ASSOC);
    if (!$settings) {
        echo json_encode(["message" => "設定數據未找到"]);
        exit;
    }
    $emailList = explode(',', $settings['email_list']);
    if (!in_array($email, $emailList)) {
        echo json_encode(["message" => "您不在參與名單當中"]);
        exit;
    }
    $sql = "SELECT * FROM participants WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['email' => $email]);
    $participant = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($participant) {
        echo json_encode(["message" => "此信箱已存在於參與者名單中"]);
        exit;
    }
    echo json_encode(["message" => "您不在參與名單當中"]);
} else {
    $sql = "SELECT bus_number, GROUP_CONCAT(name SEPARATOR ', ')
     as passengers FROM participants WHERE bus_number
      IS NOT NULL GROUP BY bus_number";
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($results);
}?>