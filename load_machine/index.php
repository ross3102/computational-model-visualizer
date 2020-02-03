<?php

require_once "../main.php";
require_once "../model/machine_db.php";

$action = filter_input(INPUT_GET, "action");
if (!isset($action)) {
    $action = filter_input(INPUT_POST, "action");
    if (!isset($action))
        $action = "show_form";
}

switch ($action) {
    case "create_turing":
        $question_id = filter_input(INPUT_POST, "question_id");
        $start_state = filter_input(INPUT_POST, "start_state");
        $transitions = str_replace("\n", ";", str_replace("\r\n","\n", filter_input(INPUT_POST, "transitions")));
        $end_state = filter_input(INPUT_POST, "end_state");

        create_machine(1, $user_id, $question_id, $start_state, $transitions, $end_state);
        break;
    case "show_form":
        $question_id = filter_input(INPUT_GET, "question_id");
        $question = get_question_by_id($question_id);
        include 'view.php';
}