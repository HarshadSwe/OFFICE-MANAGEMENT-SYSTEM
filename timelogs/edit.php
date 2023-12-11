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
            <h1 class="text-danger">Edit TimeLogs</h1>
        </div>
        <?php
        if (isset($_POST["timelog_id"])) {
            $id = $_POST["timelog_id"];

            try {
                require_once "../includes/database.php";

                $sql = "SELECT time_logs.*, users.name from time_logs LEFT JOIN users ON time_logs.users_id = users.id WHERE time_logs.id = '$id'";
                $stmt = $pdo->prepare($sql);
                $stmt->execute();

                $logResult = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $user_id = $logResult[0]["users_id"];
                $userName = $logResult[0]["name"];
                $description = $logResult[0]["description"];
                $clock_in_time = $logResult[0]["clock_in_time"];
                $clock_out_time = $logResult[0]["clock_out_time"];

                // $sql = "SELECT name FROM users WHERE id = $user_id";

                // $stmt = $pdo->prepare($sql);
                // $stmt->execute();

                // $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                // $userName = $result[0]["name"];

                // echo"<pre>";
                // print_r($logResult[0]);
                // die;
            } catch (Exception $e) {
                echo "Error :" . $e->getMessage() . "";
            }
        }
        ?>
        <div class="card p-5  shadow">
        <form action="<?php $_SERVER["PHP_SELF"] ?>" method="post">

            <div class='row g-3 align-items-center'>
                <input type='hidden' name='id' id='id' value='<?php echo $id ?>'>
                <div class="d-flex">
                    <label for='' class='form-label'>Name :</label>
                    <p><?php echo isset($userName) ? $userName : '' ?></p>
                </div>
                <div class='col-auto'>
                    <label for='' class='form-label'>Description:</label>
                    <input type='text' class='form-control mb-4' id='description' name='description' required placeholder='Description' value='<?php echo  $description ?>'>
                </div>
                <div class='col-auto'>
                    <label for='' class='form-label'>Clock In Tme:</label>
                    <input type='datetime-local' class='form-control mb-4' id='clock_in_time' name='clock_in_time' required placeholder='Clock in time' value='<?php echo  $clock_in_time ?>'>
                </div>
                <div class='col-auto'>
                    <label for='' class='form-label'>Clock In Tme:</label>
                    <input type='datetime-local' class='form-control mb-4' id='clock_out_time' name='clock_out_time' required placeholder='Clock out time' value='<?php echo  $clock_out_time ?>'>
                </div>
                <div>
                    <button type="submit" class="btn btn-dark">Update</button>
                </div>
            </div>
        </form>
        </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

</html>

<?php

if (!isset($_POST["timelog_id"]) && $_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST["id"];
    $description = $_POST["description"];
    $clock_in_time = date('Y-m-d H:i:s',strtotime($_POST["clock_in_time"]));
    $clock_out_time = date('Y-m-d H:i:s',strtotime($_POST["clock_out_time"]));

    try {
        require_once "../includes/database.php";

        $sql = "UPDATE `time_logs` SET `description` = '$description' , `clock_in_time` = '$clock_in_time' , `clock_out_time` =  '$clock_out_time' WHERE `id` = '$id'";

        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $timelogsEdit = 'edit';

        $_SESSION["timelogsEdit"] = $timelogsEdit;
        echo "
        <script>
            window.location.href = 'index.php'
        </script>
    ";
    } catch (PDOException $e) {
        echo "Error :" . $e->getMessage();
    }
}
?>