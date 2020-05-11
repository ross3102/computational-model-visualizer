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
function add_question ($id, $question){
    global $db;
    try {
        $query = "INSERT INTO question (room_id, text)
              VALUES(:id, :question)";
        $statement = $db->prepare($query);
        $statement->bindValue(":id", $id);
        $statement->bindValue(":question", $question);
        $statement->execute();
        $statement->closeCursor();
    }
    catch(PDOException $e){
        echo $e;
        exit();
    }

}
function add_test_cases($question_id, $test_case, $fail){
    global $db;
    try{
        $query = "INSERT INTO test_case (question_id, fail, input)
                VALUES (:question_id, :fail, :input)";
        $statement = $db->prepare($query);
        $statement->bindValue(":question_id", $question_id);
        $statement->bindValue(":fail", $fail);
        $statement->bindValue(":input", $test_case);
        $statement->execute();
        $statement->closeCursor();
    }
    catch (PDOException $e){
        echo $e;
        exit();
    }
}
function get_test_cases($question_id){
    global $db;
    try{
        $query = "SELECT * FROM test_case
      WHERE question_id = :question_id";
        $statement = $db->prepare($query);
        $statement->bindValue(":question_id", $question_id);
        $statement->execute();
        $result = $statement->fetch();
        $statement->closeCursor();
        return $result;
    }
    catch (PDOException $e){
        echo $e;
        exit();
    }
}
function delete_question($question_id){
    global $db;
    try{
        $query = "DELETE FROM question WHERE question_id = :question_id";
        $statement = $db->prepare($query);
        $statement->bindValue(":question_id", $question_id);
        $statement->execute();
        $statement->closeCursor();
    }catch (PDOException $e) {
        echo $e;
        exit();
    }
}

function update_room($id, $name, $desc){
    global $db;
    try {
        $query = "UPDATE room
                    SET room_desc = :desc,
                    name = :name
                    WHERE room_id = :room_id";
        $statement = $db->prepare($query);
        $statement->bindValue(":desc", $desc);
        $statement->bindValue(":name", $name);
        $statement->bindValue(":room_id", $id);
        $statement->execute();
        $statement->closeCursor();
    } catch (PDOException $e){
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

function get_rooms($user_id) {
    global $db;

    try {
        $query = "SELECT * FROM room WHERE owner_id = :user_id";

        $statement = $db->prepare($query);
        $statement->bindValue(":user_id", $user_id);
        $statement->execute();
        $result = $statement->fetchAll();
        $statement->closeCursor();
        return $result;
    } catch (PDOException $e) {
        echo $e;
        exit();
    }
}

function get_joined_rooms($user_id) {
    global $db;

    try {
        $query = "SELECT *
                    FROM room_user_xref x, room r  
                    WHERE x.room_id = r.room_id
                    AND user_id = :user_id";

        $statement = $db->prepare($query);
        $statement->bindValue(":user_id", $user_id);
        $statement->execute();
        $result = $statement->fetchAll();
        $statement->closeCursor();
        return $result;
    } catch (PDOException $e) {
        echo $e;
        exit();
    }
}

function get_user_by_id($user_id) {
    global $db;

    try {
        $query = "SELECT * FROM user WHERE user_id = :user_id";

        $statement = $db->prepare($query);
        $statement->bindValue(":user_id", $user_id);
        $statement->execute();
        $result = $statement->fetch();
        $statement->closeCursor();
        return $result;
    } catch (PDOException $e) {
        echo $e;
        exit();
    }
}

function get_user_by_token($token) {
    global $db;

    try {
        $query = "SELECT * FROM user WHERE user_token = :user_token limit 1";

        $statement = $db->prepare($query);
        $statement->bindValue(":user_token", $token);
        $statement->execute();
        $result = $statement->fetch();
        $statement->closeCursor();
        return $result;
    } catch (PDOException $e) {
        echo $e;
        exit();
    }
}

function create_room($user_id, $name, $desc) {
    global $db;

    try {
        $query = "INSERT INTO room (owner_id, name, room_desc)
                    VALUES (:owner_id, :name, :room_desc)";

        $statement = $db->prepare($query);
        $statement->bindValue(":owner_id", $user_id);
        $statement->bindValue(":name", $name);
        $statement->bindValue(":room_desc", $desc);
        $statement->execute();
        $statement->closeCursor();
    } catch (PDOException $e) {
        echo $e;
        exit();
    }
}

function join_room($user_id, $room_code) {
    global $db;

    try {
        $query = "INSERT INTO room_user_xref (user_id, room_id)
                    VALUES (:user_id, (
                      SELECT room_id
                        FROM room
                        WHERE room_code = :room_code
                    ))";

        $statement = $db->prepare($query);
        $statement->bindValue(":user_id", $user_id);
        $statement->bindValue(":room_code", $room_code);
        $statement->execute();
        $statement->closeCursor();
    } catch (PDOException $e) {
        echo $e;
        exit();
    }
}

function get_room_by_id($room_id) {
    global $db;

    try {
        $query = "select * from room
              where room_id = :room_id";

        $statement = $db->prepare($query);
        $statement->bindValue(":room_id", $room_id);
        $statement->execute();
        $result = $statement->fetch();
        $statement->closeCursor();
        return $result;
    } catch (PDOException $e) {
        echo $e;
        exit();
    }
}

function get_all_hashes() {
    global $db;

    try {
        $query = "select room_code from room";

        $statement = $db->prepare($query);
        $statement->execute();
        $result = $statement->fetchAll();
        $statement->closeCursor();

        $new = array();
        foreach ($result as $hash)
            array_push($new, $hash["room_code"]);
        return $new;
    } catch (PDOException $e) {
        echo $e;
        exit();
    }
}

function get_users_by_room($room_id) {
    global $db;

    try {
        $query = "select username from user, room_user_xref
              where room_id = :room_id
              and user.user_id = room_user_xref.user_id";

        $statement = $db->prepare($query);
        $statement->bindValue(":room_id", $room_id);
        $statement->execute();
        $result = $statement->fetchAll();
        $statement->closeCursor();
        return $result;
    } catch (PDOException $e) {
        echo $e;
        exit();
    }
}

function get_questions_by_room($room_id) {
    global $db;

    try {
        $query = "select * from question q, room r
              where r.room_id = :room_id
              and q.room_id = r.room_id";

        $statement = $db->prepare($query);
        $statement->bindValue(":room_id", $room_id);
        $statement->execute();
        $result = $statement->fetchAll();
        $statement->closeCursor();
        return $result;
    } catch (PDOException $e) {
        echo $e;
        exit();
    }
}

function get_questions_by_room_code($room_code) {
    global $db;

    try {
        $query = "select * from question q, room r
              where r.room_code = :room_code
              and q.room_id = r.room_id";

        $statement = $db->prepare($query);
        $statement->bindValue(":room_code", $room_code);
        $statement->execute();
        $result = $statement->fetchAll();
        $statement->closeCursor();
        return $result;
    } catch (PDOException $e) {
        echo $e;
        exit();
    }
}

function get_question_by_id($question_id) {
    global $db;

    try {
        $query = "select * from question q
              where question_id = :question_id";

        $statement = $db->prepare($query);
        $statement->bindValue(":question_id", $question_id);
        $statement->execute();
        $result = $statement->fetch();
        $statement->closeCursor();
        return $result;
    } catch (PDOException $e) {
        echo $e;
        exit();
    }
}

function update_hash($room_id, $hash) {
    global $db;

    try {
        $query = "update room
                  set room_code = :hash
                  where room_id = :room_id";

        $statement = $db->prepare($query);
        $statement->bindValue(":hash", $hash);
        $statement->bindValue(":room_id", $room_id);
        $statement->execute();
        $statement->closeCursor();
    } catch (PDOException $e) {
        echo $e;
        exit();
    }
}
function create_machine($type_cde, $creator_id, $question_id, $start_state, $transitions, $end_state) {
    global $db;

    try {
        $query = "insert into machine (type_cde, creator_id, question_id, start_state, transitions, end_state) 
                  values (:type_cde, :creator_id, :question_id, :start_state, :transitions, :end_state)";

        $statement = $db->prepare($query);
        $statement->bindValue(":type_cde", $type_cde);
        $statement->bindValue(":creator_id", $creator_id);
        $statement->bindValue(":question_id", $question_id);
        $statement->bindValue(":start_state", $start_state);
        $statement->bindValue(":transitions", $transitions);
        $statement->bindValue(":end_state", $end_state);
        $statement->execute();
        $statement->closeCursor();
    } catch (PDOException $e) {
        echo $e;
        exit();
    }
}
function delete_student($student_id, $room_id) {
    global $db;

    try {
        $query = "delete from room_user_xref where room_id = :room_id and user_id = :user_id";
        $statement = $db->prepare($query);
        $statement->bindValue(":user_id", $student_id);
        $statement->bindValue(":room_id", $room_id);
        $statement->execute();
        $statement->closeCursor();
    } catch(PDOException $e) {
        echo $e;
        exit();
    }
}
function close_room($room_id) {
    global $db;

    try {
        $query = "delete from room_user_xref where room_id = :room_id; update room set room_code = null where room_id = :room_id";
        $statement = $db->prepare($query);
        $statement->bindValue(":room_id", $room_id);
        $statement->execute();
        $statement->closeCursor();
    } catch(PDOException $e) {
        echo $e;
        exit();
    }
}
function get_user_by_email($email) {
    global $db;

    try {
        $query = "SELECT * FROM user WHERE email = :email LIMIT 1";

        $statement = $db->prepare($query);
        $statement->bindValue(":email", $email);
        $statement->execute();
        $result = $statement->fetch();
        $statement->closeCursor();
        return $result;
    } catch (PDOException $e) {
        echo $e;
        exit();
    }
}
function create_user($token, $first_name, $last_name, $email) {
    global $db;

    try {
        $e = get_user_by_email($email);
        if (!isset($e["email"])) {
            $query = "insert into user (user_token, first_name, last_name, email)
                      values (:token, :first_name, :last_name, :email)";
        }
        else {
            $query = "update user set user_token = :token where email = :email";
        }

        $statement = $db->prepare($query);
        if(!isset($e["email"])) {
            $statement->bindValue(":first_name", $first_name);
            $statement->bindValue(":last_name", $last_name);
        }
        $statement->bindValue(":token", $token);
        $statement->bindValue(":email", $email);
        $statement->execute();
        $statement->closeCursor();
    } catch (PDOException $e) {
        echo $e;
        exit();
    }
}