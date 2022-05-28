<?php

include_once "../../header.php";

?>
<link rel="stylesheet" href="/yoteach/pages/agreements/agr.css" />
<?php
if (isset($_SESSION["user_id"])) {
    if (isset($_GET["id"])) {

        require_once "../../includes/db.inc.php";
        require_once "../../includes/functions.inc.php";

        $agreement = getThing($conn, "agreements", "agreement_id", $_GET["id"]);

        if ($agreement) {
            if ($agreement["user_id"] == $_SESSION["user_id"] || $agreement["expert_id"] == $_SESSION["user_id"]) {


                $id = $agreement["agreement_id"];
                $user_id = $agreement["user_id"];
                $expert_id = $agreement["expert_id"];
                $state = $agreement["state"];
                $start_date = $agreement["start_date"];
                $finish_date = $agreement["finish_date"];
                $user_msg = $agreement["user_msg"];

                $user = getThing($conn, "users", "user_id", $user_id);

                $user_name = $user["name"];
                $user_email = $user["email"];

                $expert = getThing($conn, "users", "user_id", $expert_id);

                $expert_name = $expert["name"];

                if ($_SESSION["user_id"] == $user_id) {
                    $reported_id = $expert_id;
                    $reporting_id = $user_id;
                    $reported_name = $expert_name;
                } else if ($_SESSION["user_id"] == $expert_id) {
                    $reported_id = $user_id;
                    $reporting_id = $user_id;
                    $reported_name = $user_name;
                } else {
                    header("Location: /yoteach/index.php");
                    exit();
                }

                $report = getReport($conn, $reporting_id, $reported_id);

                echo "<div class='container'>"; #Container Start
                echo "<div class='agreementContent'>"; #Content Start

                echo "<div>";

                #AgrID
                echo "<div class='agrID'>Agreement ID#$id</div>";

                #Expert Name
                echo "<div class='item'>";
                echo "Expert:<div><a href='/yoteach/pages/expert/expert.php?expert_id=$expert_id'>$expert_name</a></div>";
                echo "</div>";

                #User Name
                echo "<div class='item'>";
                echo "Learner:<div>$user_name</div>";
                echo "</div>";

                #User Email
                echo "<div class='item'>";
                echo "Learner's Email:<div>$user_email</div>";
                echo "</div>";

                #State
                echo "<div class='item'>";
                echo "State:";
                switch ($state) {
                    case "1":
                        echo "<div style='color: orange; font-weight: 700'>In Process</div>";
                        break;
                    case "2":
                        echo "<div style='color: green; font-weight: 700'>Completed</div>";
                        break;
                    case "3":
                        echo "<div style='color: darkred; font-weight: 700'>Canceled (After Being Accepted)</div>";
                        break;
                    case "4":
                        echo "<div style='color: orange; font-weight: 700'>Pending</div>";
                        break;
                    case "5":
                        echo "<div style='color: darkred; font-weight: 700'>Canceled (Before Being Accepted)</div>";
                        break;
                }
                echo "</div>";

                #Start Date
                echo "<div class='item'>";
                echo "Created:<div>$start_date</div>";
                echo "</div>";

                #If there is a finish date
                if (!empty($finish_date)) {
                    echo "<div class='item'>";
                    echo "Finished:<div>$finish_date</div>";
                    echo "</div>";
                }

                #Message
                echo "<div class='text'>Learner's Message:";
                echo "<div>$user_msg</div>";
                echo "</div>";
                echo "</div>";

                #Buttons
                echo "<div class='options'>";
                echo "<form action='' method='GET'>";
                echo "<div class='top'>";
                #If In Process or Pending
                if ($state == "1" || $state == "4") {

                    echo "<input name='current_state' value='$state' style='display: none'/>";
                    echo "<input name='id' value='$id' style='display: none'/>";

                    #If the current user is the expert
                    if ($state == "4" && $expert_id == $_SESSION["user_id"]) {
                        echo "<button type='submit' class='agrButton' name='accept' formaction='/yoteach/includes/agreement.inc.php'>Accept Agreement</button>";
                    }

                    if ($state == "1") {
                        echo "<button type='submit' class='agrButton' name='complete' formaction='/yoteach/includes/agreement.inc.php'>Complete Agreement</button>";
                    }
                    echo "<button type='submit' class='agrButton' name='cancel' formaction='/yoteach/includes/agreement.inc.php'>Cancel Agreement</button>";
                    #If Finished
                } else if ($state == "2" || $state == "3" || $state == "5") {

                    if ($expert_id != $_SESSION["user_id"]) {
                        echo "<input name='expert_id' value='$expert_id' style='display: none'/>";
                        echo "<button type='submit' class='agrButton' formaction='/yoteach/pages/expert/expert.php'>Make Another Agreement</button>";
                    }

                    #If Completed or Canceled After Acceptance
                    if ($state != "5") {
                        if ($expert_id != $_SESSION["user_id"]) {
                            echo "<button type='button' class='agrButton rateButton' id='rateExpert'>Rate Expert</button>";
                        }
                        if ($report == false) {
                            echo "<button type='button' class='agrButton reportButton' id='reportButton' class='reportButton'>Report User</button>";
                        } else {
                            echo "<div class='reportLabel'>You have already made a report against this user</div>";
                        }
                        echo "</div>";

                        #Bottom
                        echo "<div class='bottom'>";

                        #Report
                        echo "<div id='report' class='report'>";

                        echo "Write your report:";
                        echo "<textarea name='reportMessage' class='formInput'></textarea>";
                        echo "<input name='reported_id' value='$reported_id' style='display: none'/>";
                        echo "<input name='reporting_id' value='$reporting_id' style='display: none'/>";
                        echo "<button type='submit' name='report' formaction='/yoteach/includes/agreement.inc.php'>Report $reported_name</button>";

                        echo "</div>";

                        #Rating
                        echo "<div id='rating' class='rating'>";

                        echo "<div>";
                        echo "<label></label>";
                        echo "<label></label>";
                        echo "<label></label>";
                        echo "<label></label>";
                        echo "<label></label>";
                        echo "</div>";

                        echo "<div>Rating:<div id='score' style='margin-left: 5px'>None</div></div>";

                        echo "<div>";
                        echo "<form method='POST'>";
                        echo "<input name='expert_id' value='$expert_id' style='display: none'/>";
                        echo "<input name='user_id' value='$user_id' style='display: none'/>";
                        echo "<input id='new_rating' name='new_rating' style='display: none'/>";
                        echo "<button id='ratingButton' type='submit' name='rating' class='ratingButton' formaction='/yoteach/includes/agreement.inc.php' style='display: none'></button>";
                        echo "</form>";
                        echo "<div>";

                        echo "</div>";
                        echo "</div>";
                    }
                }
                echo "</form>";

                echo "</div>";
                if (isset($_GET["error"])) {
                    if ($_GET["error"] == "noneRate") {
                        echo "Rating submitted succesfully";
                    }
                }
                echo "</div>"; #Content End
                echo "</div>"; #Container End

            } else {
                echo "<div class='container'>"; #Container Start
                echo "<div class='content'>"; #Container Start
                echo "<div class='contentTitle'>You are not allowed to see this</div>";
                echo "</div>";
                echo "</div>";
            }
        }
    }
} else {
    header("Location: /yoteach/index.php");
    exit();
}

?>

<script>
    let report = document.getElementById("report");
    let showReport = false;
    let rating = document.getElementById("rating");
    let showRating = false;

    let rpButton = document.getElementById("reportButton");

    //For the Report 
    if (rpButton) {
        rpButton.addEventListener('click', function() {
            if (!showReport) {
                report.style.display = "flex";
                showReport = true;
                if (showRating) {
                    showRating = false;
                    rating.style.display = "none";
                }
            } else {
                report.style.display = "none";
                showReport = false;
            }
        })
    }

    //For the Rating 

    document.getElementById("rateExpert").addEventListener('click', function() {
        if (!showRating) {
            rating.style.display = "flex";
            showRating = true;
            if (showReport) {
                showReport = false;
                report.style.display = "none";
            }
        } else {
            rating.style.display = "none";
            showRating = false;
        }
    })

    let stars = document.querySelectorAll("div.rating > div > label");
    let score = document.getElementById('score');
    let selected = false;
    let state = ["rgb(139, 139, 139)", "rgb(139, 139, 139)", "rgb(139, 139, 139)", "rgb(139, 139, 139)", "rgb(139, 139, 139)"];

    console.log(stars);

    for (let i = 0; i < stars.length; i++) {
        stars[i].addEventListener('mouseover', function() {

            this.style.backgroundColor = 'rgb(255, 174, 0)'
            for (let j = 0; j < stars.length; j++) {
                if (j <= i) {
                    stars[j].style.backgroundColor = 'rgb(255, 174, 0)';
                } else {
                    stars[j].style.backgroundColor = 'rgb(139, 139, 139)';
                }
            }
        });

        stars[i].addEventListener('mouseout', function() {
            for (let j = 0; j < stars.length; j++) {
                stars[j].style.backgroundColor = state[j];
            }
        });

        stars[i].addEventListener('click', function() {
            for (let j = 0; j < stars.length; j++) {
                stars[j].style.backgroundColor = 'rgb(139, 139, 139)';
                state[j] = 'rgb(139, 139, 139)';
            }
            score.innerHTML = i + 1;
            selected = true;
            let button = document.getElementById("ratingButton");
            button.style.display = "inline";
            button.innerHTML = "Rate";
            document.getElementById("new_rating").setAttribute("value", (i + 1));
            for (let j = 0; j <= i; j++) {
                stars[j].style.backgroundColor = 'rgb(255, 174, 0)';
                state[j] = 'rgb(255, 174, 0)';
            }
        })
    }
</script>



<?php

include_once "../../footer.php";

?>