<?php

require_once("../db_connect.php");
require_once("../db_connect.php");
$sql = "SELECT * FROM product ORDER BY id ASC";
$result = $conn->query($sql);
$rows = $result->fetch_all(MYSQLI_ASSOC);

if (!isset($_POST["title"])) {
    echo "請循正常管道進入此頁";
    exit;
}

$title = $_POST["title"];
if (empty($title)) {
    echo "標題不能為空";
    exit;
}

$sqlCheck = "SELECT * FROM video WHERE title = '$title'";
$result = $conn->query($sqlCheck);
$videoCount = $result->num_rows;



if ($videoCount > 0) {
    echo "該影片已存在";
    exit;
}
$product_id = $_POST["id"];


$yt_url = $_POST["yt_url"];
if (empty($yt_url)) {
    echo "網址不能為空";
    exit;
}


$Video_duration = $_POST["Video_duration"];
$now = date('Y-m-d H:i:s');


$sql = "INSERT INTO video (title, product_id, yt_url, Video_duration,created_time, updated_time, valid)
	VALUES ('$title', '$product_id', '$yt_url', '$Video_duration', '$now', '$now', 1)";


if ($conn->query($sql) === TRUE) {
    $last_id = $conn->insert_id;
    echo "新資料輸入成功, id 為 $last_id";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

header("location: videos.php");

$conn->close();
