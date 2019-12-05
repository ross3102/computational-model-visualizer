<?php

require_once "../main.php";
require_once "../model/machine_db.php";

$action = filter_input(INPUT_GET, "action");
if (!isset($action)) {
    $action = filter_input(INPUT_POST, "action");
    if (!isset($action))
        $action = "create";
}

switch ($action) {
    case "create":
        break;

    case "show_users":
        $room_id = filter_input(INPUT_GET, "room_id");
        $user_list = get_users_by_room($room_id);
        $hash = bin2hex(random_bytes(3));
        update_hash($room_id, $hash);
        include 'join.php';
}