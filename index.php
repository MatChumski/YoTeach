<?php

include_once "header.php";

?>

<link rel="stylesheet" href="css/search.css" />
<div class="container">
    <div class="content">
        <div class="contentTitle">Welcome</div>
        <div>
            <form action="index.php" class="form" method="GET">
                <input class="formInput" name="search" type="text" placeholder="Search Experts In..." required>
                <button type="submit" class="formButton submit">Search</button>
            </form>
        </div>
        <?php

        if (isset($_GET["search"])) {

            include_once "includes/db.inc.php";
            include_once "includes/functions.inc.php";

            $experts = getExpertsByTag($conn, $_GET["search"]);

            if ($experts) {
                echo "<div class='contentTitle' style='margin-top: 10px; border-top-style: solid'>Results for \"" . $_GET['search'] . "\"</div>";
                foreach ($experts as $expert) {
                    if (empty($_SESSION["user_id"]) || $expert["expert_id"] != $_SESSION["user_id"]) {
                        $user = getThing($conn, "users", "user_id", $expert["expert_id"]);
                        $tags = getTags($conn, $expert["expert_id"]);

                        echo "<div class='result'>";
                        echo "<div class='resultTitle'>";

                        echo "<div class='resultName'>" . $user['name'] . "</div>";
                        
                        echo "<div class='resultTags' style='color: rgb(211, 144, 0)'>";
                        echo "Score: " .  $expert['avg_score'];
                        echo "</div>";

                        echo "<div class='resultTags'>";
                        echo "Tags:";
                        foreach ($tags as $tag) {
                            echo "<div>" . $tag['tag_name'] . "</div>";
                        }
                        echo "</div>";

                        echo "<div class='resultService'>";
                        echo "Service: ";
                        if ($expert["presential"] == 1) {
                            echo "<div>Presential</div>";
                        }
                        if ($expert["virtual"] == 1) {
                            echo "<div>Virtual</div>";
                        }
                        echo "</div>";


                        echo "<div class='resultTags' style='color: green'>";
                        echo "Fee: " .  $expert['fee'] . "/hour";
                        echo "</div>";

                        echo "<div class='resultLocation'>";
                        echo "Location:";
                        echo "<div>" . $user["city"] . ", " . $user["department"] . "</div>";
                        echo "</div>";

                        echo "</div>";
                        echo "<form action='index.php' method='GET' style='width: 100%'>";
                        echo "<input style='display: none' name='expert_id' value='" . $expert["expert_id"] . "'></input>";
                        echo "<input style='display: none' name='name' value='" . $user["name"] . "'></input>";
                        echo "<button class='resultButton' name='submit' formaction='/yoteach/pages/expert/expert.php'>Check Profile</button>";
                        echo "</form>";
                        echo "</div>";
                    }
                }
            } else {
                echo "<div class='contentTitle' style='margin-top: 10px; border-top-style: solid'>Nothing found for \"" . $_GET['search'] . "\"</div>";
            }
        }

        ?>
    </div>
</div>

<?php

include_once "footer.php";

?>