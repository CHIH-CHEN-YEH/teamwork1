<?php
require_once("../db_connect.php");

if(!isset($_POST["name"])){
    echo "請循正常管道進入此頁";
    exit;
}

$id = $_POST["id"];
$name = $_POST["name"];
$category_tag = $_POST["category_tag"];
$price = $_POST["price"];

$sql="UPDATE product SET name='$name', category_tag='$category_tag', price='$price' WHERE id='$id'";

if ($conn->query($sql) === TRUE) {
    echo "更新成功";
} else {
    echo "更新資料錯誤: " . $conn->error;
}
header("Location: products.php");
$conn->close();