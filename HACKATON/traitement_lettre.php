<?php
require_once('bdd.php');
$bdd = new BDD();

$nom = $_POST['nom'];
$prenom = $_POST['prenom'];
$adresse = $_POST['adresse'];
$pays = $_POST['pays'];
$ville = $_POST['ville'];
$souhait = $_POST['souhait'];
$sage = true;

if (!$bdd->connexion()) {
    die("Erreur de connexion à la base de données.");
}
$conn = $bdd->mysqli;

// Sécurité
$nom = htmlspecialchars($nom);
$prenom = htmlspecialchars($prenom);
$adresse = htmlspecialchars($adresse);
$pays = htmlspecialchars($pays);
$ville = htmlspecialchars($ville);
$souhait = htmlspecialchars($souhait);

// 1. Coordonnée
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

// 2. Enfant
$stmt = $conn->prepare("INSERT INTO Enfant (Nom, Prenom, adresse, id_1) VALUES (?, ?, ?, ?)");
$stmt->bind_param("sssi", $nom, $prenom, $adresse, $id_coord);
$stmt->execute();
$id_enfant = $conn->insert_id;

// 3. Cadeau
$stmt = $conn->prepare("SELECT id FROM nom_cadeau WHERE nom = ?");
$stmt->bind_param("s", $souhait);
$stmt->execute();
$result = $stmt->get_result();
$cadeau = $result->fetch_assoc();

if (!$cadeau) {
    echo "Le Père Noël ne fabrique pas ce cadeau.";
    exit;
}
$id_cadeau = $cadeau['id'];

// 4. Lien enfant/cadeau
$stmt = $conn->prepare("INSERT INTO enfant_gentil (id_enfant, id_cadeau, sage) VALUES (?, ?, ?)");
$stmt->bind_param("iid", $id_enfant, $id_cadeau, $sage);
$stmt->execute();

// 5. Log
$message = "Lettre créée pour $prenom $nom - Souhait: $souhait - " . date("Y-m-d H:i:s");
openlog("perenoel", LOG_PID | LOG_PERROR, LOG_LOCAL0);
syslog(LOG_INFO, $message);
closelog();

// Réponse
echo "<h2>🎄 Lettre enregistrée pour $prenom $nom !</h2>";
echo "<p>Cadeau souhaité : <strong>$souhait</strong></p>";
echo "<p><a href='index.php'>🔙 Retour à l'accueil</a></p>";

$bdd->deconnexion();
?>
