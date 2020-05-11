<?php

require_once dirname(__FILE__) . "/config.php";
require_once dirname(__FILE__). "/vendor/autoload.php";

require_once dirname(__FILE__) . "/model/machine_db.php";

$current_user = "";

if(isset($_COOKIE["session"])){
    $current_user = get_user_by_token($_COOKIE["session"]);
}
function generateHeader($head="") {
    global $web_root, $current_user;

    if(isset($current_user["first_name"])){
        $user_greeting = "<li><a href='#'>Hello, " . $current_user["first_name"] . "</a></li>
                        <li><a href='../index.php?action=signout'>Sign Out</a></li>";
    }
    else {
        $user_greeting = "";
    }

    echo "
    <!DOCTYPE html>
    <html lang='en'>
        <head>
            <!--Import Google Icon Font-->
            <link href='https://fonts.googleapis.com/icon?family=Material+Icons' rel='stylesheet'>
            <!--Import materialize.css-->
            <link type='text/css' rel='stylesheet' href='/" .$web_root . "/css/materialize.min.css'  media='screen,projection'/>
            <link rel='stylesheet' href='/" .$web_root . "/css/styles.css'>
            
            <!--Let browser know website is optimized for mobile-->
            <meta name='viewport' content='width=device-width, initial-scale=1.0'/>
        " . $head . " </head>
        <body class=''>
            <nav class='blue'>
            <div class='nav-wrapper' style='padding: 0 30px;'>
                <a href='/" . $web_root . "' class='brand-logo'>CMV</a>
                <ul id='nav-mobile' class='right hide-on-med-and-down'>
                    " . $user_greeting . "
                </ul>
            </div>
            </nav>
            <main>
                <div class='container z-depth-3 white' id='main-box' style='padding: 10px'>
          
    ";
}

function generateFooter($scripts="") {
    global $web_root;

    echo "
             </div>
            </main>
            <script src='/" .$web_root . "/js/jquery.min.js'></script>
            <script src='/" .$web_root . "/js/materialize.min.js'></script>
            <script>
                $(document).ready(function() {
                    M.AutoInit();
                });
            </script>
        </body>
    </html>
    ";
}

function verify_logged() {
    global $current_user;

    if (isset($current_user["first_name"])) {
        return true;
    }
    else {
        header("Location: ../index.php");
    }
}