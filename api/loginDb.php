<?php
session_start();
include 'db.php';
$username=$_GET['username']??'';
$password=$_GET['password']??'';
$sql="SELECT*FROM `user` WHERE `username`='$username' AND `password`='$password'";
$result=$pdo->query($sql)->fetch();
if(empty($result)){
    echo $result;
}else{
    $_SESSION['username']=$username;
    echo $result;
}?>