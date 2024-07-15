<?php
header("Content-Type: application/json");
include 'db.php';
$method = $_SERVER['REQUEST_METHOD'];
$data = json_decode(file_get_contents("php://input"), true);

if ($method == 'POST') {
    if (isset($data['form_enabled'])) {
        $form_enabled = $data['form_enabled'];
        $sql = "UPDATE settings SET form_enabled = :form_enabled WHERE id = 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['form_enabled' => $form_enabled]);
        echo json_encode(["message" => "設定已更新"]);
    } else if (isset($data['new_email'])) {
        $new_email = $data['new_email'];
        $settings = $pdo->query("SELECT * FROM settings WHERE id = 1")->fetch(PDO::FETCH_ASSOC);
        if (!$settings) {
            echo json_encode(["message" => "設定數據未找到"]);
            exit;
        }
        $emailList = explode(',', $settings['email_list']);
        if (in_array($new_email, $emailList)) {
            echo json_encode(["message" => "此信箱已存在於參與者名單中"]);
            exit;
        }
        $emailList[] = $new_email;
        $emailListString = implode(',', $emailList);
        $sql = "UPDATE settings SET email_list = :email_list WHERE id = 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['email_list' => $emailListString]);
        echo json_encode(["message" => "信箱已新增"]);
    } else {
        if (!isset($data['name']) || !isset($data['email'])) {
            echo json_encode(["message" => "請提供完整的參與者信息"]);
            exit;
        }
        $name = $data['name'];
        $email = $data['email'];
        $settings = $pdo->query("SELECT * FROM settings WHERE id = 1")->fetch(PDO::FETCH_ASSOC);
        if (!$settings) {
            echo json_encode(["message" => "設定數據未找到"]);
            exit;
        }
        $emailList = explode(',', $settings['email_list']);
        if (!$settings['form_enabled']) {
            echo json_encode(["message" => "該表單目前不接受回應"]);
            exit;
        }
        if (!in_array($email, $emailList)) {
            echo json_encode(["message" => "您不在參與者名單中"]);
            exit;
        }
        $sql = "SELECT * FROM participants WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['email' => $email]);
        if ($stmt->rowCount() > 0) {
            echo json_encode(["message" => "您已經參與過意見調查"]);
            exit;
        }
        $sql = "INSERT INTO participants (name, email) VALUES (:name, :email)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['name' => $name, 'email' => $email]);
        echo json_encode(["message" => "感謝您的參與"]);
    }
} else if ($method == 'GET' && isset($_GET['type']) && $_GET['type'] == 'settings') {
    $settings = $pdo->query("SELECT * FROM settings WHERE id = 1")->fetch(PDO::FETCH_ASSOC);
    if (!$settings) {
        echo json_encode(["message" => "設定數據未找到"]);
        exit;
    }
    $settings['email_list'] = explode(',', $settings['email_list']);
    echo json_encode($settings);
} else {
    echo json_encode(["message" => "Invalid request method"]);
}
?>
