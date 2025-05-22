<?php
    class BDD {
        private $password = 'noel';
        private $username = 'noel';
        private $servername = '192.168.10.20';
        private $dbname = 'Noel';
        private $port= 3306;

        public $mysqli;
        private $sql;
        private $conn;

        public function __construct() {
            $this -> mysqli = false;
        }

        /* Connexion à la base de données */
        public function connexion() {
            mysqli_report(MYSQLI_REPORT_OFF);
    
            $this->mysqli = new mysqli($this->servername, $this->username, $this->password, $this->dbname, $this->port);

            if ($this->mysqli->connect_errno != 0) {
                echo "Erreur de connexion MySQL : " . $this->mysqli->connect_error;
            return false;
            } else {
                return true;
            }
        }
        
        
        /* Déconnexion à la base de données */
        public function deconnexion() {
            if($this -> mysqli -> connect_errno != 0) {
                $this -> mysqli -> close();
            }
        }
    }

