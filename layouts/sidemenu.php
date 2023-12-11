<?php
session_start();
require_once "../includes/database.php";
function permissionCheck($permission_name)
{
    global $pdo;

    $user_id = $_SESSION["user_id"];

    $sql = "SELECT users.role_id , roles.role_name , roles.type FROM users JOIN roles ON users.role_id = roles.id WHERE users.id = '$user_id'";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $get_role_id = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $role_id = $get_role_id[0]["role_id"];

    if ($role_id == 1)
        return true;
    $permissionSql = "SELECT roles_and_permissions.permission_id , permissions.name FROM roles_and_permissions JOIN permissions ON roles_and_permissions.permission_id = permissions.id WHERE roles_and_permissions.role_id = '$role_id'";

    $stmt = $pdo->prepare($permissionSql);
    $stmt->execute();

    $myres = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // echo "<pre>";
    // print_r($myres);
    foreach ($myres as $res) {
        if ($res['name'] == $permission_name)
            return true;
    }
    return false;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- Toastr -->
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
    <link rel="stylesheet" href="../assets/stylesheet/style.css">
</head>

<body>
    <?php
    // Get the current URL
    $currentUrl = $_SERVER['REQUEST_URI'];

    if (!isset($_SESSION['user_id'])) {
        header("Location: ../logout/logout.php");
        exit;
    }

    $restrictionAlert = "alert";

    if (!permissionCheck('attendance') && strpos($currentUrl, 'attendance') !== false) {
        $_SESSION["restriction"] = $restrictionAlert;
        header('Location: ../dashboard/index.php');
    }

    if (!permissionCheck('timelogs') && strpos($currentUrl, 'timelogs') !== false) {
        $_SESSION["restriction"] = $restrictionAlert;
        header('Location: ../dashboard/index.php');
        exit;
    }

    if (!permissionCheck('roles') && strpos($currentUrl, 'roles') !== false) {
        $_SESSION["restriction"] = $restrictionAlert;
        header('Location: ../dashboard/index.php');
        exit;
    }

    if (!permissionCheck('users') && strpos($currentUrl, 'users') !== false) {
        $_SESSION["restriction"] = $restrictionAlert;
        header('Location: ../dashboard/index.php');
        exit;
    }
    ?>
    <div class="sidenav">
        <a href="../dashboard/index.php" class="<?php echo strpos($currentUrl, 'dashboard') !== false ? 'active' : ''; ?>"><i class="bi bi-house-check text-danger fs-3 px-2"></i>Dashboard</a>
        <?php if (permissionCheck('attendance')) { ?>
            <a href="../attendance/index.php" class="<?php echo strpos($currentUrl, 'attendance') !== false ? 'active' : ''; ?>"><i class="bi bi-list-check text-danger fs-3 px-2"></i>Attendance</a>
        <?php } ?>
        <?php if (permissionCheck('timelogs')) { ?>
            <a href="../timelogs/index.php" class="<?php echo strpos($currentUrl, 'timelogs') !== false ? 'active' : ''; ?>"><i class="bi bi-clock text-danger fs-3 px-2"></i>Time Logs</a>
        <?php } ?>
        <?php if (permissionCheck('roles')) { ?>
            <a href="../roles/index.php" class="<?php echo strpos($currentUrl, 'roles') !== false ? 'active' : ''; ?>"><i class="bi bi-controller text-danger fs-3 px-2"></i>Roles</a>
        <?php } ?>
        <?php if (permissionCheck('users')) { ?>
            <a href="../users/index.php" class="<?php echo strpos($currentUrl, 'users') !== false ? 'active' : ''; ?>"><i class="bi bi-people text-danger fs-3 px-2"></i>Users</a>
        <?php } ?>
        <a class="nav-logout fixed-bottom mr-5 px-2 py-2 my-3 bg-white text-light" href="../logout/logout.php">
            <span class="d-flex align-items-center"><i class="bi bi-person-x fs-3 px-2 text-danger"></i><span class="text-dark mx-4">Logout</span></span>
        </a>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
</body>

</html>