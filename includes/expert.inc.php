<?php

session_start();

if (isset($_SESSION["user_id"])) {

    if (isset($_POST["make"])) {
        $user_id = $_POST["user_id"];
        $expert_id = $_POST["expert_id"];
        $message = $_POST["message"];

        require_once "db.inc.php";
        require_once "functions.inc.php";

        $state = 4;
        $start_date = date("Y-m-d");

        createAgreement($conn, $user_id, $expert_id, $state, $start_date, $message);

        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit();
    } else if (isset($_POST["cancel"])) {
        $agr_id = $_POST["agr_id"];
        $current_state = $_POST["current_state"];

        $finish_date = date("Y-m-d");
        $new_state;
        if ($current_state == "1") {
            $new_state = 3;
        } elseif ($current_state == "4") {
            $new_state = 5;
        }

        require_once "db.inc.php";
        require_once "functions.inc.php";

        finishAgreement($conn, $agr_id, $new_state, $finish_date);

        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit();
    } else {
        header("location: /yoteach/index.php");
        exit();
    }
} else {
    header("location: /yoteach/index.php?error=noUser");
    exit();
}
