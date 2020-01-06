<?php

require_once "../main.php";
require_once "../model/machine_db.php";

$action = filter_input(INPUT_GET, "action");
if (!isset($action)) {
    $action = filter_input(INPUT_POST, "action");
    if (!isset($action))
        $action = "list_rooms";
}

switch ($action) {
    case "join_room":
        $room_code = filter_input(INPUT_POST, "room_code");
        join_room($user_id, hexdec($room_code));
        include "waiting.php";
        break;
    case "create_room":
        $name = filter_input(INPUT_POST, "room_name");
        $desc = filter_input(INPUT_POST, "room_desc");
        create_room($user_id, $name, $desc);
        header("Location: ./index.php");
        break;
    case "edit_room":
        $id = filter_input(INPUT_POST, "room_id");
        $name = filter_input(INPUT_POST, "room_name");
        $desc = filter_input(INPUT_POST, "room_desc");
        update_room($id, $name, $desc);
        header("Location: ./index.php");
        break;
    case "list_rooms":
        $rooms = get_rooms($user_id);
        include "./view.php";
}