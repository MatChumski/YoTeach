<?php

include_once "../../header.php";
include_once "../../includes/db.inc.php";
include_once "../../includes/functions.inc.php";

$id = $_GET["expert_id"];
$expert = getThing($conn, "experts", "expert_id", $id);
if ($expert != false) {
    $user = getThing($conn, "users", "user_id", $id);
    $tags = getTags($conn, $expert["expert_id"]);
    $agrState = 0;
    if (isset($_SESSION["user_id"])) {
        $agreement = getActiveAgreement($conn, $_SESSION["user_id"], $id);

        if ($agreement != false) {
            $agrState = $agreement["state"];
            $agr_id = $agreement["agreement_id"];
        }
    }

    $name = $user["name"];
    $lastname = $user["lastname"];
    $city = $user["city"];
    $department = $user["department"];

    $expert_id = $expert["expert_id"];
    $avg_score = $expert["avg_score"];
    $total_reviews = $expert["total_reviews"];
    $fee = $expert["fee"];
    $virtual = $expert["virtual"];
    $presential = $expert["presential"];
    $description = $expert["description"];
}
?>
<link rel="stylesheet" href="expert.css" />
<script>
    function showAgreement() {
        let div = document.getElementById("msgDiv");

        div.style.display = "flex";
    }

    function hideAgreement() {
        let div = document.getElementById("msgDiv");

        div.style.display = "none";
    }
</script>

<?php

if (!empty($expert)) {
    echo "<div class='container'>";
    echo "<div class='expertContentTitle'>$name's Profile</div>";
    echo "<div class='expertContent'>";
    echo "<div class='expertName'>$name $lastname</div>";
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
    echo "Located in: <div>$city, $department</div>";
    echo "</div>";
    echo "<div class='expertFee'>Fee: <div>$fee/hour</div></div>";
    echo "<div class='expertRating'>Rating: <div><label>$avg_score</label> out of <label>$total_reviews</label> reviews</div></div>";
    echo "<div class='expertContentText'>";
    echo $description;
    echo "</div>";
    echo "<div style='width: 100%;'>";

    if (isset($_SESSION["user_id"])) {
        echo "<form action='' method='POST' style='width: 100%; margin-left: 0;'>";
        echo "<input name='expert_id' value='$expert_id' style='display: none'/>";
        echo "<input name='user_id' value='" . $_SESSION["user_id"] . "' style='display: none'/>";
        echo "<input name='place' value='" . $_SESSION["user_id"] . "' style='display: none'/>";
        if ($agrState == 0) {
            echo "<button type='button' onclick='showAgreement()' class='formButton submit' style='margin-left: 0'>Make Agreement</button>";
            echo "<div id='msgDiv' class='msgDiv'>";
            echo "<div class='expertContentText'>Write a message with your needs</div>";
            echo "<div id='chars'>0/500</div>";
            echo "<textarea id='msg' name='message' class='formInput' maxlength='500' required></textarea>";
            echo "<div>";
            echo "<button type='submit' class='formButton submit' name='make' style='margin-left: 0' formaction='/yoteach/includes/expert.inc.php'>Send</button>";
            echo "<button type='button' id='cancel' onclick='hideAgreement()' class='formButton' style='margin-left: 0'>Cancel</button>";
            echo "</div>";
            echo "</div>";
        } else if ($agrState == 1 || $agrState == 4) {
            echo "<input name='agr_id' value='$agr_id' style='display: none'/>";
            echo "<input name='current_state' value='$agrState' style='display: none'/>";
            echo "<div class='bottom'>";
            echo "<button class='formButton submit' name='cancel' style='margin-left: 0' formaction='/yoteach/includes/expert.inc.php'>Cancel Agreement</button>";
            if ($agrState == 1) {
                echo "<div>You have an agreement in process with this expert";
            } else {
                echo "<div>You have an agreement pending to be accepted by this expert";
            }
            echo "</div>";
        }
        echo "</form>";
    } else {
        echo "<div class='expertName' style='font-size: 18pt'>Log in in order to make an agreement with this expert</div>";
    }
    echo "</div>";
    echo "</div>";
    echo "</div>";
    echo "</div>";
} else {
    echo "<div class='container'>";
    echo "<div class='expertContentTitle'>Nothing here</div>";
    echo "</div>";
}
?>

<script>
    document.getElementById("msg").addEventListener("keyup", function() {
        let size = this.value.length;
        document.getElementById("chars").innerHTML = `${size}/500`;
    });
</script>


<?php

include_once "../../footer.php";

?>