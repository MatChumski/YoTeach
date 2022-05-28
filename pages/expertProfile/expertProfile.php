<?php

include_once "../../header.php";
include_once "../../includes/db.inc.php";
include_once "../../includes/functions.inc.php";

$expert = getThing($conn, "experts", "expert_id", $_SESSION["user_id"]);
if ($expert != false) {
    $tags = getTags($conn, $expert["expert_id"]);

    $user_name = $_SESSION["name"];
    $user_lastname = $_SESSION["lastname"];
    $user_department = $_SESSION["department"];
    $user_city = $_SESSION["city"];

    $expert_id = $expert["expert_id"];
    $avg_score = $expert["avg_score"];
    $total_reviews = $expert["total_reviews"];
    $fee = $expert["fee"];
    $virtual = $expert["virtual"];
    $presential = $expert["presential"];
    $description = $expert["description"];
}
?>
<!--  -->
<link rel="stylesheet" href="expertProfile.css" />
<div class="container">
    <?php
        if ($expert) {
            echo "<div class='expertContentTitle'>Expert Profile</div>";
            echo "<div class='expertContent'>";
            echo "<div>";

            echo "<div class='expertName'>$user_name $user_lastname</div>";

            echo "<div class='expertRating'>Rating: <div><label>$avg_score</label> out of <label>$total_reviews</label> reviews</div></div>";

            echo "<div class='expertFee'>Fee: <div>$fee/hour</div></div>";
            
            echo "<div class='expertTags'>";
            echo "Expert on:";
            echo "<div>";
            foreach ($tags as $tag) {
                echo "<div>" . $tag['tag_name'] . "</div>";
            }
            echo "</div>";
            echo "</div>";

            echo "<div class='expertService'>";
            echo "Service: ";
            if ($presential == 1) {
                echo "<div>Presential</div>";
            }
            if ($virtual == 1) {
                echo "<div>Virtual</div>";
            }
            echo "</div>";

            echo "<div class='expertLocation'>";
            echo "Located in: <div>$user_city, $user_department</div>";
            echo "</div>";

            echo "<div class='expertContentText'>";
            echo "<div class='expertLocation'>Description:</div>";
            echo $description;
            echo "</div>";

            echo "<div>";
            echo "<form action='' method='POST' style='width: 100%'>";
            echo "<button class='expertButton' formaction='editExpert.php'>Edit Info</button>";
            echo "</form>";
            echo "</div>";
        } else {
            echo "<div class='content'>";
            echo "<div class='contentTitle'>Nothing here yet</div>";
            echo "<div class='contentText'>Would you like to create your Expert Profile now?</div>";
            echo "<form action='index.php' method='POST'>";
            echo "<button class='formButton submit' formaction='/yoteach/pages/createExpert/createExpert.php'>Create</button>";
            echo "</form>";
            echo "</div>";
        }
        ?>
    </div>
</div>
</div>

<?php

include_once "../../footer.php";

?>