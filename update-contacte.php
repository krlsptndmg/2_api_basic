<?php

    /**
     * Actualitza contacte 
     * 
     */

    namespace api;

    require_once ('./lib/ResponseHeaders.php');
    require_once('./lib/Connection.php');
    
    try {
        $id = getIdFromRequest();
        $requestData = getDataFromRequest();
        $response = update($id,$requestData);
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
        if ( isset($_REQUEST['id']) ){
            return  $_REQUEST['id'];
        } else {
            throw new \Exception( "identificador no informat") ;     
        }
    }

    /**
     * Extracció paràmetres insert
     */
    function getDataFromRequest()
    {
        
        $requestData = null;
        $method = $_SERVER["REQUEST_METHOD"];
        if ( $method != 'PUT' ) throw new \Exception("Petició incorrecte");

        // cas on data bé dins de params
        if (isset($_REQUEST['data'])) {
            $requestData =  json_decode(stripslashes($_REQUEST['data']));
        } else {

            // cas on data bé dins de body
            $raw  = '';
            $httpContent = fopen('php://input', 'r');
            while ($kb = fread($httpContent, 1024)) {
                $raw .= $kb;
            }

            // json_decode retorna array associatiu
            $params = json_decode(stripslashes($raw),true);
            if( isset ($params['data']) ){
                $requestData = $params['data'];	
            }
        }
        return $requestData;
    }

    /**
     * Execució insert segons requesData
     */
    function update($id,$requestData)
    {
        // CONNEXIÓ
        $connection = new \lib\Connection();
        $connection->open();

        // EXECUCIÓ UPDATE
        $idCreated = null;
        $connection->getConnection()->beginTransaction();
        $sql = "UPDATE contacte
                SET nom = :nom,
                    cognom1 = :cognom1,
                    cognom2 = :cognom2,
                    telefon = :telefon,
                    email = :email
                WHERE id = :id";    
                 
        $connection->getConnection()->prepare($sql)->execute( [
            'id' => $id,
            'nom' => $requestData['nom'],
            'cognom1' => $requestData['cognom1'],
            'cognom2' => $requestData['cognom2'],
            'telefon' => $requestData['telefon'],
            'email' => $requestData['email'],
        ]);
        
        // Commit transacció
        $connection->getConnection()->commit();

        // RESPOSTA
        $response["status"] = "success";
       
        return $response;
    }

?>