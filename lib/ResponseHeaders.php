<?php
    header('content-type: application/json; charset=utf-8');
    $allowedOrigin = "http://localhost:3000";
    header("Access-Control-Allow-Origin: $allowedOrigin");
    header("Access-Control-Allow-Headers: content-type");
    header("Access-Control-Allow-Methods: OPTIONS,GET,PUT,POST,DELETE");
    header('content-type: application/json; charset=utf-8');
?>