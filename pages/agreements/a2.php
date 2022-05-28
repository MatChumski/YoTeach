<?php

include_once "../../header.php";

?>

<?php

if (isset($_SESSION["user_id"])) {

    require_once "../../includes/db.inc.php";
    require_once "../../includes/functions.inc.php";

    $user_id = $_SESSION["user_id"];

    $agreements = getNotFinishedAgreements($conn, $user_id, "4");

    $exists = false;
    if ($agreements) {
        foreach ($agreements as $agr) {
            if ($agr["user_id"] == $user_id) {
                $exists = true;
            }
        }
    }
}
?>

<link rel="stylesheet" href="/yoteach/pages/agreements/agreements.css" />
<div class="container plus">
    <div class="content">
        <div class='contentTitle'>Your Agreements</div>
        <div class='menu'>
            <div class='sidebar'>
                <div>
                    <a href="a1.php" class='sidebarButton'>Active</a>
                    <a href="a2.php" class='sidebarButton active'>Pending</a>
                    <a href="a3.php" class='sidebarButton'>Requests</a>
                    <a href="a4.php" class='sidebarButton'>Finished</a>
                </div>
                <!-- <form action='/yoteach/index.php' method='POST'>
                    <button type='submit' formaction='a1.php' class='sidebarButton'>Active</button>
                    <button type='submit' formaction='a2.php' class='sidebarButton active'>Pending</button>
                    <button type='submit' formaction='a3.php' class='sidebarButton'>Requests</button>
                    <button type='submit' formaction='a4.php' class='sidebarButton'>Finished</button>
                </form> -->
            </div>
            <?php
            if ($agreements && $exists) {
                echo "<div class='table'>";

                echo "<div class='col'>";
                echo "<div class='colTitle'>Agreement ID</div>";
                foreach ($agreements as $agr) {
                    if ($agr["user_id"] == $user_id) {
                        echo "<div class='item'>" . $agr['agreement_id'] . "</div>";
                    }
                }
                echo "</div>";

                echo "<div class='col'>";
                echo "<div class='colTitle'>User ID</div>";
                foreach ($agreements as $agr) {
                    if ($agr["user_id"] == $user_id) {
                        echo "<div class='item'>" . $agr['user_id'] . "</div>";
                    }
                }
                echo "</div>";

                echo "<div class='col'>";
                echo "<div class='colTitle'>Expert ID</div>";
                foreach ($agreements as $agr) {
                    if ($agr["user_id"] == $user_id) {
                        echo "<div class='item'>" . $agr['expert_id'] . "</div>";
                    }
                }
                echo "</div>";

                echo "<div class='col'>";
                echo "<div class='colTitle'>Start Date</div>";
                foreach ($agreements as $agr) {
                    if ($agr["user_id"] == $user_id) {
                        echo "<div class='item'>" . $agr['start_date'] . "</div>";
                    }
                }
                echo "</div>";

                echo "<div class='col'>";
                echo "<div class='colTitle' style='color: transparent; background-color: transparent'>Action</div>";
                foreach ($agreements as $agr) {
                    if ($agr["user_id"] == $user_id) {
                        echo "<div>";
                        echo "<a href='agreement.php?id=" . $agr['agreement_id'] . "' class='tableButton'>See More</a>";
                        echo "</div>";
                    }
                }
                echo "</div>";

                echo "</div>";
                echo "</div>";
            } else {
                echo "<div class='table'>You don't have any pending agreements</div>";
            }
            ?>

        </div>
    </div>

    <?php

    include_once "../../footer.php";

    ?>