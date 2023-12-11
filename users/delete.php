<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="../assets/stylesheet/style.css">
    <title>Delete Roles</title>
</head>

<body>
    <?php
    include("../layouts/sidemenu.php");
    ?>

    <div class="container">

        <div class="main mb-4">
            <h1 class="text-danger">Delete Roles</h1>
        </div>
        <?php
        if (isset($_POST["deleteId"])) {
            require_once "../includes/database.php";

            $id = $_POST["deleteId"];

            // echo "$id";

            $checklogs = "SELECT * FROM time_logs WHERE users_id = '$id'";
            $stmt = $pdo->prepare($checklogs);
            $stmt->execute();

            $checkResult = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // echo "<pre>";
            // print_r($checkResult);
            if ($checkResult) {

                $_SESSION["checkDelete"] = $checkResult;
                header("Location: index.php");
                
            } else {

                $sql = "DELETE FROM users_details WHERE user_id = $id";
                $stmt = $pdo->prepare($sql);
                $stmt->execute();

                $sql = "DELETE FROM users WHERE id = $id";
                $stmt = $pdo->prepare($sql);
                $stmt->execute();
                $deleteUserAlert = 'Delete';
                
                $_SESSION["userDeleted"] = $deleteUserAlert;
                header("Location: index.php");

            }
            $stmt = null;
        }
        ?>
    </div>

</body>

</html>