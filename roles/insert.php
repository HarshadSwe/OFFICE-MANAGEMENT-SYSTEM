<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <link rel="stylesheet" href="../assets/stylesheet/style.css">
    <title>Add Roles</title>
</head>

<body>
    <?php
    include("../layouts/sidemenu.php");

    require_once "../includes/database.php";

    $sql = "SELECT * FROM permissions";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $permissonData = $stmt->fetchAll(PDO::FETCH_ASSOC);

    ?>
    <div class="container">
        <div class="main mb-4">
                <h1 class="text-danger">Add Roles</h1>
        </div>
        <form method="post">
            <div class="row g-3 align-items-center">
                <div class="col-auto">
                    <label for="name" class="form-label">Role Name</label>
                    <input type="text" class="form-control mb-4" id="role_name" name="role_name" required placeholder="Role Name" minlength="3">
                </div>
                <div class="col-auto">
                    <label for="name" class="form-label">Select Type</label>
                    <select class="form-select mb-4" aria-label="Large select example" name="type">
                        <option selected disabled>Type</option>
                        <option value="0">Staff</option>
                        <option value="1">Employee</option>
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
                                    <input type="checkbox" id="option" name="options[]" value="<?php echo $data["id"] ?>">
                                    <label for="option"><?php echo $data["description"] ?></label>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <div>
                    <button type="submit" class="btn btn-dark px-4 py-2">Submit</button>
                </div>
        </form>
    </div>
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $role_name = $_POST["role_name"];
        $type = $_POST["type"];
        $permissions = $_POST["options"];

        print_r($permissions);

        try {
            require_once "../includes/database.php";

            $sql = "INSERT INTO roles (role_name , type) VALUES (? , ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$role_name, $type]);

            $role_id = $pdo->lastInsertId();

            echo $role_id;

            foreach ($permissions as $data) {
                $rpSql = "INSERT INTO roles_and_permissions (role_id , permission_id) VALUES (? , ?)";
                $stmt = $pdo->prepare($rpSql);
                $stmt->execute([$role_id, $data]);
            }

            $pdo = null;
            $stmt = null;
            $roleAdd = 'insert';

            $_SESSION["insertRole"] = $roleAdd;
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