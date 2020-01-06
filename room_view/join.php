<?php

generateHeader(""); ?>
<div class="row">
    <div class="col s12">
        <div class="container">
            <h3>Join <?php echo $room["name"] ?> - Code: <?php echo strtoupper(dechex($room["room_code"])) ?></h3>
            <h5 style="text-decoration: underline;">Joined Users</h5>
            <ul class="collection">
                <?php foreach ($user_list as $member) { ?>
                    <li class="collection-item"><?php echo $member["username"] ?></li>
                <?php } ?>
            </ul>
            <div class="center-align">
                <a href="./index.php?action=create&room_id=<?php echo $room_id ?>" class="btn btn-large waves-effect waves-light">Start</a>
            </div>
        </div>
    </div>
</div>

<?php generateFooter() ?>
