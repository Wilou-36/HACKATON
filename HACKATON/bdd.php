<?php
    class BDD {
        public $password = 'papanoel';
        public $username = 'papanoel';
        public $servername = '192.168.10.10';
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

        public function getenfants()
        {

       
            /* récupération de la liste des enfants*/
            $sql = "SELECT e.id, e.nom, e.prenom, c.pays, c.ville
                    FROM enfant e
                    JOIN coordonnee c ON e.adresse = c.id";

            $result = $this->mysqli->query($sql);

            // Affichage des résultats
            if ($result && $result->num_rows > 0) {
                echo "<h2>Liste des enfants</h2>";
                echo "<ul>";
                while ($row = $result->fetch_assoc()) {
                    echo "<li>" . htmlspecialchars($row["prenom"]) . " " . htmlspecialchars($row["nom"]) .
                        " — " . htmlspecialchars($row["ville"]) . ", " . htmlspecialchars($row["pays"]) . "</li>";
                }
                echo "</ul>";
            } else {
                echo "Aucun enfant trouvé.";
            } 
        }
    }