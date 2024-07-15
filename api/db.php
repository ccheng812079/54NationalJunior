<?php
$servername="localhost";
$db_username="root";
$db_password="";
$dbname="web08";
$charset="utf8mb4";
$dsn="mysql:host=$servername; dbname=$dbname; charset=$charset";
$pdo=new PDO($dsn,$db_username,$db_password);
?>