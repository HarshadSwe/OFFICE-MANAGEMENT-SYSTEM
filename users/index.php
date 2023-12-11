<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/stylesheet/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="../assets/stylesheet/user_style.css">
    <style>
        select option {
            background-color: white;
            color: black;
        }
    </style>
    <title>users</title>
</head>

<body>
    <?php
    include("../layouts/sidemenu.php");

    if (isset($_SESSION["checkDelete"])) {
        echo '<script type="text/javascript">
        toastr.error("Delete the timelogs of a particular user first");
        </script>';

        unset($_SESSION['checkDelete']);
    } 

    if (isset($_SESSION["userDeleted"])) {
        echo '<script type="text/javascript">
        toastr.success("User has been Deleted successfully");
        </script>';

        unset($_SESSION['userDeleted']);
    }

    if (isset($_SESSION["insertUser"])) {
        echo '<script type="text/javascript">
        toastr.success("New User has been Added successfully");
        </script>';

        unset($_SESSION['insertUser']);
    }

    if (isset($_SESSION["editedUser"])) {
        echo '<script type="text/javascript">
        toastr.success("User has been edited successfully");
        </script>';

        unset($_SESSION['editedUser']);
    }

    ?>
    <div class="container">
    
        <div class="main">
            <h1 class="text-danger">Users</h1>
            <div class="row">
                <?php if (permissionCheck('user-add')) { ?>
                    <div class="col-12">
                        <button class="btn btn-dark px-4 py-2" data-bs-toggle="modal" data-bs-target="#userForm" style="float: right; margin-right: 20px;">Add Student</button>
                    </div>
                <?php } ?>
            </div>

            <div class="row">
                <div class="col-12">
                    <table class="table table-striped table-hover mt-3 text-center table-bordered full-width-table">
                        <thead>
                            <tr>
                                <th>S No</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Actions</th>
                            </tr>
                        </thead>

                        <tbody id="data">
                            <?php

                            try {
                                require_once "../includes/database.php";

                                $sql = $pdo->prepare("SELECT users.image , users.name, users.role_id ,users.id ,users.email, users.password , users.phone, users_details.address , users_details.pincode , users_details.date_of_birth , roles.role_name , roles.type FROM users INNER JOIN users_details ON users.id = users_details.user_id
                                INNER JOIN roles ON users.role_id = roles.id WHERE NOT users.id = 1");
                                $sql->execute();

                                $result = $sql->fetchAll(PDO::FETCH_ASSOC);
                                echo "<pre>";
                            } catch (PDOException $e) {
                                echo "Error" . $e->getMessage() . "<br>";
                            }
                            ?>
                            <?php foreach ($result as $key => $value) { ?>
                                <?php $id = $value['id']; ?>
                                <tr>
                                    <td><?php echo $key + 1; ?></td>
                                    <td><?php echo $value['name'] ?></td>
                                    <td><?php echo $value['email'] ?></td>
                                    <td><?php echo $value['role_name'] ?></td>
                                    <td class="d-flex justify-content-center">
                                        <?php if (permissionCheck('user-delete')) { ?>
                                            <button type="submit" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal<?php echo $value['id']; ?>"><i class="bi bi-trash"></i></button>
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
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick='$("#deleteModal").modal("toggle");'>Close</button>
                                                                <input type="hidden" name="deleteId" value="<?php echo $value['id']; ?>">
                                                                <button type="submit" class="btn btn-primary submit">Delete</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php if (permissionCheck('user-edit')) { ?>

                                            <form action="edit.php" method="post">
                                                <input type='hidden' name='roleId' value='<?php echo $value['id']; ?>'>
                                                <button type="submit" class="btn btn-success"><i class="bi bi-pencil-square"></i></button>
                                            </form>
                                        <?php } ?>

                                        <?php if (permissionCheck('user-view')) { ?>
                                        <button type="click" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#viewModal<?php echo $value['id']; ?>"><i class="bi bi-eye"></i></button>
                                        <?php } ?>
                                        <!-- view modal -->
                                        <div class="modal fade" id="viewModal<?php echo $value['id']; ?>">
                                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                                <div class="modal-content">

                                                    <div class="modal-header">
                                                        <h4 class="modal-title">View Form</h4>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>

                                                    <div class="modal-body">
                                                        <?php
                                                        require_once '../includes/database.php';
                                                        $id = $value['id'];

                                                        $sql = "SELECT * FROM users_details WHERE user_id = '$id'";
                                                        $stmt = $pdo->prepare($sql);
                                                        $stmt->execute();

                                                        $detail_result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                                        $sql = "SELECT * FROM users WHERE id = '$id'";
                                                        $stmt = $pdo->prepare($sql);
                                                        $stmt->execute();

                                                        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                                        ?>

                                                        <form id="viewForm" method="post">
                                                            <div class="inputField">
                                                                <div class="d-flex justify-content-center">
                                                                    <img src="<?php echo $result[0]['image'] ?>" alt="Profile" width="300px">
                                                                </div>
                                                                <div class="d-flex justify-content-start mx-5">
                                                                    <label for="name" class="ms-5">Name:</label>
                                                                    <label class="mx-4"><?php echo $result[0]['name'] ?></label>
                                                                </div>
                                                                <div class="d-flex justify-content-start mx-5">
                                                                    <label for="email" class="ms-5">Email:</label>
                                                                    <label class="mx-4"><?php echo $result[0]['email'] ?></label>
                                                                </div>
                                                                <div class="d-flex justify-content-start mx-5">
                                                                    <label for="number" class="ms-5">Mobile No:</label>
                                                                    <label class="mx-4"><?php echo $result[0]['phone'] ?></label>
                                                                </div>
                                                                <div class="d-flex justify-content-start mx-5">
                                                                    <label for="dob" class="ms-5">DOB:</label>
                                                                    <label class="mx-4"><?php echo $detail_result[0]['date_of_birth'] ?></label>
                                                                </div>
                                                                <div class="d-flex justify-content-start mx-5">
                                                                    <label for="address" class="ms-5">Address:</label>
                                                                    <label class="mx-4"><?php echo $detail_result[0]['address'] ?></label>
                                                                </div>
                                                                <div class="d-flex justify-content-start mx-5">
                                                                    <label for="pincode" class="ms-5">Pincode:</label>
                                                                    <label class="mx-4"><?php echo $detail_result[0]['pincode'] ?></label>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>

                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php }  ?>
                        </tbody>
                    </table>
                </div>
            </div>
            </section>

            <!-- add modal -->
            <div class="modal fade" id="userForm">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">

                        <div class="modal-header">
                            <h4 class="modal-title">Add Form</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <div class="modal-body">

                            <form action="insert.php" id="myForm" method="post" enctype="multipart/form-data">

                                <div class="inputField">
                                    <div>
                                        <label for="name">Name:</label>
                                        <input type="text" id="name" name="name" required placeholder="Name" minlength="3" maxlength="30" pattern="[^\s]+">
                                    </div>
                                    <div>
                                        <label for="email">Email:</label>
                                        <input type="email" id="email" name="email" required placeholder="Email" pattern="[^\s]+">
                                    </div>
                                    <div>
                                        <label for="register">Password:</label>
                                        <input type="password" id="password" name="password" required placeholder="password" pattern="[^\s]+">
                                    </div>
                                    <div>
                                        <label for="number">Mobile No:</label>
                                        <input type="number" id="number" name="number" required placeholder="Mobile no" pattern="[^\s]+">
                                    </div>
                                    <div>
                                        <input type="hidden" name="MAX_FILE_SIZE" value="1048576">
                                        <label for="image">Image:</label>
                                        <input type="file" name="image" required />
                                    </div>
                                    <div>
                                        <label for="dateofbirth">Date of Birth:</label>
                                        <input type='datetime-local' id="date_of_bith" name="date_of_bith" required pattern="[^\s]+">
                                    </div>
                                    <div>
                                        <label for="address">Address:</label>
                                        <input type="text" id="address" name="address" required placeholder="Address" pattern="[^\s]+">
                                    </div>
                                    <div>
                                        <label for="pincode">Pincode:</label>
                                        <input type="number" id="pincode" name="pincode" required placeholder="Pincode" pattern="[^\s]+">
                                    </div>
                                    <div>
                                        <label for="role" class="mx-4">Role:</label>
                                        <select class="form-select mx-4" name="role">
                                            <option disabled selected>Select Role</option>
                                            <?php

                                            require_once '../includes/database.php';

                                            $sql = $pdo->prepare("SELECT * FROM roles");
                                            $sql->execute();

                                            $result = $sql->fetchAll(PDO::FETCH_ASSOC);

                                            if ($result) {
                                                foreach ($result as $key => $value) {
                                                    $role_id = $value["id"];
                                                    $role_name = $value["role_name"];
                                                    echo "<option value='$role_id'> $role_name </option>";
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="modal-footer">
                            <button type="submit" form="myForm" class="btn btn-primary submit">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <script>
        $(".newUser").onclick(function() {
            $("#myForm").reset()
        })
    </script>
</body>

</html>