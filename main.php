<?php

require_once dirname(__FILE__) . "/config.php";

require_once dirname(__FILE__) . "/model/machine_db.php";

$user_id = 31;
$user = get_user_by_id($user_id);

function generateHeader($head="") {
    global $web_root, $user;

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
        
        
        <body class='teal accent-1'>
            <nav class='pink accent-3'>
            <div class='nav-wrapper' style='padding: 0 30px;'>
                <a href='#' class='brand-logo'>CMV</a>
                <ul id='nav-mobile' class='right hide-on-med-and-down'>
                    <li><a href='#'>Hello, " . $user["username"] . "</a></li>
                </ul>
            </div>
            </nav>        
            <main>
                <div class='container z-depth-3 white' id='main-box'>
          
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