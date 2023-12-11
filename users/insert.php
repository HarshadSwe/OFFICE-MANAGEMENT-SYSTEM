<?php

if ($_SERVER["REQUEST_METHOD"] == 'POST') {

    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $phone = $_POST["number"];
    $address = $_POST["address"];
    $pincode = $_POST["pincode"];
    $role = $_POST["role"];
    $date_of_birth = $_POST["date_of_bith"];

    if (empty($_FILES)) {
        exit('$_FILES is empty - is file_uploads enabled in php.ini');
    } else {
        $fileName = $_FILES['image']['name'];
        $imageData = $_FILES['image']['tmp_name'];
    }

    $folderPath = realpath('../assets/uploads');

    $dbpath = '../assets/uploads';
    $imagePath = $dbpath . '/' . time().'_'.$_FILES['image']['name'];

    move_uploaded_file($imageData, $imagePath);

    print_r($imageData);

    echo "<h2>$name , $email , $password , $phone , $address , $pincode , $role</h2>";
    require_once "../includes/database.php";

    $sql = "INSERT INTO users (name, email, password, phone , role_id , image) VALUES (? , ? , ? , ? , ? , ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$name, $email, $password, $phone, $role, $imagePath]);

    $user_id = $pdo->lastInsertId();

    $sql = "INSERT INTO users_details (address , pincode , date_of_birth ,user_id) VALUES (? , ? , ? , ?)";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$address, $pincode, $date_of_birth , $user_id]);

    $pdo = null;
    $stmt = null;

    $userInserted = "user";
    session_start();

    $_SESSION["insertUser"] = $userInserted;
    header("Location: index.php");
}
