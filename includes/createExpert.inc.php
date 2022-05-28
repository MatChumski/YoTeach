<?php

session_start();

if (isset($_POST["create"])) {
    $virtual = $_POST["virtual"];
    $presential = $_POST["presential"];
    $fee = $_POST["fee"];
    $description = $_POST["description"];
    $tags = array();
    $id = $_SESSION["user_id"];
    foreach ($_POST["tag"] as $tag) {
        $tags[] = $tag;
    }
    
    require_once "db.inc.php";
    require_once "functions.inc.php";

    if (emptyCreateExpert($virtual, $presential, $fee, $description, $tags) !== false) {
        header("location: /yoteach/pages/createExpert/createExpert.php?error=emptyInput");
        exit();
    }
    
    foreach ($tags as $tag) {
        if (onlyText($tag)) {
            header("location: /yoteach/pages/createExpert/createExpert.php?error=invalidTag");
        }
    }

    createExpert($conn, $id, $presential, $virtual, $fee, $description, $tags);
} else {
    header("location: /yoteach/pages/createExpert/createExpert.php");
    exit();
}
