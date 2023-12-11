<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="../assets/stylesheet/style.css">
    <title>roles and permissions</title>
</head>

<body>

    <?php
    include("../layouts/sidemenu.php");

    if (isset($_SESSION["rolesCheckDelete"])) {
        echo '<script type="text/javascript">
        toastr.error("First delete the users thats assigned to the particular role");
        </script>';

        unset($_SESSION["rolesCheckDelete"]);
    }

    if (isset($_SESSION["roleDeleted"])) {
        echo '<script type="text/javascript">
        toastr.success("Role has been deleted successfully");
        </script>';

        unset($_SESSION["roleDeleted"]);
    }

    if (isset($_SESSION["insertRole"])) {
        echo '<script type="text/javascript">
        toastr.success("Role has been added successfully");
        </script>';

        unset($_SESSION["insertRole"]);

    }

    if (isset($_SESSION["editedRole"])) {
        echo '<script type="text/javascript">
        toastr.success("Role has been edited successfully");
        </script>';

        unset($_SESSION["editedRole"]);
    }
    ?>
    <div class="container">
        <div class="main">
            <h1 class="text-danger">Roles and permissions</h1>

            <?php if (permissionCheck('roles-add')) { ?>
                <div class="float-end">
                    <a class="btn btn-dark px-4 py-2 mx-4" href="insert.php" role="button">Add Roles</a>
                </div>
            <?php } ?>
        </div>
        <div class="row mt-4">
            <div class="col-12 mt-4">
                <table class="table table-striped table-hover mt-4 text-center table-bordered full-width-table">
                    <thead>
                        <tr>
                            <th>S No</th>
                            <th>Role Name</th>
                            <th>Type</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody id="data">
                        <?php
                        try {
                            require_once "../includes/database.php";
                            $sql = $pdo->prepare("SELECT id, role_name , type FROM roles WHERE NOT id = 1");
                            $sql->execute();
                            $result = $sql->fetchAll(PDO::FETCH_ASSOC);
                        } catch (PDOException $e) {
                            echo "Error" . $e->getMessage() . "<br>";
                        }
                        ?>
                        <?php foreach ($result as $key => $value) { ?>
                            <tr>
                                <td><?php echo $key + 1; ?></td>
                                <td><?php echo $value['role_name']; ?></td>
                                <td><?php echo ($value['type'] == 0 ? 'Staff' : 'Employee'); ?></td>
                                <td class='d-flex justify-content-center'>
                                    <?php if (permissionCheck('roles-delete')) { ?>
                                        <button type="click" class='btn btn-danger btn-delete' data-bs-toggle="modal" data-bs-target="#myModal<?php echo $value['id']; ?>"><i class="bi bi-trash"></i></button>
                                    <?php } ?>

                                    <div class="modal fade" id="myModal<?php echo $value['id']; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Delete</h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Are you Sure</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <form action="delete.php" method="POST">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                        <input type="hidden" name="deleteId" value="<?php echo $value['id']; ?>">
                                                        <button type="submit" class="btn btn-danger">Delete</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <?php if (permissionCheck('roles-edit')) { ?>
                                        <form action='edit.php' method='post'>
                                            <input type='hidden' name='roleId' value='<?php echo $value['id']; ?>'>
                                            <button type='submit' class='btn btn-primary editbtn ms-2'><i class="bi bi-pencil-square"></i></button>
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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

</body>

</html>