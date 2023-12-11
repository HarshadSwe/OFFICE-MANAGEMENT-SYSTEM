<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/stylesheet/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <title>Edit TimeLogs</title>
</head>

<body>
<?php
    include("../layouts/sidemenu.php");
    ?>
     <div class="container">
        <div class="main mb-4">
            <h1>Delete TimeLogs</h1>
        </div>
        <?php
        if (isset($_POST["timelog_id"])) {

            require_once "../includes/database.php";

            $id = $_POST["timelog_id"];

            echo "$id";

            $sql = "DELETE FROM time_logs WHERE id = '$id'";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();

            $stmt = null;

            $timelogsDel = 'Delete';
            $_SESSION["timelogsDel"] = $timelogsDel;
            header("Location: index.php");
        }
        ?>
    </div>

</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

</html>
