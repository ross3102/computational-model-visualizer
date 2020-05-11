<?php

require_once "main.php";

if(isset($current_user["first_name"])){
    header('Location: rooms/index.php');
}

$action = filter_input(INPUT_GET, "action");

switch($action) {
    case "authenticate":
        $first_name = filter_input(INPUT_GET, "first_name");
        $last_name = filter_input(INPUT_GET, "last_name");
        $email = filter_input(INPUT_GET, "email");
        $id = filter_input(INPUT_GET, "id_token");

        create_user($id, $first_name, $last_name, $email);
        header("Location: ./rooms");
        break;

    case "signout":
        setcookie("session", "", time() - 3600);
        header("Location: ../index.php");
        break;
}

include 'view.php';