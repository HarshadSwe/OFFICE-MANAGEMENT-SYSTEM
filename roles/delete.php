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
            <h1>Delete Roles</h1>
        </div>
        <?php
        if (isset($_POST["deleteId"])) {
            $id = $_POST["deleteId"];
            echo $id;

            require_once "../includes/database.php";

            $sql = "SELECT EXISTS(SELECT * FROM users WHERE role_id = $id)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();

            $exists = $stmt->fetchColumn();

            if ($exists) {
                $_SESSION["rolesCheckDelete"] = $exists;
                header("Location: index.php");
            } else {
                echo "Data doesn't exists";

                $deleteSql = "DELETE FROM roles_and_permissions WHERE role_id = '$id'";
                $stmt = $pdo->prepare($deleteSql);
                $stmt->execute();

                $sql = "DELETE FROM roles WHERE id = '$id'";
                $stmt = $pdo->prepare($sql);
                $stmt->execute();

                $stmt = null;

                $deleteRoleAlert = 'Delete';
                
                $_SESSION["roleDeleted"] = $deleteRoleAlert;
                header("Location: index.php");
            }
        }
        ?>
    </div>

</body>

</html>