<?php

include_once "../../header.php";

?>

<?php

include_once "../../includes/db.inc.php";
include_once "../../includes/functions.inc.php";

$expert = getThing($conn, "experts", "expert_id", $_SESSION["user_id"]);
if ($expert != false) {
    $tags = getTags($conn, $expert["expert_id"]);

    $expert_id = $expert["expert_id"];
    $fee = $expert["fee"];
    $virtual = $expert["virtual"];
    $presential = $expert["presential"];
    $description = $expert["description"];
}

?>

<link rel="stylesheet" href="editExpert.css" />
<div class="container">
    <div class="content">
        <div class="contentTitle">Edit Expert Profile</div>
        <form action="index.php" method="POST" class="formExpert">
            <div class="option">
                <div class="formText">Assistance:</div>
                <?php
                if ($presential == "1") {
                    echo "<label><input type='checkbox' name='presential' value='presential' class='checkbox' checked>Presential</label>";
                } else {
                    echo "<label><input type='checkbox' name='presential' value='presential' class='checkbox'>Presential</label>";
                }
                if ($virtual == "1") {
                    echo "<label><input type='checkbox' name='virtual' value='virtual' class='checkbox' checked>Virtual</label>";
                } else {
                    echo "<label><input type='checkbox' name='virtual' value='virtual' class='checkbox'>Virtual</label>";
                }
                ?>
            </div>
            <div class="option">
                <div class="formText">Fee:</div>
                <input class="expertInput" type="number" name="fee" placeholder="Fee" value='<?php echo $fee ?>' />
            </div>
            <div id="tags" class="option">
                <div class="formText">Your Tags (Max 5):</div>
                <div>
                    <button type="button" class="smallButton" onclick="addTag()">+</button>
                    <button type="button" class="smallButton" onclick="removeTag()">-</button>
                </div>
                <?php
                foreach ($tags as $tag) {
                    echo "<input class='expertInput mb-5' type='text' id='tag' name='tag[]' placeholder='Mathematics, Music...' value='" . $tag['tag_name'] . "'/>";
                }
                ?>
            </div>
            <div class="option">
                <div class="formText">Description:</div>
                <div id='chars' class="formText"><?php echo strlen($description) ?>/500</div>
                <textarea id='description' class="description" name="description" maxlength="500" cols="100" rows="5" placeholder="I am..."><?php echo $description; ?></textarea>
            </div>

            <?php
            #Errors
            if (isset($_GET["error"])) {
                switch($_GET["error"]) {
                    case "emptyInput":
                        echo "<div>Please fill in all the fields</div>";
                        break;
                    case "invalidTag":
                        echo "<div>Please enter a valid tag</div>";
                        break;
                }
                
                
            }
            ?>

            <div class="buttons">
                <button class="formButton submit" type="submit" name="create" formaction="../../includes/editExpert.inc.php">Finish</button>
                <button class="formButton" type="submit" name="cancel" formaction="index.php">Cancel</button>
            </div>

        </form>
    </div>
</div>

<script>
    document.getElementById("description").addEventListener("keyup", function() {
        let size = this.value.length;
        document.getElementById("chars").innerHTML = `${size}/500`;
    });

    function addTag() {
        let tagsOption = document.getElementById("tags");
        let list = tagsOption.getElementsByTagName("input")

        if (list.length < 5) {
            let newTag = document.createElement("input");
            newTag.setAttribute("class", "expertInput mb-5");
            newTag.setAttribute("type", "text");
            newTag.setAttribute("id", "tag");
            newTag.setAttribute("name", "tag[]");
            newTag.setAttribute("placeholder", "Mathematics, Music...");

            tagsOption.appendChild(newTag);
        }
    }

    function removeTag() {
        let tagsOption = document.getElementById("tags");
        let list = tagsOption.getElementsByTagName("input")

        if (list.length > 1) {
            tagsOption.removeChild(list[list.length - 1])
        }
    }
</script>

<?php

include_once "../../footer.php";

?>