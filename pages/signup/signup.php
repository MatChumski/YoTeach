<?php

include_once "../../header.php";

?>

<?php

require_once "../../includes/db.inc.php";
require_once "../../includes/functions.inc.php";

$departments = getDepartments($conn);

?>

<div class="container">
    <div class="content">
        <div class="contentTitle">Create your Account</div>
        <form action="" method="POST" class="form">
            <input class="formInput" type="text" name="name" placeholder="Name">
            <input class="formInput" type="text" name="lastname" placeholder="Last Name">
            <input class="formInput" type="password" name="password" placeholder="Password">
            <input class="formInput" type="password" name="passwordCheck" placeholder="Confirm Password">
            <input class="formInput" type="text" name="email" placeholder="E-Mail">
            <select id='departments' class="formInput" type="text" name="department" placeholder="Department">
            <option value="" selected>Department</option>
                <?php
                foreach($departments as $dp) {
                    echo "<option id='" . $dp['department'] . "' value='" . $dp['department'] . "'>" . $dp['department'] . "</option>";
                }   
                ?>
            </select>
            <select id='cities' class="formInput" type="text" name="city" placeholder="City">
                <option value="" selected>Municipality</option>
            </select>

            <button class="formButton submit" type="submit" name="signup" formaction="/yoteach/includes/signup.inc.php">Sign Up</button>
            <button class="formButton" type="submit" name="cancel" formaction="/yoteach/index.php">Cancel</button>
        </form>
        <?php

        if (isset($_GET["error"])) {
            echo "<div style='text-align: center'>";
            if ($_GET["error"] == "emptyInput") {
                echo "<p>Please fill in all the fields<p>";
            } else if ($_GET["error"] == "invalidName") {
                echo "<p>Please use a valid name (No symbols or numbers)<p>";
            } else if ($_GET["error"] == "invalidLastname") {
                echo "<p>Please use a valid last name (No symbols or numbers)<p>";
            } else if ($_GET["error"] == "unmatchingPwd") {
                echo "<p>The passwords entered don't match<p>";
            } else if ($_GET["error"] == "invalidEmail") {
                echo "<p>Please enter a valid E-Mail direction<p>";
            } else if ($_GET["error"] == "invalidCity") {
                echo "<p>Please enter a valid city name<p>";
            } else if ($_GET["error"] == "invalidDepartment") {
                echo "<p>Please enter a valid department name<p>";
            } else if ($_GET["error"] == "takenEmail") {
                echo "<p>The email introduced is already taken<p>";
            } else if ($_GET["error"] == "sqlError") {
                echo "<p>Something went wrong, please try again<p>";
            } else if ($_GET["error"] == "none") {
                echo "<p>Signed up succesfully<p>";
            }
            echo "</div>";
        }

        ?>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

<script >

document.getElementById("departments").onchange = function() {
        console.log("enter here");
        var department = $("#departments").val();
        $.post("ajax.php",{getCityByDepartment:'getCityByDepartment', department:department},function (response) {
            var data = response.split('^');
            $("#cities").html(data[1]);
        });
    }

</script>

<?php

include_once "../../footer.php";

?>