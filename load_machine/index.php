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

        create_machine($machine_type, $current_user["user_id"], $question_id, $start_state, $transitions, $end_state);
        break;
    case "show_form":
        $question_id = filter_input(INPUT_GET, "question_id");
        $question = get_question_by_id($question_id);
        $machine_type = $question["machine_type"];
        include 'view.php';
}