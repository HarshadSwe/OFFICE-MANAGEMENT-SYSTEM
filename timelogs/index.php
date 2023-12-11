<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="../assets/stylesheet/style.css">
    <title>time Logs</title>
</head>

<body>
    <?php
    include("../layouts/sidemenu.php");

    if (isset($_SESSION["timelogsDel"])) {
        echo '<script type="text/javascript">
        toastr.success("Timelogs has been Deleted successfully");
        </script>';

        unset($_SESSION["timelogsDel"]);
    }

    if (isset($_SESSION["timelogsEdit"])) {
        echo '<script type="text/javascript">
        toastr.success("Timelogs has been edited successfully");
        </script>';

        unset($_SESSION["timelogsEdit"]);
    }
    ?>
    <div class="container">
        <div class="main">
            <h1 class="text-danger">Time Logs</h1>
        </div>
        <div class="row pt-5">
            <div class="col-12">
                <table class="table table-striped table-hover mt-3 text-center table-bordered full-width-table">
                    <thead>
                        <tr>
                            <th>S No</th>
                            <th>Name</th>
                            <th>Log</th>
                            <th>Clock In Time</th>
                            <th>Clock Out Time</th>
                            <th>Total Hours</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        require_once "../includes/database.php";

                        $sql = $pdo->prepare("SELECT * FROM time_logs WHERE clock_out_time IS NOT NULL");

                        $sql->execute();

                        $result = $sql->fetchAll();
                        ?>
                        <?php foreach ($result as $key => $value) { ?>
                            <?php
                            $clockInTime = $value["clock_in_time"];
                            $clockOutTime = $value["clock_out_time"];
                            $datetime1 = new DateTime($clockOutTime);
                            $datetime2 = new DateTime($clockInTime);
                            $interval = $datetime1->diff($datetime2);

                            require_once "../includes/database.php";

                            $id = $value["users_id"];

                            $sql = $pdo->prepare("SELECT name from users WHERE id = '$id'");
                            $sql->execute();

                            $result = $sql->fetchAll();

                            $name = $result[0]["name"];
                            ?>
                            <tr>
                                <td><?php echo $key + 1 ?></td>
                                <td><?php echo $name ?></td>
                                <td><?php echo $value["description"] ?></td>
                                <td><?php echo $value["clock_in_time"] ?></td>
                                <td><?php echo $value["clock_out_time"] ?></td>
                                <td><?php echo $interval->format('%h') . " Hrs " . ($interval->format('%i') != 0 ? $interval->format('%i') . " Mins" : ''); ?></td>
                                <td class="d-flex justify-content-center">
                                    <?php if (permissionCheck('timelogs-delete')) { ?>

                                        <button type="submit" class="btn btn-danger  mx-2" data-bs-toggle="modal" data-bs-target="#deleteModal<?php echo $value['id']; ?>"><i class="bi bi-trash"></i></button>
                                    <?php } ?>
                                    <div class=" modal fade" id="deleteModal<?php echo $value['id']; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="deleteModal">Delete</h5>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Are you sure</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <form action="delete.php" method="post">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                        <input type="hidden" name="timelog_id" value="<?php echo $value['id']; ?>">
                                                        <button type="submit" class="btn btn-danger submit">Delete</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php if (permissionCheck('timelogs-edit')) { ?>
                                        <form action="edit.php" method="post">
                                            <input type='hidden' name='timelog_id' value='<?php echo $value['id']; ?>'>
                                            <button type="submit" class="btn btn-primary mx-2"><i class="bi bi-pencil-square"></i></button>
                                        </form>
                                    <?php } ?>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>