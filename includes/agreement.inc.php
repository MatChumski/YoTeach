<?php

require_once "db.inc.php";
require_once "functions.inc.php";

if (isset($_GET["accept"])) {
    $id = $_GET["id"];    

    $new_state = 1;

    acceptAgreement($conn, $id, $new_state);    

    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit();
} else if (isset($_GET["cancel"])) {
    $id = $_GET["id"];
    $current_state = $_GET["current_state"];

    if ($current_state == "1"){
        $new_state = "3";
    } else if ($current_state == "4") {
        $new_state = "5";
    }

    $finish_date = date("Y-m-d");

    finishAgreement($conn, $id, $new_state, $finish_date);

    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit();    
} else if (isset($_GET["rating"])){
    $user_id = $_GET["user_id"];
    $expert_id = $_GET["expert_id"];
    $new_rating = $_GET["new_rating"];

    $cur_rating = getRating($conn, $user_id, $expert_id);

    if ($cur_rating) {
        updateUserRating($conn, $cur_rating['rating_id'], $new_rating, $expert_id);
    } else {
        createRating($conn, $user_id, $expert_id, $new_rating);
    }
    header("Location: " . $_SERVER['HTTP_REFERER'] . "?error=noneRate");
    exit();
} else if (isset($_GET["complete"])){
    $id = $_GET["id"];
    $new_state = "2";

    $finish_date = date("Y-m-d");

    finishAgreement($conn, $id, $new_state, $finish_date);

    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit();
} else if (isset($_GET["report"])){
    $reported = $_GET["reported_id"];
    $author = $_GET["reporting_id"];
    $description = $_GET["reportMessage"];

    $creation = date("Y-m-d");

    createReport($conn, $author, $reported, $description, $creation);
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit();
} else {
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit();
}