<?php

   

    /**
     * Insereix contacte 
     * 
     */ 

    namespace api;

    require_once ('./lib/ResponseHeaders.php');
    require_once('./lib/Connection.php');
    
    try {
        $requestData = getDataFromRequest();
        $response = insert($requestData);
        echo json_encode($response);
    
    } catch (\Exception $e) {
        
        $error["status"] = "error";
        $error["message"] =  $e->getMessage();
        echo json_encode($error);
    }   
    
    /**
     * Extracció paràmetres insert
     */
    function getDataFromRequest()
    {
        
        $requestData = null;
        $method = $_SERVER["REQUEST_METHOD"];
        if ( $method != 'POST' ) throw new \Exception("Petició incorrecte");

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
    function insert($requestData)
    {
        // CONNEXIÓ
        $connection = new \lib\Connection();
        $connection->open();

        // EXECUCIÓ INSERT
        $idCreated = null;
        $connection->getConnection()->beginTransaction();
        $sql = "INSERT INTO contacte(nom,cognom1,cognom2,telefon,email)
                 values(:nom,:cognom1,:cognom2,:telefon,:email)";
        $connection->getConnection()->prepare($sql)->execute( [
            'nom' => $requestData['nom'],
            'cognom1' => $requestData['cognom1'],
            'cognom2' => $requestData['cognom2'],
            'telefon' => $requestData['telefon'],
            'email' => $requestData['email'],
        ]);
        // Es recupera id automàtic creat
        $idCreated = $connection->getConnection()->lastInsertId();
       
        // Commit transacció
        $connection->getConnection()->commit();

        // RESPOSTA
        $response["status"] = "success";
        $response["data"]["id"] = $idCreated;
        return $response;
    }

?>