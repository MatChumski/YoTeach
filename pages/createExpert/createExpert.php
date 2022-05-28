<?php

include_once "../../header.php";

?>

<div class="container">
    <link rel="stylesheet" href="createExpert.css" />
    <div class="content">
        <div class="contentTitle">Create Expert Profile</div>
        <form action="index.php" method="POST" class="formExpert">
            <div class="option">
                <div class="formText">Your assistance will be (Can select both options):</div>
                <label><input type="checkbox" name="presential" value="presential" class="checkbox">Presential</label>
                <label><input type="checkbox" name="virtual" value="virtual" class="checkbox">Virtual</label>
            </div>
            <div class="option">
                <div class="formText">Price per hour for your service (Please be reasonable):</div>
                <input id='fee' class="expertInput" type="number" name="fee" placeholder="Fee" min='1' />
            </div>
            <div id="tags" class="option">
                <div class="formText">Your Tags (Max 5):</div>
                <div>
                    <button type="button" class="smallButton" onclick="addTag()">+</button>
                    <button type="button" class="smallButton" onclick="removeTag()">-</button>
                </div>
                <input class="expertInput mb-5" type="text" id="tag" name="tag[]" placeholder="Mathematics, Music..." />
            </div>
            <div class="option">
                <div class="formText">Your Description:</div>
                <div id='chars' class="formText">0/500</div>
                <textarea id='description' class="description" name="description" maxlength="500" cols="100" rows="5" placeholder="I am..."></textarea>
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
                <button class="formButton submit" type='' name="create" style='margin-left: 0px' formaction="/yoteach/includes/createExpert.inc.php">Finish</button>
                <button class="formButton" type='' name="cancel" style='margin-left: 0px' formaction="index.php">Cancel</button>
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