<?php

include_once "../../header.php";

?>

<link rel="stylesheet" href="profile.css" />

<div class="container">
    <div class="content">
        <div>
            <div class="contentTitle">Your Profile</div>
            <div class="tab">Name: <div> <?php echo $_SESSION["name"] . " " . $_SESSION["lastname"] ?></div>
            </div>
            <div class="tab">Email: <div> <?php echo $_SESSION["email"] ?></div>
            </div>
            <div class="tab">Location: <div> <?php echo $_SESSION['city'] . ", " . $_SESSION['department'] ?></div>
        </div>
    </div>
</div>

<?php

include_once "../../footer.php";

?>