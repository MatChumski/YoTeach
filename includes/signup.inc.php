<?php

if (isset($_POST["signup"]))
{
    $name = $_POST["name"];
    $lastname = $_POST["lastname"];
    $pwd = $_POST["password"];
    $pwdCheck = $_POST["passwordCheck"];

    $email = $_POST["email"];
    $department = $_POST["department"];
    $city = $_POST["city"];

    require_once "db.inc.php";
    require_once "functions.inc.php";

    if (emptySignupInput($name, $lastname, $pwd, $pwdCheck, $email, $department, $city) !== false)
    {
        header("location: /yoteach/pages/signup/signup.php?error=emptyInput");
        exit();
    }

    if (onlyText($name) !== false) 
    {        
        header("location: /yoteach/pages/signup/signup.php?error=invalidName");
        exit();
    }

    if (onlyText($lastname) !== false) 
    {
        header("location: /yoteach/pages/signup/signup.php?error=invalidLastname");
        exit();
    }

    if (checkPassword($pwd, $pwdCheck))
    {
        header("location: /yoteach/pages/signup/signup.php?error=unmatchingPwd");
        exit();
    }

    if (invalidEmail($email))
    {
        header("location: /yoteach/pages/signup/signup.php?error=invalidEmail");
        exit();
    }

    if (onlyText($city) !== false) 
    {
        header("location: /yoteach/pages/signup/signup.php?error=invalidCity");
        exit();
    }
    
    if (onlyText($department) !== false) 
    {
        header("location: /yoteach/pages/signup/signup.php?error=invalidDepartment");
        exit();
    }
    
    if (getThing($conn, "users", "email", $email))
    {
        header("location: /yoteach/pages/signup/signup.php?error=takenEmail");
        exit();
    }
    
    signUp($conn, $name, $lastname, $pwd, $email, $department, $city);

    header("location: /yoteach/pages/signup/signup.php");
    exit();
} 
else 
{
    header("location: /yoteach/pages/signup/signup.php");
    exit();
}