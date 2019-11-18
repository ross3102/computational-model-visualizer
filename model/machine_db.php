<?php

function get_machines() {
    global $db;

    try {
        $query = "SELECT * FROM machine";

        $statement = $db->prepare($query);
        $statement->execute();
        $result = $statement->fetchAll();
        $statement->closeCursor();
        return $result;
    } catch (PDOException $e) {
        echo $e;
        exit();
    }
}

function get_machine_by_id($machine_id) {
    global $db;

    try {
        $query = "SELECT * FROM machine WHERE machine_id = :machine_id";

        $statement = $db->prepare($query);
        $statement->bindValue(":machine_id", $machine_id);
        $statement->execute();
        $result = $statement->fetch();
        $statement->closeCursor();
        return $result;
    } catch (PDOException $e) {
        echo $e;
        exit();
    }
}