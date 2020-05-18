<?php

require_once "../main.php";
require_once "../model/machine_db.php";

verify_logged_in();

const FSM = 1,
    PDA = 2,
    TM = 3;

$action = filter_input(INPUT_GET, "action");
if (!isset($action)) {
    $action = filter_input(INPUT_POST, "action");
    if (!isset($action))
        $action = "show_form";
}

switch ($action) {
    case "save_machine":
        $question_id = filter_input(INPUT_POST, "question_id");
        $start_state = filter_input(INPUT_POST, "start_state");
        $transitions = str_replace("\n", ";", str_replace("\r\n","\n", filter_input(INPUT_POST, "transitions")));
        $end_state = filter_input(INPUT_POST, "end_state");
        $machine_type = filter_input(INPUT_POST, "machine_type");
        $correct = filter_input(INPUT_POST, "correct");

        create_machine($machine_type, $current_user["user_id"], $question_id, $start_state, $transitions, $end_state, $correct);
        break;
    case "show_form":
        $room_code = filter_input(INPUT_GET, "room_code");
        $question_num = (int)filter_input(INPUT_GET, "question_num");
        $question = "";
        $question_id = 0;
        $machine_type = 0;
        $question_list = get_questions_by_room_code($room_code);

        if(isset($question_list[$question_num])) {
            $question = $question_list[$question_num];
            $question_id = $question["question_id"];
            if (answered($question_id, $current_user["user_id"])) {
                header("Location: ./index.php?room_code=" . $room_code . "&question_num=" . ($question_num + 1));
                exit();
            }
            $machine_type = $question["machine_type"];
            include 'view.php';
        }
        else {
            include 'done.php';
        }
}