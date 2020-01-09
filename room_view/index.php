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
        include "view.php";
        break;
    case "create":
        $room_id = filter_input(INPUT_GET, "room_id");
        $used_hashes = get_all_hashes();

        $NUM_DIGITS = 6;

        do {
            $hash = rand(pow(16, $NUM_DIGITS-1), pow(16, $NUM_DIGITS) - 1);
        } while (in_array($hash, $used_hashes));

        update_hash($room_id, $hash);
        header("Location: ./index.php?action=show_users&room_id=" . $room_id);
        break;
    case "show_users":
        $room_id = filter_input(INPUT_GET, "room_id");
        $room = get_room_by_id($room_id);
        $user_list = get_users_by_room($room_id);
        include 'join.php';
}