<?php

function emptySignupInput($name, $lastname, $pwd, $pwdCheck, $email, $department, $city)
{
    if (empty($name) || empty($lastname) || empty($pwd) || empty($pwdCheck) || empty($email) || empty($department) || empty($city)) {
        return true;
    } else {
        return false;
    }
}

function onlyText($input)
{
    if (!preg_match("/^[a-zA-ZÀ-ÿñÑ]+$/", $input)) {
        return true;
    } else {
        return false;
    }
}

function invalidEmail($email)
{
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return true;
    } else {
        return false;
    }
}

function checkPassword($pwd, $pwdCheck)
{
    if ($pwd !== $pwdCheck) {
        return true;
    } else {
        return false;
    }
}

function emptyLoginInput($userID, $pwd)
{
    if (empty($userID) || empty($pwd)) {
        return true;
    } else {
        return false;
    }
}

function emptyCreateExpert($virtual, $presential, $fee, $description, $tags)
{
    if ((empty($virtual) && empty($presential)) || empty($fee) || empty($description) || empty($tags)) {
        return true;
    } else {
        for ($i = 0; $i < sizeof($tags); $i++) {
            if (empty($tags[$i])) {
                return true;
            }
        }
        return false;
    }
}

function getThing($conn, $table, $data, $value)
{
    $sql = "SELECT * FROM $table WHERE $data = ?;";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../index.php?error=sqlError");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $value);
    mysqli_stmt_execute($stmt);

    $resultSQL = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($resultSQL);

    if ($row) {
        return $row;
    } else {
        return false;
    }

    mysqli_stmt_close($stmt);
}

function signUp($conn, $name, $lastname, $pwd, $email, $department, $city)
{
    $sql = "INSERT INTO `users` (`password`, `name`, `lastname`, `email`, `city`, `department`, `creation`)
            VALUES (?, ?, ?, ?, ?, ?, ?);";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: /yoteach/pages/signup/signup.php?error=sqlError");
        exit();
    }

    $creation = date("Y-m-d");
    $hashPwd = password_hash($pwd, PASSWORD_DEFAULT);

    mysqli_stmt_bind_param($stmt, "sssssss", $hashPwd, $name, $lastname, $email, $city, $department, $creation);
    mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);
}

function getTags($conn, $expert_id)
{
    $sql = "SELECT * FROM expert_tags WHERE expert_id = ?;";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../index.php?error=sqlError");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $expert_id);
    mysqli_stmt_execute($stmt);

    $resultSQL = mysqli_stmt_get_result($stmt);

    $result = array();
    while ($row = mysqli_fetch_assoc($resultSQL)) {
        $result[] = $row;
    }

    if ($result) {
        return $result;
    } else {
        return false;
    }

    mysqli_stmt_close($stmt);
}

function login($conn, $email, $pwd)
{
    $user = getThing($conn, "users", "email", $email);

    if ($user === false) {
        header("location: ../pages/login/login.php?error=noUser");
        exit();
    }

    $hashPwd = $user["password"];
    $pwdCheck = password_verify($pwd, $hashPwd);

    if ($pwdCheck === false) {
        header("location: ../pages/login/login.php?error=wrongPwd");
        exit();
    } else if ($pwdCheck === true) {
        session_start();

        $_SESSION["user_id"] = $user["user_id"];
        $_SESSION["name"] = $user["name"];
        $_SESSION["lastname"] = $user["lastname"];
        $_SESSION["email"] = $user["email"];
        $_SESSION["department"] = $user["department"];
        $_SESSION["city"] = $user["city"];

        header("location: ../index.php");
        exit();
    }
}

function createExpert($conn, $id, $presential, $virtual, $fee, $description, $tags)
{
    $sql = "INSERT INTO `experts` (`expert_id`, `fee`, `virtual`, `presential`, `description`)
    VALUES (?, ?, ?, ?, ?);";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../pages/createExpert/createExpert.php?error=sqlError");
        exit();
    }

    $p = 0;
    $v = 0;
    if ($presential == "presential") {
        $p = 1;
    }
    if ($virtual == "virtual") {
        $v = 1;
    }

    mysqli_stmt_bind_param($stmt, "iiiis", $id, $fee, $v, $p, $description);
    mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);

    for ($i = 0; $i < sizeof($tags); $i++) {
        createTag($conn, $id, $tags[$i]);
    }

    header("location: ../pages/expertProfile/expertProfile.php?error=none");
    exit();
}

function setTag($conn, $expert_id, $tag_id, $tag_name)
{
    $sql = "INSERT INTO `expert_tags` (`tag_id`, `expert_id`, `tag_name`)
            VALUES (?, ?, ?);";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../pages/createExpert/createExpert.php?error=sqlError");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "sss", $tag_id, $expert_id, $tag_name);
    mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);
}

function createTag($conn, $expert_id, $newTag)
{
    $newTag = ucfirst(strtolower($newTag));
    $tag = getThing($conn, "tags", "tag_name", $newTag);

    if ($tag) {
        setTag($conn, $expert_id, $tag["tag_id"], $tag["tag_name"]);
    } else {
        $sql = "INSERT INTO `tags` (`tag_name`)
            VALUES (?);";
        $stmt = mysqli_stmt_init($conn);

        if (!mysqli_stmt_prepare($stmt, $sql)) {
            header("location: ../pages/createExpert/createExpert.php?error=sqlError");
            exit();
        }

        mysqli_stmt_bind_param($stmt, "s", $newTag);
        mysqli_stmt_execute($stmt);

        mysqli_stmt_close($stmt);

        $tagResult = getThing($conn, "tags", "tag_name", $newTag);
        setTag($conn, $expert_id, $tagResult["tag_id"], $tagResult["tag_name"]);
    }
}

function getExpertsByTag($conn, $tag_name)
{
    $tag_name = "%$tag_name%";
    $sqlTags = "SELECT * FROM expert_tags WHERE tag_name LIKE ?;";
    $stmtTags = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmtTags, $sqlTags)) {
        header("location: ../index.php?error=sqlError");
        exit();
    }

    mysqli_stmt_bind_param($stmtTags, "s", $tag_name);
    mysqli_stmt_execute($stmtTags);

    $resultSQLTags = mysqli_stmt_get_result($stmtTags);

    $resultTags = array();
    while ($row = mysqli_fetch_assoc($resultSQLTags)) {
        $exists = false;
        foreach ($resultTags as $result) {
            if ($row["expert_id"] == $result["expert_id"]) {
                $exists = true;
                break;
            }
        }
        if (!$exists) {
            $resultTags[] = $row;
        }
    }

    if ($resultTags) {
        $resultExperts = array();
        for ($i = 0; $i < sizeof($resultTags); $i++) {
            $sqlExperts = "SELECT * FROM experts WHERE expert_id = ?;";
            $stmtExperts = mysqli_stmt_init($conn);

            if (!mysqli_stmt_prepare($stmtExperts, $sqlExperts)) {
                header("location: ../index.php?error=sqlError");
                exit();
            }

            mysqli_stmt_bind_param($stmtExperts, "s", $resultTags[$i]["expert_id"]);
            mysqli_stmt_execute($stmtExperts);

            $resultSQLExperts = mysqli_stmt_get_result($stmtExperts);

            while ($rowE = mysqli_fetch_assoc($resultSQLExperts)) {
                $resultExperts[] = $rowE;
            }

            mysqli_stmt_close($stmtExperts);
        }
        if ($resultSQLExperts) {
            return $resultExperts;
        } else {
            return false;
        }
    } else {
        return false;
    }

    mysqli_stmt_close($stmtTags);
}

function getActiveAgreement($conn, $user_id, $expert_id)
{
    $sql = "SELECT * FROM `agreements` WHERE `user_id` = ? AND `expert_id` = ? AND `state` != 2 AND `state` != 3 AND `state` != 5;";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../index.php?error=sqlError");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "ii", $user_id, $expert_id);
    mysqli_stmt_execute($stmt);

    $resultSQL = mysqli_stmt_get_result($stmt);

    $result = mysqli_fetch_assoc($resultSQL);

    if ($result) {
        return $result;
    } else {
        return false;
    }

    mysqli_stmt_close($stmt);
}

function createAgreement($conn, $user_id, $expert_id, $state, $start_date, $user_msg)
{
    $sql = "INSERT INTO `agreements` (`expert_id`, `user_id`, `state`, `start_date`, `user_msg`)
            VALUES (?, ?, ?, ?, ?);";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: /yoteach/index.php?error=sqlError");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "sssss", $expert_id, $user_id, $state, $start_date, $user_msg);
    mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);
}

function acceptAgreement($conn, $agreement_id, $new_state)
{
    $sql = "UPDATE `agreements` SET `state` = ? WHERE `agreement_id` = ?;";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../index.php?error=sqlError");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "is", $new_state, $agreement_id);
    mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);

}

function finishAgreement($conn, $agreement_id, $new_state, $finish_date)
{
    $sql = "UPDATE `agreements` SET `state` = ?, `finish_date` = ? WHERE `agreement_id` = ?;";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../index.php?error=sqlError");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "isi", $new_state, $finish_date, $agreement_id);
    mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);

}

function getNotFinishedAgreements($conn, $user_id, $state)
{
    $sql = "SELECT * FROM `agreements` WHERE (`user_id` = ? OR `expert_id` = ?) AND (`state` = ?);";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../index.php?error=sqlError");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "iii", $user_id, $user_id, $state);
    mysqli_stmt_execute($stmt);

    $resultSQL = mysqli_stmt_get_result($stmt);

    $result = array();
    while ($row = mysqli_fetch_assoc($resultSQL)) {
        $result[] = $row;
    }

    if ($result) {
        return $result;
    } else {
        return false;
    }

    mysqli_stmt_close($stmt);
}

function getFinishedAgreements($conn, $user_id)
{
    $sql = "SELECT * FROM `agreements` WHERE (`user_id` = ? OR `expert_id` = ?) AND (`state` = 2 OR `state` = 3 OR `state` = 5);";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../index.php?error=sqlError");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "ii", $user_id, $user_id);
    mysqli_stmt_execute($stmt);

    $resultSQL = mysqli_stmt_get_result($stmt);

    $result = array();
    while ($row = mysqli_fetch_assoc($resultSQL)) {
        $result[] = $row;
    }

    if ($result) {
        return $result;
    } else {
        return false;
    }

    mysqli_stmt_close($stmt);
}

function getExpertRatings($conn, $expert_id)
{
    $sql = "SELECT * FROM `ratings` WHERE expert_id = ?;";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../index.php?error=sqlError");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $expert_id);
    mysqli_stmt_execute($stmt);

    $resultSQL = mysqli_stmt_get_result($stmt);

    $result = array();
    while ($row = mysqli_fetch_assoc($resultSQL)) {
        $result[] = $row;
    }

    if ($result) {
        return $result;
    } else {
        return false;
    }

    mysqli_stmt_close($stmt);
}

function getRating($conn, $user_id, $expert_id)
{
    $sql = "SELECT * FROM `ratings` WHERE `user_id` = ? AND `expert_id` = ?;";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../index.php?error=sqlError");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "ii", $user_id, $expert_id);
    mysqli_stmt_execute($stmt);

    $resultSQL = mysqli_stmt_get_result($stmt);
    $result = mysqli_fetch_assoc($resultSQL);

    if ($result) {
        return $result;
    } else {
        return false;
    }

    mysqli_stmt_close($stmt);
}

function createRating($conn, $user_id, $expert_id, $rating)
{
    $sql = "INSERT INTO `ratings` (`user_id`, `expert_id`, `rating`)
    VALUES (?, ?, ?);";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: " . $_SERVER['HTTP_REFERER'] . "?error=sqlError");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "iii", $user_id, $expert_id, $rating);
    mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);

    updateExpertRating($conn, $expert_id);
}

function updateUserRating($conn, $rating_id, $new_rating, $expert_id)
{
    $sql = "UPDATE `ratings` SET `rating` = ? WHERE `rating_id` = ?;";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: " . $_SERVER['HTTP_REFERER'] . "?error=sqlError");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "ii", $new_rating, $rating_id);
    mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);

    updateExpertRating($conn, $expert_id);
}

function updateExpertRating($conn, $expert_id)
{
    $ratings = getExpertRatings($conn, $expert_id);

    if ($ratings) {
        $sum = 0;
        $total = sizeof($ratings);
        foreach ($ratings as $rate) {
            $sum += (int)$rate["rating"];
        }

        $new_rating = $sum / $total;

        $sql = "UPDATE `experts` SET `avg_score` = ?, `total_reviews` = ? WHERE `expert_id` = ?;";
        $stmt = mysqli_stmt_init($conn);

        if (!mysqli_stmt_prepare($stmt, $sql)) {
            header("location: ../index.php?error=sqlError");
            exit();
        }

        mysqli_stmt_bind_param($stmt, "dii", $new_rating, $total, $expert_id);
        mysqli_stmt_execute($stmt);

        mysqli_stmt_close($stmt);

        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit();
    }
}

function createReport($conn, $author, $reported, $description, $creation)
{
    $sql = "INSERT INTO `reports` (`author_user`, `reported_user`, `description`, `creation`)
            VALUES (?, ?, ?, ?);";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../index.php?error=sqlError");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "ssss", $author, $reported, $description, $creation);
    mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);
}

function getReport($conn, $author, $reported) 
{
    $sql = "SELECT * FROM `reports` WHERE `author_user` = ? AND `reported_user` = ?;";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../index.php?error=sqlError");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "ii", $author, $reported);
    mysqli_stmt_execute($stmt);

    $resultSQL = mysqli_stmt_get_result($stmt);
    $result = mysqli_fetch_assoc($resultSQL);

    if ($result) {
        return $result;
    } else {
        return false;
    }

    mysqli_stmt_close($stmt);
}

function editExpertProfile($conn, $expert_id, $new_presential, $new_virtual, $new_fee, $new_description, $new_tags) 
{
    $sql = "UPDATE `experts` SET `presential` = ?, `virtual` = ?, `fee` = ?, `description` = ? WHERE `expert_id` = ?;";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../pages/createExpert/createExpert.php?error=sqlError");
        exit();
    }

    $p = 0;
    $v = 0;
    if ($new_presential == "presential") {
        $p = 1;
    }
    if ($new_virtual == "virtual") {
        $v = 1;
    }

    mysqli_stmt_bind_param($stmt, "iiisi", $p, $v, $new_fee, $new_description, $expert_id);
    mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);

    updateTags($conn, $expert_id, $new_tags);
}

function updateTags($conn, $expert_id, $new_tags) {
    
    $sql = "DELETE FROM `expert_tags` WHERE `expert_id` = ?;";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../pages/createExpert/createExpert.php?error=sqlError");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "i", $expert_id);
    mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);

    for ($i = 0; $i < sizeof($new_tags); $i++) {
        createTag($conn, $expert_id, $new_tags[$i]);
    }

    header("location: ../pages/expertProfile/expertProfile.php?error=none");
    exit();
}

function getDepartments($conn) 
{
    $sql = "SELECT DISTINCT `department` FROM `dp_col`";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: /yoteach/pages/signup/signup.php?error=sqlError");
        exit();
    }

    mysqli_stmt_execute($stmt);

    $resultSQL = mysqli_stmt_get_result($stmt);

    $result = array();
    while ($row = mysqli_fetch_assoc($resultSQL)) {
        $result[] = $row;
    }

    if ($result) {
        return $result;
    } else {
        return false;
    }

    mysqli_stmt_close($stmt);
}

function getCityByDepartment($conn, $department) 
{
    $sql = "SELECT * FROM `dp_col` WHERE `department` = ?";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: /yoteach/pages/signup/signup.php?error=sqlError");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $department);
    mysqli_stmt_execute($stmt);

    $resultSQL = mysqli_stmt_get_result($stmt);

    $result = array();
    while ($row = mysqli_fetch_assoc($resultSQL)) {
        $result[] = $row;
    }

    if ($result) {
        return $result;
    } else {
        return false;
    }

    mysqli_stmt_close($stmt);
}