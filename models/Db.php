<?php

namespace models;

class Db
{
    private static $instance = NULL;
    private static $rdb = "mysql";
    private static $host = "localhost";
    private static $dbname = "test";
    private static $user = "root";
    private static $pass = "";
	
	public function __construct()
	{
		echo "<br>Database is working...";
	}

	public static function connect(){
		if (!isset(self::$instance)) {

            $pdo_options[\PDO::ATTR_ERRMODE] = \PDO::ERRMODE_EXCEPTION;
            
            self::$instance = new \PDO(
            	self::$rdb.":host=".self::$host.";dbname=".self::$dbname, 
            	self::$user, 
            	self::$pass,
            	$pdo_options
            );
        }
        return self::$instance;	
	}

}

