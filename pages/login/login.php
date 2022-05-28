<?php

include_once "../../header.php";

?>

<div class="container">
    <div name="login" class="content">
        <div class="contentTitle">Log In</div>
        <form action="index.php" method="POST" class="form">
            <input class="formInput" type="text" name="email" placeholder="E-Mail"/>
            <input class="formInput" type="password" name="password" placeholder="Password"/>
            
            <button class="formButton submit" type="submit" name="login" formaction="/yoteach/includes/login.inc.php">Log in</button>
            <button class="formButton" type="submit" name="cancel" formaction="/yoteach/index.php">Cancel</button>

        </form>
        <?php
        if (isset($_GET["error"])) {
            echo "<div style='text-align: center'>";
            if ($_GET["error"] == "emptyInput") {
                echo "<p>Please fill in all the fields<p>";
            } else if ($_GET["error"] == "noUser") {
                echo "<p>User not found<p>";
            } else if ($_GET["error"] == "wrongPwd") {
                echo "<p>Wrong Password<p>";
            }
            echo "</div>";
        }
        ?>
    </div>
</div>

<?php

include_once "../../footer.php";

?>