<?php

require_once "main.php";

$action = filter_input(INPUT_GET, "action");

switch($action) {
    case "authenticate":
        if(isset($current_user["first_name"])){
            header('Location: rooms/index.php');
        }
        $first_name = filter_input(INPUT_GET, "first_name");
        $last_name = filter_input(INPUT_GET, "last_name");
        $email = filter_input(INPUT_GET, "email");
        $id = filter_input(INPUT_GET, "id_token");

        create_user($id, $first_name, $last_name, $email);
        header("Location: ./rooms");
        break;

    case "signout":
        setcookie("session", "", time() - 3600);
        $current_user = array();
        break;
}

include 'view.php';