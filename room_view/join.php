<?php

generateHeader(""); ?>
<div class="row">
    <div class="col s12">
        <div class="container">
            <h4 style="font-weight: 300;"><?php echo $room["name"] ?>&nbsp;<span class="right" style="font-size: 0.7em"><?php echo strtoupper(dechex($room["room_code"])) ?></span></h4>
            <h5 style="font-weight:200; text-decoration: underline;">Joined Users</h5>
            <ul class="collection">
                <?php foreach ($user_list as $member) { ?>
                    <li class="collection-item"><?php echo $member["last_name"] . ", " . $member["first_name"] ?></li>
                <?php } ?>
            </ul>
            <div class="center-align">
                <a href="./index.php?action=create&room_id=<?php echo $room_id ?>" class="btn btn-large waves-effect waves-light">Start</a>
            </div>
        </div>
    </div>
</div>

<?php generateFooter() ?>
