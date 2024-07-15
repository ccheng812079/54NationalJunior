<?php
header("Content-Type: application/json");
include 'db.php';
$method = $_SERVER['REQUEST_METHOD'];
if ($method == 'PUT') {
    $sql = "SELECT * FROM participants WHERE bus_number IS NULL";
    $stmt = $pdo->query($sql);
    $participants = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $numParticipants = count($participants);
    $numBuses = ceil($numParticipants / 50);
    $busnum = [];
    for ($i = 0; $i < $numBuses; $i++) {
        $busnum[] = 'AUTO-' . str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
    }
    $busIndex = 0;
    foreach ($participants as $participant) {
        $sql = "UPDATE participants SET bus_number = :bus_number WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'bus_number' => $busnum[$busIndex],
            'id' => $participant['id']
        ]);
        $busIndex = ($busIndex + 1) % $numBuses;
    }
    $sql = "UPDATE settings SET form_enabled = FALSE WHERE id = 1";
    $pdo->exec($sql);
    echo json_encode(["message" => "接駁車已生成"]);
} else {
    echo json_encode(["message" => "Invalid request method"]);
}
?>
