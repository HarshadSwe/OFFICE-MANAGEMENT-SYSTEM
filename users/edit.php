<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="../assets/stylesheet/style.css">
    <title>Edit Users</title>
</head>

<body>
    <?php
    include("../layouts/sidemenu.php");
    ?>

    <div class="container">
        <div class="main mb-4">
            <h1 class="text-danger">Edit Users</h1>
        </div>
        <?php
        if (isset($_POST["roleId"])) {
            $id = $_POST['roleId'];
            try {
                require_once "../includes/database.php";

                $sql = "SELECT users.*, users_details.address, users_details.date_of_birth , users_details.pincode FROM users LEFT JOIN users_details ON users.id = users_details.user_id WHERE users.id = $id";
                $stmt = $pdo->prepare($sql);
                $stmt->execute();

                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                // echo "<pre>";
                // print_r($result[0]['date_of_birth']);

                $rolesSql = $pdo->prepare("SELECT * FROM roles");
                $rolesSql->execute();

                $rolesResult = $rolesSql->fetchAll(PDO::FETCH_ASSOC);
            } catch (Exception $e) {
                echo "Error :" . $e->getMessage() . "";
            }
        }
        ?>
        <div class="card p-5  shadow">
        <form action="<?php $_SERVER["PHP_SELF"] ?>" method='post'>

            <div class='row g-3 align-items-center'>

                <input type='hidden' name='id' id='id' value='<?php echo $result[0]['id']; ?>'>

                <div class='col-auto'>
                    <label for='name' class='form-label'>Name:</label>
                    <input type='text' class='form-control mb-4' id='name' name='name' required placeholder='Name' minlength='3' maxlength='30' pattern='[^\s]+' value='<?php echo $result[0]['name']; ?>'>
                </div>
                <div class='col-auto'>
                    <label for='email' class='form-label'>Email:</label>
                    <input type='email' class='form-control mb-4' id='email' name='email' required placeholder='Email' pattern='[^\s]+' value='<?php echo $result[0]['email']; ?>'>
                </div>
                <div class='col-auto'>
                    <label for='password' class='form-label'>Password:</label>
                    <input type='password' class='form-control mb-4' id='password' name='password' required placeholder='password' pattern='[^\s]+' value='<?php echo $result[0]['password']; ?>'>
                </div>
                <div class='col-auto'>
                    <label for='number' class='form-label'>Mobile No:</label>
                    <input type='number' class='form-control mb-4' id='phone' name='phone' required placeholder='Mobile no' pattern='[^\s]+' value='<?php echo $result[0]['phone']; ?>'>
                </div>
                <div class='col-auto'>
                    <label for='date_of_birth' class='form-label'>Date of Birth:</label>
                    <?php $formattedDateOfBirth = date('Y-m-d\TH:i', strtotime($result[0]['date_of_birth'])); ?>
                    <input type='datetime-local' class='form-control mb-4' id='date_of_birth' name='date_of_birth' required pattern='[^\s]+' value='<?php echo $formattedDateOfBirth; ?>'>
                </div>
                <div class='col-auto'>
                    <label for='address' class='form-label'>Address:</label>
                    <input type='text' class='form-control mb-4' id='address' name='address' required placeholder='Address' pattern='[^\s]+' value='<?php echo $result[0]['address']; ?>'>
                </div>
                <div class='col-auto'>
                    <label for='pincode' class='form-label'>Pincode:</label>
                    <input type='number' class='form-control mb-4' id='pincode' name='pincode' required placeholder='Pincode' pattern='[^\s]+' value='<?php echo $result[0]['pincode']; ?>'>
                </div>
                <div class='col-auto'>
                    <label for="role" class="mx-4" class='form-label'>Role:</label>
                    <select class="form-select mx-4 form-control mb-4" name="role_id">
                        <option disabled selected>Select Role</option>
                        <?php foreach ($rolesResult as $key => $value) {
                            $role_id = $value["id"];
                            $role_name = $value["role_name"]; ?>
                            <option value='<?php echo $role_id ?>' <?php echo ($result[0]['role_id'] == $role_id ? 'selected' : '') ?>><?php echo $role_name ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div>
                    <button type="submit" class="btn btn-dark">Update</button>
                </div>
            </div>
        </form>
        </div>
    </div>
    </div>

</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

</html>
<?php
if (!isset($_POST["roleId"]) && $_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST["id"];
    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $phone = $_POST["phone"];
    $address = $_POST["address"];
    $pincode = $_POST["pincode"];
    $role_id = $_POST["role_id"];
    $date_of_birth = $_POST["date_of_birth"];

    try {
        require_once "../includes/database.php";
        $sql = "UPDATE users LEFT JOIN users_details ON users.id = users_details.user_id SET users.name = '$name', users.email = '$email', users.password = '$password', users.phone = '$phone', users.role_id = '$role_id', users_details.address = '$address', users_details.pincode = '$pincode', users_details.date_of_birth = '$date_of_birth' WHERE users.id = $id;";

        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        $editAlert = "edit";
        $_SESSION["editedUser"] = $editAlert;
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