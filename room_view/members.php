<?php

generateHeader(""); ?>
<div class="row">
    <div class="col s12">
        <div class="container">
            <h4 style="font-weight: 300;"><?php echo $room["name"] ?>&nbsp;<span class="right" style="font-size: 0.7em"><?php echo strtoupper(dechex($room["room_code"])) ?></span></h4>
            <h5 style="font-weight:200; text-decoration: underline;">Members</h5>
            <ul class="collection">
                <?php foreach ($user_list as $member) { ?>
                    <li class="collection-item"><?php echo $member["last_name"] . ", " . $member["first_name"] ?></li>
                <?php } ?>
            </ul>
            <h5 style="font-weight:200; text-decoration: underline;">Scores</h5>
            <?php foreach ($questions as $question) {
                $max_score = count(get_test_cases($question["question_id"])); ?>
                <h5 style="font-weight:200"><?php echo $question["text"] ?></h5>
                <ul class="collection">
                <?php foreach (get_machines($question["question_id"]) as $machine) { ?>
                    <li class="collection-item"><?php echo $machine["last_name"] . ", " . $machine["first_name"] ?><span class="secondary-content"><?php echo isset($machine["score"]) ? ($machine["score"] . "/" . $max_score): "" ?></span></li>
                <?php } ?>
                </ul>
            <?php } ?>
        </div>
    </div>
</div>

<?php generateFooter() ?>
