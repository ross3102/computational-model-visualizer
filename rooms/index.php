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
        header("Location: ../build_machine/index.php?room_code=". $room_code . "&question_num=0");
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
        update_room($id, $name, $desc, $current_user["user_id"]);
        header("Location: ./index.php");
        break;
    case "close_room":
        $room_id = filter_input(INPUT_GET, "room_id");
        close_room($room_id, $current_user["user_id"]);
        header("Location: ./index.php");
        break;
    case "delete_room":
        $room_id = filter_input(INPUT_GET, "room_id");
        delete_room($room_id, $current_user["user_id"]);
        header("Location: ./index.php");
        break;
    case "leave_room":
        $room_id = filter_input(INPUT_GET, "room_id");
        leave_room($room_id, $current_user["user_id"]);
        header("Location: ./index.php");
        break;
    case "list_rooms":
        $rooms = get_rooms($current_user["user_id"]);
        $joined_rooms = get_joined_rooms($current_user["user_id"]);
        include "./view.php";
}