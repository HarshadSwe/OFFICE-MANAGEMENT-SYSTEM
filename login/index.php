<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../assets/stylesheet/style.css">
    <title>Login</title>
    <style>
        
    </style>
</head>

<body  class="login-wrapper">
    <div>
    <div class="login-form">
        <h1 class="login-icon">
            <i class="bi bi-box-arrow-in-right"></i>
            Login
        </h1>
        <form action="login_handler.php" method="post">
            <input type="email" id="email" name="email" placeholder="email id" pattern="[^\s]+">
            <input type="password" id="password" name="password" placeholder="Password" pattern="[^\s]+">
            <button type="submit" class="btn login-btn bg-danger" onclick="validateData()">Login</button>
        </form>
    </div>
    </div>
    <script>
        let username = document.getElementById("email");
        let password = document.getElementById("password");
        let loginbtn = document.querySelector("login-btn");

        function validateData() {
            if (username.value == "" || password.value == "") {
                alert("Both username and password are required");
            }
        }

    </script>
</body>

</html>