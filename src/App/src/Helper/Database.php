<?php

namespace App\Helper;

use PDO;

class Database
{
    private $host = "";
    private $username = "";
    private $port = "";
    private $password = "";
    private $database = "";
    private $connection = null;

    public function __construct(
        $host = "localhost",
        $port = "3306",
        $username = "root",
        $password = "",
        $database = "booking_database"
    ) {
        $this->host = $host;
        $this->port = $port;
        $this->username = $username;
        $this->password = $password;
        $this->database = $database;
    }

    /**
     * Create PDO connection
     * @return PDO|null
     */
    public function getConnection()
    {
        try {
            $this->connection = new PDO(
                "mysql:host=" . $this->host . ":" . $this->port . ";dbname=" . $this->database,
                $this->username,
                $this->password
            );
            $this->connection->exec("set names utf8_unicode_ci");
        } catch (\PDOException $e) {
            echo "Error: " . $e->getMessage();
        }

        return $this->connection;
    }
}
