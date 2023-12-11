<?php

if ($_SERVER["REQUEST_METHOD"] == 'POST') {

    session_start();

    $email = $_POST["email"];
    $password = $_POST["password"];

    require_once "../includes/database.php";

    $sql = "SELECT * FROM users WHERE email = '$email' and password = '$password'";

    $stmt = $pdo->prepare($sql);

    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
 
    // echo "<pre>";
    if ($result) {
        // print_r($result);
        // session_regenerate_id();
        $_SESSION["user_id"] = $result[0]["id"];

        header("Location: ../dashboard/index.php");
    } else {
        header("Location: index.php");
    }
}
