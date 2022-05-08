<?php

    namespace api;

    require_once ('./lib/ResponseHeaders.php');
    require_once('./lib/Connection.php');

    //use lib;

    $connection = new \lib\Connection();
    $connection->open();
    try {
        $stmt = $connection->getConnection()->prepare('SELECT * FROM contacte');
        
        $stmt->execute();
        $contactes = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
        $response["status"] = "success";
        $response["data"]["contactes"] = $contactes;
        echo json_encode($response);
        
    } catch (\Exception $e) {
       
        $error["status"] = "error";
        $error["message"] =  $e->getMessage();
        echo json_encode($error);
 
     }  

?>