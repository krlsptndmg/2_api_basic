<?php

namespace lib;

class Connection {
	
	private $connection;
	
	public function __construct(){
	}
	
	public function __destruct(){
        
		$this->close();	
	}
	
    /**
     * Obrir connexió
     * 
     */
	public function open()
	{

        $host = 'localhost';
        $db   = 'bootcamp_backend';
        $user = 'root';
        $pass = '';
        $charset = 'utf8mb4';

        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
        $options = [
            \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            \PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        try {
            $this->connection = new \PDO($dsn, $user, $pass, $options);
           
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), (int)$e->getCode());
        }
	
	}
	
    /**
     * Tanca la connexió
     */
	public function close()
	{
		
		$this->connection = null;
		
	}
	
    /**
     * Retorna connexió creada
     */
	public function getConnection() {
		return $this->connection;
	}
}