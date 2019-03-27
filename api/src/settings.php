<?php
include("localDB.php");

return [
    'settings' => [
        'displayErrorDetails' => true, // set to false in production
        'addContentLengthHeader' => false, // Allow the web server to send the content-length header

        // Renderer settings
        'renderer' => [
            'template_path' => __DIR__ . '/../templates/',
        ],

        // Monolog settings
        'logger' => [
            'name' => 'slim-app',
            'path' => __DIR__ . '/../logs/app.log',
            'level' => \Monolog\Logger::DEBUG,
        ],

        // MySQL Remote Database
        "db_local" => [
            //"dsn" => "dblib:dbname=ndmbuilders;host=localhost",
            "host" => $hostname,
            "dbname" => $dbname,
            "user" => $username,
            "pass" => $password
        ],
    ],
];
