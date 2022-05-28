<?php

include_once "../../header.php";

?>

<?php

if (isset($_SESSION["user_id"])) {

    require_once "../../includes/db.inc.php";
    require_once "../../includes/functions.inc.php";

    $user_id = $_SESSION["user_id"];

    $agreements = getFinishedAgreements($conn, $user_id);
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
                    <a href="a2.php" class='sidebarButton'>Pending</a>
                    <a href="a3.php" class='sidebarButton'>Requests</a>
                    <a href="a4.php" class='sidebarButton active'>Finished</a>
                </div>
                <!-- <form action='/yoteach/index.php' method='POST'>
                    <button type='submit' formaction='a1.php' class='sidebarButton'>Active</button>
                    <button type='submit' formaction='a2.php' class='sidebarButton'>Pending</button>
                    <button type='submit' formaction='a3.php' class='sidebarButton'>Requests</button>
                    <button type='submit' formaction='a4.php' class='sidebarButton active'>Finished</button>
                </form> -->
            </div>
            <?php
            if ($agreements) {
                echo "<div class='table'>";
                echo "<div class='col'>";
                echo "<div class='colTitle'>Agreement ID</div>";
                foreach ($agreements as $agr) {
                    echo "<div class='item'>" . $agr['agreement_id'] . "</div>";
                }
                echo "</div>";
                echo "<div class='col'>";
                echo "<div class='colTitle'>Learner ID</div>";
                foreach ($agreements as $agr) {
                    echo "<div class='item'>" . $agr['user_id'] . "</div>";
                }
                echo "</div>";
                echo "<div class='col'>";
                echo "<div class='colTitle'>Expert ID</div>";
                foreach ($agreements as $agr) {
                    echo "<div class='item'>" . $agr['expert_id'] . "</div>";
                }
                echo "</div>";
                echo "<div class='col'>";
                echo "<div class='colTitle'>Start Date</div>";
                foreach ($agreements as $agr) {
                    echo "<div class='item'>" . $agr['start_date'] . "</div>";
                }
                echo "</div>";
                echo "<div class='col'>";
                echo "<div class='colTitle'>Finish Date</div>";
                foreach ($agreements as $agr) {
                    echo "<div class='item'>" . $agr['finish_date'] . "</div>";
                }
                echo "</div>";
                echo "<div class='col'>";
                echo "<div class='colTitle'>Finish State</div>";
                foreach ($agreements as $agr) {
                    if ($agr['state'] == 2) {
                        echo "<div class='item'>Completed</div>";
                    } else if ($agr['state'] == "3" || $agr['state'] == "5") {
                        echo "<div class='item'>Canceled</div>";
                    }
                }
                echo "</div>";
                echo "<div class='col'>";
                echo "<div class='colTitle' style='color: transparent; background-color: transparent'>Action</div>";
                foreach ($agreements as $agr) {
                    /* echo "<form action='' method='POST'>";
                    echo "<input name='id' value='" . $agr["agreement_id"] . "' style='display: none'/>";
                    echo "<button class='tableButton' formaction='agreement.php'>See More</button>";
                    echo "</form>"; */
                    echo "<div>";
                    echo "<a href='agreement.php?id=". $agr['agreement_id'] . "' class='tableButton'>See More</a>";
                    echo "</div>";
                }
                echo "</div>";
                echo "</div>";
                echo "</div>";
            } else {
                echo "<div class='table'>You don't have any finished agreements</div>";
            }
            ?>

        </div>
    </div>

    <?php

    include_once "../../footer.php";

    ?>