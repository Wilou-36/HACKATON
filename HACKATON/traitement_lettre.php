<?php
require_once('bdd.php');
$bdd = new BDD();
$nom = $_POST['nom'];
$prenom = $_POST['prenom'];
$adresse = $_POST['adresse'];
$pays = $_POST['pays'];
$ville = $_POST['ville'];
$souhait = $_POST['souhait']; // ex: "PS5"
$sage = true; // on suppose qu’il est gentil

$bdd = new BDD();
if (!$bdd->connexion()) {
    die("Erreur de connexion à la base de données.");
}
$conn = $bdd->mysqli;

// Protection des entréesss
$nom = htmlspecialchars($nom);
$prenom = htmlspecialchars($prenom);
$adresse = htmlspecialchars($adresse);
$pays = htmlspecialchars($pays);
$ville = htmlspecialchars($ville);
$souhait = htmlspecialchars($souhait);

// 1. Trouver ou insérer la coordonnée
$stmt = $conn->prepare("SELECT id FROM coordonée WHERE pays = ? AND ville = ?");
$stmt->bind_param("ss", $pays, $ville);
$stmt->execute();
$result = $stmt->get_result();
$coord = $result->fetch_assoc();

if (!$coord) {
    $stmt = $conn->prepare("INSERT INTO coordonée (pays, ville) VALUES (?, ?)");
    $stmt->bind_param("ss", $pays, $ville);
    $stmt->execute();
    $id_coord = $conn->insert_id;
} else {
    $id_coord = $coord['id'];
}

// 2. Ajouter l'enfant
$stmt = $conn->prepare("INSERT INTO Enfant (Nom, Prenom, adresse, id_1) VALUES (?, ?, ?, ?)");
$stmt->bind_param("sssi", $nom, $prenom, $adresse, $id_coord);
$stmt->execute();
$id_enfant = $conn->insert_id;

// 3. Récupérer l’ID du cadeau souhaité
$stmt = $conn->prepare("SELECT id FROM nom_cadeau WHERE nom = ?");
$stmt->bind_param("s", $souhait);
$stmt->execute();
$result = $stmt->get_result();
$cadeau = $result->fetch_assoc();

if (!$cadeau) {
    echo "Le père noël et ses lutins ne fabriquent pas ce cadeau.";
    exit;
}
$id_cadeau = $cadeau['id'];

// 4. Associer dans enfant_gentil
$stmt = $conn->prepare("INSERT INTO enfant_gentil (id_enfant, id_cadeau, sage) VALUES (?, ?, ?)");
$stmt->bind_param("iis", $id_enfant, $id_cadeau, $sage);
$stmt->execute();

// 5. Log dans syslog
$message = "Lettre créée pour $prenom $nom - Souhait: $souhait - " . date("Y-m-d H:i:s");
openlog("perenoel", LOG_PID | LOG_PERROR, LOG_LOCAL0);
syslog(LOG_INFO, $message);
closelog();

echo "🎄 Lettre enregistrée et cadeau souhaité : $souhait !";

$bdd->deconnexion();
?>
