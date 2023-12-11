<?php
session_start();
session_destroy();
header("Location: ../login/index.php"); // Redirect to the login page after logout
?>