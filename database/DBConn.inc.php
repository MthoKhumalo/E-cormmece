<?php

    class DatabaseConnection
    {
        private static $instance = null;
        private $pdo;

        private function __construct()
        {
            $dsn = 'mysql:host=localhost;dbname=computer_complex';
            $dbusername = 'root';
            $dbpassword = 'cS4FJ?';

            try {
                $this->pdo = new PDO($dsn, $dbusername, $dbpassword);
                $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Database connection failed: " . $e->getMessage());
            }
        }

        public static function getInstance()
        {
            if (self::$instance === null) {
                self::$instance = new DatabaseConnection();
            }
            return self::$instance;
        }

        public function getConnection()
        {
            return $this->pdo;
        }
    }

    /*set_time_limit(300);

    try {
        // Set up the DSN for connecting to the Azure SQL Server
        $dsn = "sqlsrv:server = tcp:disappdd.database.windows.net,1433; Database = ComputerCmplex";
        $dbusername = "st10107568";
        $dbpassword = "dianaK1209$";

        // Establish the connection using PDO with error handling attributes
        $pdo = new PDO($dsn, $dbusername, $dbpassword);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        //Set the connection timeout to 200 second
        //$pdo->setAttribute(PDO::ATTR_TIMEOUT, 200);

    } catch (PDOException $e) {

        // Display a connection error message
        echo "Error connecting to SQL Server: " . $e->getMessage();
    }*/