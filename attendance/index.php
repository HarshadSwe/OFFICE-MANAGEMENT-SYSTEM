<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="../assets/stylesheet/style.css">
    <title>Attendance</title>
</head>

<body>
    <?php
    include '../layouts/sidemenu.php';

    if (isset($_POST['month'])) {
        $selected_month = $_POST['month'];
    } else {
        $selected_month = date('m');
    }

    if (isset($_POST['year'])) {
        $selected_year = $_POST['year'];
    } else {
        $selected_year = date('Y');
    }

    $months = [
        'January' => 1,
        'February' => 2,
        'March' => 3,
        'April' => 4,
        'May' => 5,
        'June' => 6,
        'July' => 7,
        'August' => 8,
        'Septemer' => 9,
        'October' => 10,
        'November' => 11,
        'December' => 12,
    ];
    $firstYear = 2020;
    $lastYear = date('Y');
    $year = [];
    for ($i = $lastYear; $i >= $firstYear; $i--)
        array_push($year, $i)
    ?>
    <div class="container">
        <div class="main">
            <h1 class="text-danger">Attendance</h1>
            <form class="d-flex align-items-center justify-content-end" action="<?php $_SERVER['PHP_SELF']; ?>" method="post">
                <select name="month" id="month" class="px-2 py-2" onchange="form.submit()">
                    <?php foreach ($months as $key => $value) { ?>
                        <option value="<?php echo $value ?>" <?php echo $selected_month == $value ? "selected" : "" ?>><?php echo $key; ?></option>
                    <?php } ?>
                </select>
                <select name="year" id="year" class="mx-2 px-2 py-2" onchange="form.submit()">
                    <?php foreach ($year as $value) { ?>
                        <option value="<?php echo $value ?>" <?php echo $selected_year == $value ? "selected" : "" ?>><?php echo $value ?></option>
                    <?php } ?>
                </select>
            </form>
        </div>
        <!-- <div class="card"> -->
        <?php

        require_once '../includes/database.php';

        $id = $_SESSION['user_id'];

        // echo "<h3>Attendance for Month: " . date('F', mktime(0, 0, 0, $selected_month, 1)) . "</h3>";

        $findSql = "SELECT roles.type FROM roles JOIN users ON roles.id = users.role_id WHERE users.id = '$id'";

        $stmt = $pdo->prepare($findSql);
        $stmt->execute();
        $findResult = $stmt->fetchAll();

        if ($findResult[0]['type'] == 1) {
            $users = "SELECT id , name FROM users WHERE id = '$id'";
        } else {
            $users = "SELECT id , name FROM users WHERE NOT id = 1";
        }
        $stmt = $pdo->prepare($users);
        $stmt->execute();
        $usersResult = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $monthPerDay = date('t', strtotime($selected_year . '-' . $selected_month . '-' . '01'));

        ?>
        <table class="table table-striped mt-3 text-center table-bordered full-width-table">
            <thead>
                <tr>
                    <th>NAME</th>
                    <?php for ($i = 1; $i <= $monthPerDay; $i++) { ?>
                        <th><?php echo $i ?></th>
                    <?php } ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usersResult as $key => $value) { ?>
                    <tr>
                        <td class="fw-normal fs-5"><?php echo $value['name'] ?></td>
                        <?php
                        for ($j = 1; $j <= $monthPerDay; $j++) {
                            $date = $selected_year . '-' . $selected_month . '-' . $j;

                            require_once '../includes/database.php';
                            $logs_id = $value['id'];
                            if ($findResult[0]['type'] == 1) {
                                $sql = "SELECT id , clock_in_time , clock_out_time , TIMESTAMPDIFF(SECOND , clock_in_time , clock_out_time) AS total_attendance_time  FROM time_logs WHERE users_id = :logs_id and date(clock_in_time)='$date' and users_id = '$id'";
                            } else {
                                $sql = "SELECT id , users_id , clock_in_time , clock_out_time , TIMESTAMPDIFF(SECOND , clock_in_time , clock_out_time) AS total_attendance_time  FROM time_logs WHERE users_id = :logs_id and date(clock_in_time)='$date'";
                            }

                            $stmt = $pdo->prepare($sql);
                            $stmt->bindParam(':logs_id', $logs_id, PDO::PARAM_INT);
                            $stmt->execute();
                            $timelogsResult = $stmt->fetchAll(PDO::FETCH_ASSOC);

                            $modal_id = $value["id"];
                            if (isset($timelogsResult[0]["clock_in_time"])) {
                                $getTime = gmdate('H:i', $timelogsResult[0]['total_attendance_time']);
                        ?>
                                <td class="attendance-td"><a data-bs-toggle="modal" data-bs-target="#exampleModal<?php echo $modal_id . '-' . $date ?>"><?php echo $getTime ?></a></td>
                                <!-- Modal -->
                                <div class="modal fade" id="exampleModal<?php echo $modal_id . '-' . $date ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="exampleModalLabel">Attendance Details</h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <?php

                                                $sql = "SELECT users.image , users.name , roles.role_name  FROM users JOIN roles WHERE users.id = '$modal_id' AND users.role_id = roles.id";

                                                $stmt = $pdo->prepare($sql);
                                                $stmt->execute();
                                                $modelData = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                                $timeSql = "SELECT clock_in_time , clock_out_time , TIMESTAMPDIFF(SECOND , clock_in_time , clock_out_time) AS total_hours FROM time_logs WHERE users_id = '$modal_id' and date(clock_in_time)='$date'";
                                                $stmt = $pdo->prepare($timeSql);
                                                $stmt->execute();
                                                $timeData = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                                $getHours = gmdate('H:i', $timeData[0]['total_hours']);
                                                $clockOut = $timeData[0]["clock_out_time"];
                                                $clock_in_time = date('g:i A', strtotime($timeData[0]["clock_in_time"]));
                                                $clock_out_time = date('g:i A', strtotime($timeData[0]["clock_out_time"]));
                                                $getDate = date('Y-m-d', strtotime($timeData[0]["clock_in_time"]));

                                                // print_r($timeData);
                                                ?>
                                                <section class="d-flex bg-dark p-3 text-light">

                                                    <div class="person d-flex mt-1">
                                                        <img src="<?php echo $modelData[0]['image'] ?>" alt="Profile" width="130px">
                                                        <div class="mx-3 mt-1">
                                                            <h4><?php echo $modelData[0]["name"] ?></h4>
                                                            <p><?php echo $modelData[0]["role_name"] ?></p>
                                                        </div>
                                                    </div>
                                                </section>
                                                <div class="mt-4">

                                                    <div class="d-flex justify-content-around">

                                                        <div class="text-center pt-1">
                                                            <p>Date</p>
                                                            <h4><?php echo $getDate ?></h4>
                                                        </div>

                                                            <div class="text-center pt-1">
                                                                <p>Clock In</p>
                                                                <h4 class=""><?php echo $clock_in_time ?></h4>
                                                            </div>

                                                            <div class="text-center pt-1">
                                                                <p>Clock Out</p>
                                                                <h4 class=""><?php echo $clock_out_time ?></h4>
                                                            </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        <?php
                            } else {
                                echo "<td>" . '-' . "</td>";
                            }
                        }
                        ?>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <!-- </div> -->

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
        </script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

</body>

</html>