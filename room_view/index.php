<?php

require_once "../main.php";
require_once "../model/machine_db.php";

$action = filter_input(INPUT_GET, "action");
if (!isset($action)) {
    $action = filter_input(INPUT_POST, "action");
    if (!isset($action))
        $action = "show_details";
}

switch ($action) {
    case "show_details":
        $room_id = filter_input(INPUT_GET, "room_id");
        $room = get_room_by_id($room_id);
        $questions = get_questions_by_room($room_id);
        $machine_types = get_all_machine_types();
        include "view.php";
        break;
    case "create":
        $room_id = filter_input(INPUT_GET, "room_id");
        if (get_room_by_id($room_id)["room_code"] == null) {
            $used_hashes = get_all_hashes();

            $NUM_DIGITS = 6;

            do {
                $hash = rand(pow(16, $NUM_DIGITS - 1), pow(16, $NUM_DIGITS) - 1);
            } while (in_array($hash, $used_hashes));

            update_hash($room_id, $hash);
        }
        header("Location: ./index.php?action=show_users&room_id=" . $room_id);
        break;
    case "add_case":
        $question_id = filter_input(INPUT_POST, "question_id");
        $room_id = filter_input(INPUT_POST, "room_id");
        $test_input = filter_input(INPUT_POST, "test_input");
        $pass = filter_input(INPUT_POST, "pass") == "P";
        add_test_cases($question_id, $test_input, $pass);
        header("Location: ./index.php?room_id=" . $room_id);
        break;
    case "get_test_cases":
        $question_id = filter_input(INPUT_GET, "question_id");
        $cases = get_test_cases($question_id);
        header("Content-Type: application/json");
        echo json_encode(array("cases" => $cases));
        break;
    case "add_question":
        $room_id = filter_input(INPUT_POST, "room_id");
        $question_name = filter_input(INPUT_POST, "question_name");
        $machine_type = filter_input(INPUT_POST, "machine_type");
        add_question($room_id, $question_name, $machine_type);
        header("Location: ./index.php?room_id=" . $room_id);
        break;
    case "delete_question":
        $room_id = filter_input(INPUT_GET, "room_id");
        $question_id = filter_input(INPUT_GET, "question_id");
        delete_question($question_id);
        header("Location: ./index.php?room_id=" . $room_id);
        break;
    case "show_users":
        $room_id = filter_input(INPUT_GET, "room_id");
        $room = get_room_by_id($room_id);
        $user_list = get_users_by_room($room_id);
        include 'join.php';
}