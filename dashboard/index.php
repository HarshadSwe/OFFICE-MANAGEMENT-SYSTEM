<?php

include("../layouts/sidemenu.php");

require_once "../includes/database.php";

if (isset($_SESSION["restriction"])) {
    echo '<script type="text/javascript">
        toastr.warning("You have no permission to access that page");
        </script>';

    unset($_SESSION['restriction']);
}

$id = $_SESSION["user_id"];
$sql = "SELECT users.id , users.name , users.image , users.role_id , roles.id , roles.type  , roles.role_name FROM users JOIN roles ON users.role_id = roles.id WHERE users.id = $id";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$userResult = $stmt->fetchAll(PDO::FETCH_ASSOC);
$user = $userResult[0];

$sql = "SELECT clock_in_time FROM time_logs WHERE users_id = '$id' AND clock_out_time IS NULL";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$attendanceResult = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="../assets/stylesheet/style.css">
    <title>Dashboard</title>
</head>

<body class="dashboard-wrapper">
    <div class="main-btns mx-5">
        <?php if ($user['type'] == 1) { ?>
            <?php if (!isset($attendanceResult[0])) { ?>
                <form action="clockin_clockout.php" method="post">
                    <button type="submit" class="mx-4 px-4 py-2 btn btn-dark btn-lg clock-btn"><i class="bi bi-house-door-fill"></i>Clock In</button>
                </form>
            <?php } else { ?>
                <form action="clockin_clockout.php" method="post">
                    <button type="button" class="mx-4 px-4 py-2 btn btn-dark btn-lg clock-btn" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="bi bi-house-door-fill"></i>Clock Out</button>
                    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Today Logs</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <textarea name="description" cols="55" rows="3" required></textarea>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Save</button>
                                </div>
                            </div>

                        </div>
                    </div>
                </form>
            <?php } ?>
        <?php } ?>
    </div>

    <div class="container">
        <div class="main">
            <div>
                <h1 class="text-danger">Welcome <?php echo $user['name'] ?></h1>
                <section class="mt-5 d-flex bg-dark p-3 text-light">
                    <img src="<?php echo $user['image'] ?>" alt="image" width="200px">
                    <div class="mt-4 mx-3">
                        <h3><?php echo $user['name'] ?></h3>
                        <h5 class="text-secondary"><?php echo $user['role_name'] ?></h5>
                    </div>
                </section>
                <!-- <div class="card mt-5 mx-3">
                    <div class="card-header">
                        <span class="text-success-emphasis">
                            Project Info
                        </span>
                    </div>
                    <div class="card-body">
                        <blockquote class="blockquote mb-0">
                            <h5>Full Stack Proman Clone</h5>
                            <p class="text-secondary">HTML , CSS , JS , MYSQL , PHP</p>
                            <footer class="blockquote-footer mx-2">harshad</footer>
                        </blockquote>
                    </div>
                </div> -->
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>