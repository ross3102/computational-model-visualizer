<?php

generateHeader(""); ?>
<div class="row">
    <div class="col s12">
        <div class="container">
            <h4 style="font-weight: 300;"><?php echo $room["name"] ?>&nbsp;<span class="right" style="font-size: 0.7em"><?php echo strtoupper(dechex($room["room_code"])) ?></span></h4>
            <h5 style="font-weight:200; text-decoration: underline;">Members</h5>
            <ul class="collapsible expandable">
                <?php foreach ($user_list as $member) { ?>
                    <li>
                        <?php
                        $total = 0;
                        $max_total = 0;
                        foreach (get_answers($room_id, $current_user["user_id"]) as $answer) {
                            $max_total += count(get_test_cases($answer["question_id"]));
                            if (isset($answer["score"]))
                                $total += $answer["score"];
                            else {
                                $total = -1;
                                break;
                            }
                        } ?>
                        <div class="collapsible-header"><?php echo $member["last_name"] . ", " . $member["first_name"] ?><span class="badge"><?php echo $total == -1 ? "Not Finished": ($total . "/" . $max_total) ?></span></div>
                        <div class="collapsible-body">
                            <ul class="collection" style="border: 1px solid lightgray">
                                <?php foreach (get_answers($room_id, $current_user["user_id"]) as $answer) {
                                    $max_score = count(get_test_cases($answer["question_id"])); ?>
                                    <li class="collection-item">
                                        <span style="display: inline-block; width: calc(100% - 110px)"><?php echo $answer["text"] ?></span>
                                        <span class="badge"><?php echo isset($answer["score"]) ? ($answer["score"] . "/" . $max_score): "Not Answered" ?></span>
                                    </li>
                                <?php } ?>
                            </ul>
                        </div>
                    </li>
                <?php } ?>
            </ul>
            <h5 style="font-weight:200; text-decoration: underline;">Scores</h5>
            <ul class="collapsible">
            <?php foreach ($questions as $question) {
                $max_score = count(get_test_cases($question["question_id"])); ?>
                <li>
                    <div class="collapsible-header"><?php echo $question["text"] ?></div>
                    <div class="collapsible-body">
                        <ul class="collection" style="border: 1px solid lightgray">
                        <?php foreach (get_machines($question["question_id"]) as $machine) { ?>
                            <li class="collection-item"><?php echo $machine["last_name"] . ", " . $machine["first_name"] ?><span class="badge"><?php echo isset($machine["score"]) ? ($machine["score"] . "/" . $max_score): "Not Answered" ?></span></li>
                        <?php } ?>
                        </ul>
                    </div>
                </li>
            <?php } ?>
            </ul>
        </div>
    </div>
</div>

<?php generateFooter() ?>
