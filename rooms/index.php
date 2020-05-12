<?php

require_once "../main.php";
require_once "../model/machine_db.php";

verify_logged_in();

$action = filter_input(INPUT_GET, "action");
if (!isset($action)) {
    $action = filter_input(INPUT_POST, "action");
    if (!isset($action))
        $action = "list_rooms";
}

switch ($action) {
    case "join_room":
        $room_code = filter_input(INPUT_POST, "room_code");
        join_room($current_user["user_id"], hexdec($room_code));
        header("Location: ./index.php");
        break;
    case "join_room_again":
        $room_code = filter_input(INPUT_GET, "room_code");
        $question1 = get_questions_by_room_code($room_code)[0];
        header("Location: ../load_machine/index.php?question_id=".$question1["question_id"]);
        break;
    case "create_room":
        $name = filter_input(INPUT_POST, "room_name");
        $desc = filter_input(INPUT_POST, "room_desc");
        create_room($current_user["user_id"], $name, $desc);
        header("Location: ./index.php");
        break;
    case "edit_room":
        $id = filter_input(INPUT_POST, "room_id");
        $name = filter_input(INPUT_POST, "room_name");
        $desc = filter_input(INPUT_POST, "room_desc");
        update_room($id, $name, $desc);
        header("Location: ./index.php");
        break;
    case "close_room":
        $room_id = filter_input(INPUT_GET, "room_id");
        close_room($room_id);
        header("Location: ./index.php");
        break;
    case "list_rooms":
        $rooms = get_rooms($current_user["user_id"]);
        $joined_rooms = get_joined_rooms($current_user["user_id"]);
        include "./view.php";
}