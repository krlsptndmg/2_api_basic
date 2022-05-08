<?php
 
    /**
     * Mostra un contacte
     * .../get-contacte.php?id=12
     */

    namespace api;
    require_once ('./lib/ResponseHeaders.php');
    require_once('./lib/Connection.php');

       
    $connection = new \lib\Connection();
    $connection->open();
    try {
        $id = null;
        if ( isset($_REQUEST['id']) ){
            $id = $_REQUEST['id'];
        } else {
            throw new \Exception( "identificador no informat") ;     
        }
        $stmt = $connection->getConnection()->prepare('SELECT * FROM contacte WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $contacte = $stmt->fetch();
        // cap contacte trobat per l'id indicat
        if(!$contacte) throw new \Exception( sprintf("Contacte %s no trobat.",$id) );

        // contacte trobat
        $response["status"] = "success";
        $response["data"]["contacte"] = $contacte;
        echo json_encode($response);

    } catch (\Exception $e) {
       
       $error["status"] = "error";
       $error["message"] =  $e->getMessage();
       echo json_encode($error);

    }   
      

?>