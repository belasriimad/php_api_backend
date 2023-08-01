<?php

namespace App\Database;
use PDO;

class Database 
{
    private $servername = 'localhost';
    private $username = 'root';
    private $password = '';
    private $dbname = 'php_events';

    public function connect()
    {
        try {
            $db = new PDO("mysql:host=$this->servername;dbname=$this->dbname",
            $this->username, $this->password);
            $db->exec("set names utf8");
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $db;
        } catch (PDOException $e) {
            echo "Connection failed ". $e->getMessage();
        }
    }
}
