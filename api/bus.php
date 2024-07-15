<?php
header("Content-Type:application/json");
include 'db.php';
$method=$_SERVER['REQUEST_METHOD'];
$data=json_decode(file_get_contents("php://input"),true);
switch($method){
    case 'POST':
        $busnum=$data['busnum'];
        $driventime=$data['driventime'];
        $sql="INSERT INTO bus (busnum,driventime) VALUES ('$busnum',$driventime)";
        $pdo->exec($sql);
        echo json_encode(["message"=>"bus add"]);
        break;
    case 'GET':
        $sql="SELECT*FROM bus";
        $stmt=$pdo->query($sql);
        $buses=$stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($buses);
        break;
    case 'PUT':
        $id=$data['id'];
        $busnum=$data['busnum'];
        $driventime=$data['driventime'];
        $sql="UPDATE bus SET driventime=$driventime WHERE id=$id";
        $pdo->exec($sql);
        echo json_encode(["message"=>"bus update"]);
        break;
        case 'DELETE':
            $id=$data['id'];
            $sql="DELETE FROM bus WHERE id=$id";
            $pdo->exec($sql);
            echo json_encode(["message"=>"bus delete"]);
            break;
        default:
        echo json_encode(["message"=>"bus Invalid request method"]);
        break;
}
$pdo=null;?>