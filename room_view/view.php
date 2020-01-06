<?php

generateHeader(""); ?>
<div class="row">
    <div class="col s12">
    <div class="container">
        <h3><?php echo $room["name"] ?></h3>
        <h5 style="text-decoration: underline;">Questions</h5>
        <ul class="collection">
            <?php foreach ($questions as $question) { ?>
                <li class="collection-item"><?php echo $question["text"] ?></li>
            <?php } ?>
        </ul>
        <div class="center-align">
            <div class="btn btn-large btn-floating waves-effect waves-light"><i class="material-icons">add</i></div>
            <br>
            <br>
            <a href="./index.php?action=create&room_id=<?php echo $room_id ?>" class="btn btn-large waves-effect waves-light">Open Room</a>
        </div>
    </div>
    </div>
</div>

<?php generateFooter() ?>
