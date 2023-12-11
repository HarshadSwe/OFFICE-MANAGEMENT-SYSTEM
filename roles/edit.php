<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/stylesheet/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <title>Edit Roles</title>
</head>

<body>
    <?php
    include("../layouts/sidemenu.php");
    ?>

    <div class="container">
        <div class="main mb-4">
            <h1 class="text-danger">Edit Roles</h1>
        </div>
        <?php
        if (isset($_POST["roleId"])) {
            $id = $_POST['roleId'];
            try {
                require_once "../includes/database.php";

                $sql = "SELECT * FROM permissions";
                $stmt = $pdo->prepare($sql);
                $stmt->execute();

                $permissonData = $stmt->fetchAll(PDO::FETCH_ASSOC);

                $sql = "SELECT * FROM roles WHERE id = $id";
                $stmt = $pdo->prepare($sql);
                $stmt->execute();

                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                $rpCheck = $pdo->prepare("SELECT permission_id FROM roles_and_permissions WHERE role_id = '$id'");
                $rpCheck->execute();

                $rpResult = $rpCheck->fetchAll(PDO::FETCH_ASSOC);
                $rpResult = array_column($rpResult, 'permission_id');
                // echo "<pre>";
                // print_r($rpResult);
            } catch (Exception $e) {
                echo "Error :" . $e->getMessage() . "";
            }
        }
        ?>
        <div class="card p-5  shadow">
            <form action="<?php $_SERVER["PHP_SELF"] ?>" method="post">
                <div class='row g-3 align-items-center'>
                    <input type='hidden' name='id' value='<?php echo $result[0]['id']; ?>'>
                    <div class='col-auto'>
                        <label for='name' class='form-label'>Role Name</label>
                        <input type='text' class='form-control mb-4' id='role_name' name='role_name' required placeholder='Role Name' minlength='3' value="<?php echo $result[0]['role_name']; ?>">
                    </div>
                    <div class='col-auto'>
                        <label for='name' class='form-label'>Select Type</label>
                        <select class='form-select mb-4' aria-label='Large select example' name='type'>
                            <option selected disabled>Type</option>
                            <option value='0' <?php echo ($result[0]['type'] == 0 ? 'selected' : '') ?>>Staff</option>
                            <option value='1' <?php echo ($result[0]['type'] == 1 ? 'selected' : '') ?>>Employee</option>
                        </select>
                    </div>
                    <div class="col-auto">
                        <div class="dropdown mt-2">
                            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Select Permissions
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <?php foreach ($permissonData as $data) { ?>
                                    <div class="dropdown-item">
                                        <?php if (in_array($data["id"], $rpResult)) { ?>
                                            <input type="checkbox" id="options" name="options[]" checked value="<?php echo $data["id"] ?>">
                                        <?php } else { ?>
                                            <input type="checkbox" id="options" name="options[]" value="<?php echo $data["id"] ?>">
                                        <?php } ?>
                                        <label for="option"><?php echo $data["description"] ?></label>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <button type="submit" class="btn btn-dark">Update</button>
                </div>
            </form>
        </div>
    </div>
    <?php
    if (!isset($_POST["roleId"]) && $_SERVER["REQUEST_METHOD"] == "POST") {
        $id = $_POST["id"];
        $role_name = $_POST["role_name"];
        $type = $_POST["type"];
        $permissions = $_POST["options"];

        try {
            require_once "../includes/database.php";

            $sql = "UPDATE roles SET role_name = '$role_name', type = '$type' WHERE id = $id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();

            $deleteSql = "DELETE FROM roles_and_permissions WHERE role_id = $id";
            $deleteStmt = $pdo->prepare($deleteSql);
            $deleteStmt->execute();

            foreach ($permissions as $data) {
                $rpSql = "INSERT INTO roles_and_permissions (role_id, permission_id) VALUES ($id, '$data')";
                $rpStmt = $pdo->prepare($rpSql);
                $rpStmt->execute();
            }

            $pdo = null;
            $stmt = null;
            $rpStmt = null;
            $editRole = 'edit';

            $_SESSION["editedRole"] = $editRole;
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
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>

</html>