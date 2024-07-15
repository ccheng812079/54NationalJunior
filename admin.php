<?php
session_start();
if(!isset($_SESSION['username'])){
    header("location:login.php");
    exit();
}?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>台北101接駁專車</title>
    <?php include 'link.php';?>
</head>
<body>    <?php include 'header.php';?>
    
<div class="container mt-5 pt-3">
    <?php $pos=$_GET['pos']??'bus';?>
<div class="border p-3">
<a class="btn <?php echo $pos=='bus'?'btn-primary':'btn-light'?>"href="?pos=bus">接駁車管理</a>
<a class="btn <?php echo $pos=='station'?'btn-primary':'btn-light'?>" href="?pos=station">站點管理</a>
<a class="btn <?php echo $pos=='form'?'btn-primary':'btn-light'?>" href="?pos=form">表單調查</a>
</div>
<?php include "admin$pos.php";?>
</div>
</body>
</html>