<?php

if (isset($_POST["login"])){
    $email = $_POST["email"];
    $pwd = $_POST["password"];

    require_once "db.inc.php";
    require_once "functions.inc.php";

    if (emptyLoginInput($email, $pwd) !== false)
    {
        header("location: ../signup.php?error=emptyInput");
        exit();
    }

    login($conn, $email, $pwd);
}
else 
{
    header("location: ../login.php");
    exit();
}

?>