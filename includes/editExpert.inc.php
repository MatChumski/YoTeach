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
        header("location: /yoteach/pages/expertProfile/editExpert.php?error=emptyInput");
        exit();
    }
    
    for ($i = 0; $i < sizeof($tags); $i++) {
        if (onlyText($tags[$i])) {
            header("location: /yoteach/pages/expertProfile/editExpert.php?error=invalidTag");
        }
    }

    editExpertProfile($conn, $id, $presential, $virtual, $fee, $description, $tags);
    header("location: /yoteach/pages/expertProfile/expertProfile.php?error=none");
} else {
    header("location: /yoteach/pages/expertProfile/expertProfile.php");
    exit();
}
