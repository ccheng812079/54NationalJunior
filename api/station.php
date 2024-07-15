<?php
header("Content-Type:application/json");
include 'db.php';
$method=$_SERVER['REQUEST_METHOD'];
$data=json_decode(file_get_contents("php://input"),true);
switch($method){
    case 'POST':
        $sitename=$data['sitename'];
        $driventime=$data['driventime'];
        $stime=$data['stime'];
        $sql="INSERT INTO station (sitename,driventime,stime) VALUES ('$sitename',$driventime,$stime)";
        $pdo->exec($sql);
        echo json_encode(["message"=>"bus add"]);
        break;
    case 'GET':
        $sql="SELECT*FROM station";
        $stmt=$pdo->query($sql);
        $buses=$stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($buses);
        break;
    case 'PUT':
        $id=$data['id'];
        $sitename=$data['sitename'];
        $driventime=$data['driventime'];
        $stime=$data['stime'];
        $sql="UPDATE station SET driventime=$driventime, stime=$stime; WHERE id=$id";
        $pdo->exec($sql);
        echo json_encode(["message"=>"bus update"]);
        break;
        case 'DELETE':
            $id=$data['id'];
            $sql="DELETE FROM station WHERE id=$id";
            $pdo->exec($sql);
            echo json_encode(["message"=>"bus delete"]);
            break;
        default:
        echo json_encode(["message"=>"bus Invalid request method"]);
        break;
}
$pdo=null;?>