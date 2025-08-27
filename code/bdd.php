<?php
class BDD {
    private $password = 'papanoel';
    private $username = 'papanoel';
    private $servername = '192.168.10.10';
    private $dbname = 'Noel';
    private $port = 3306;

    private $mysqli;
    public $sql;

    public function __construct() {
        $this->mysqli = false;
    }

    public function connexion() {
        mysqli_report(MYSQLI_REPORT_OFF);
        
        $this->mysqli = new mysqli($this->servername, $this->username, $this->password, $this->dbname, $this->port);

        if ($this->mysqli->connect_errno) {
            echo "Erreur de connexion MySQL : " . $this->mysqli->connect_error;
            return false;
        } else {
            $this->mysqli->set_charset("utf8mb4");
            return true; 
        }
    }

    public function deconnexion() {
        if ($this->mysqli) {
            $this->mysqli->close();
        }
    }

    public function getenfants() {
        $sql = "SELECT enfant.id, enfant.nom, enfant.prenom, coordonée.pays, coordonée.ville
                FROM enfant 
                INNER JOIN coordonée ON enfant.id_1 = coordonée.id";

        $result = $this->mysqli->query($sql);

        if ($result && $result->num_rows > 0) {
            echo "<h2>Liste des enfants</h2><ul>";
            while ($row = $result->fetch_assoc()) {
                echo "<li>" . htmlspecialchars($row["prenom"]) . " " . htmlspecialchars($row["nom"]) .
                    " — " . htmlspecialchars($row["ville"]) . ", " . htmlspecialchars($row["pays"]) . "</li>";
            }
            echo "</ul>";
        } else {
            echo "Aucun enfant trouvé. " . $this->mysqli->error;
        }
    }
}
?>
