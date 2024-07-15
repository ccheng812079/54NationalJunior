<?php
header("Content-Type: application/json");// 告訴瀏覽器使用 JSON 格式
include 'db.php'; // 引入 db.php

$method = $_SERVER['REQUEST_METHOD']; // 取得請求方法
$data = json_decode(file_get_contents("php://input"), true); // 取得請求內容

switch ($method) { // 判斷請求方法
    case 'POST': // 如果是 POST 請求
        $name = $data['name'];
        $email = $data['email'];
        $bus_number = $data['bus_number'];
        $sql = "INSERT INTO participants (name, email, bus_number) VALUES ('$name', $email, $bus_number)"; // 新增資料
        $pdo->exec($sql);
        echo json_encode(["message" => "站點新增成功!"]); // 回傳訊息
        break; // 結束

    case 'GET': // 如果是 GET 請求
        $sql = "SELECT * FROM participants";
        $stmt = $pdo->query($sql);
        $stations = $stmt->fetchAll(PDO::FETCH_ASSOC); // 取得所有資料
        echo json_encode($stations);
        break;

    case 'PUT': // 如果是 PUT 請求
        $id = $data['id'];
        $name = $data['name'];
        $email = $data['email'];
        $bus_number = $data['bus_number'];
        $sql = "UPDATE participants SET name='$name', email=$email, bus_number=$bus_number WHERE id=$id"; // 更新資料
        $pdo->exec($sql);
        echo json_encode(["message" => "站點更新成功!"]);
        break;

    case 'DELETE': // 如果是 DELETE 請求
        $id = $data['id'];
        $sql = "DELETE FROM participants WHERE id=$id"; // 刪除資料
        $pdo->exec($sql);
        echo json_encode(["message" => "站點刪除成功!"]);
        break;

    default: // 如果是其他請求
        echo json_encode(["message" => "無效的請求方法"]);
        break;
}

$pdo = null; // 關閉資料庫連線
