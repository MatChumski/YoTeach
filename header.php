<?php
session_start()
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>YoTeach</title>

    <link rel="stylesheet" href="/yoteach/css/styles.css" />
    <link rel="stylesheet" href="/yoteach/css/header.css" />

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@300;400;700;900&display=swap" rel="stylesheet">
</head>

<body class="background">
    <div class="header">
        <div>
            <a class="homeButton" href="/yoteach/index.php" style="float: left">YoTeach</a>
            <?php
            if (isset($_SESSION["user_id"])) {
                echo "<a class='headerButton' href='/yoteach/includes/logout.inc.php' style='float: right'>Log Out</a>";
                echo "<a class='headerButton' href='/yoteach/pages/profile/profile.php' style='float: right'>" . $_SESSION['name'] . "</a>";
                echo "<a class='headerButton' href='/yoteach/pages/expertProfile/expertProfile.php' style='float: right'>Expert Profile</a>";
                echo "<a class='headerButton' href='/yoteach/pages/agreements/a1.php' style='float: right'>My Agreements</a>";
            } else {
                echo "<a class='headerButton' href='/yoteach/pages/login/login.php' style='float: right'>Log In</a>";
                echo "<a class='headerButton' href='/yoteach/pages/signup/signup.php' style='float: right'>Sign Up</a>";
            }
            ?>
        </div>
    </div>