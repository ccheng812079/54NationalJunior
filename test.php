<?php
header("Content-Type:application/json");
include 'db.php';
$method=$_SERVER['REQUEST_METHOD'];

if($method=='GET' && isset($_GET['type']) && $_GET['type']='count' ){
    $sql="SELECT COUNT(*)as count FROM participants WHERE bus_numberIS NULL ";
    $stmt=$pdo->query($sql);
    $participants=$stmt->fetch(PDO::FETCH_ASSOC);
    echo json_encode($count);
}else if($method=='GET'){
$sql="SELECT id,email FROM participants";
$stmt=$pdo->query($sql);
$participants=$stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($participants);
}else if($method=='DELETE'){
    $data=json_decode(file_get_contents("php://input"),true);
    $sql="DELETE FROM psricipants WHERE id=:id";
    $stmt=
    echo json_encode(["message"=>"接駁車已生成"]);
}else{
    echo json_encode(["message"=>"bus Invalid request method"]);
}?>