<?php

require_once "config.php";

function generateHeader($head="") {
    echo "
    <!DOCTYPE html>
    <html lang='en'>
        <head>
            <!--Import Google Icon Font-->
            <link href='https://fonts.googleapis.com/icon?family=Material+Icons' rel='stylesheet'>
            <!--Import materialize.css-->
            <link type='text/css' rel='stylesheet' href='css/materialize.min.css'  media='screen,projection'/>
            
            <!--Let browser know website is optimized for mobile-->
            <meta name='viewport' content='width=device-width, initial-scale=1.0'/>
        " . $head . " </head>
        <body>
    ";
}

function generateFooter($scripts="") {
    echo "
    <script src='js/jquery.min.js'></script>
    <script src='js/materialize.min.js'></script>
    <script>
        $(document).ready(function() {
            M.AutoInit();
        }
    </script>
    </body>
    </html>
    ";
}