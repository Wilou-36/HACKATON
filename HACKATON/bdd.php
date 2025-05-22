<?php
    class BDD {
        public $password = 'noel';
        public $username = 'noel';
        public $servername = '192.168.10.20';
        public $dbname = 'Noel';
        public $port= 3306;

        public $mysqli;
        public $sql;
        public $conn;

        public function __construct() {
            $this -> mysqli = false;
        }

        /* Connexion à la base de données */
        public function connexion() {
            mysqli_report(MYSQLI_REPORT_OFF);
            
            $this -> mysqli = new mysqli($this->servername, $this->username, $this->password, $this->dbname, $this->port);

            if($this -> mysqli -> connect_errno != 0) {
                return false;
            }
            else {
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