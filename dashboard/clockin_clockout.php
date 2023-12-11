<?php

session_start();

$id = $_SESSION["user_id"];

require_once "../includes/database.php";

date_default_timezone_set('Asia/Kolkata');

$currentTime = date("Y-m-d H:i:s");
$date = date("Y-m-d");

$sql = "SELECT clock_in_time FROM time_logs WHERE users_id = '$id' AND DATE(created_at) = '$date' AND clock_out_time IS NULL";

$stmt = $pdo->prepare($sql);
$stmt->execute();
$result = $stmt->fetch();

if ($result) {

    $sqlId = "SELECT id FROM time_logs WHERE users_id = '$id' ORDER BY id DESC";
    $stmt = $pdo->prepare($sqlId);
    $stmt->execute();
    $resultId = $stmt->fetch();

    $timeLogId = $resultId[0];
    $description = $_POST["description"];

    $updateSql = "UPDATE time_logs SET clock_out_time = '$currentTime' , description = '$description' WHERE id = '$timeLogId'";

    $stmt = $pdo->prepare($updateSql);
    $stmt->execute();
} else {
    $sql = "INSERT INTO time_logs (users_id , clock_in_time) VALUES ('$id' , '$currentTime')";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
}

header("Location: index.php");
