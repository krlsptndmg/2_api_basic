<?php

    /**
     * Delete contacte 
     * 
     */

    namespace api;

    require_once ('./lib/ResponseHeaders.php');
    require_once('./lib/Connection.php');
    
    try {
        $id = getIdFromRequest();
       
        $response = delete($id);
        echo json_encode($response);
    
    } catch (\Exception $e) {
        
        $error["status"] = "error";
        $error["message"] =  $e->getMessage();
        echo json_encode($error);
    }   
    
    /**
     * 
     */
    function getIdFromRequest()
    {
        $method = $_SERVER["REQUEST_METHOD"];
        if ( $method != 'DELETE' ) throw new \Exception("Petició incorrecte");

        if ( isset($_REQUEST['id']) ){
            return  $_REQUEST['id'];
        } else {
            throw new \Exception( "identificador no informat") ;     
        }
    }
  

    /**
     * Execució delete
     */
    function delete($id)
    {
        // CONNEXIÓ
        $connection = new \lib\Connection();
        $connection->open();

        // EXECUCIÓ UPDATE
        $idCreated = null;
        $connection->getConnection()->beginTransaction();
        $sql = "DELETE FROM contacte
                WHERE id = :id";    
                 
        $connection->getConnection()->prepare($sql)->execute( [
            'id' => $id,
        ]);
        
        // Commit transacció
        $connection->getConnection()->commit();

        // RESPOSTA
        $response["status"] = "success";
       
        return $response;
    }

?>