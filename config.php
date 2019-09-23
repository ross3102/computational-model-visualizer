<?php

$web_root = "computational-model-visualizer";

$app_title = "Computational Model Visualizer";

$db_host = "localhost";

$db_name = "computational-model-visualizer";

$username = "comp-model-vis";

$password = "leoger";

$dsn = 'mysql:host='. $db_host . ';dbname=' . $db_name;

$options = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION);