<?php
require_once('bdd.php');
$bdd = new BDD();

$id_cadeau = $_POST['cadeau'];
$id_atelier = $_POST['atelier'];
$date = $_POST['date'];

if (!$bdd->connexion()) {
    die("Erreur de connexion à la base de données.");
}
$conn = $bdd->mysqli;

$stmt = $conn->prepare("SELECT COUNT(*) AS total FROM planification WHERE id_atelier = ? AND date_fabrication = ?");
$stmt->bind_param("is", $id_atelier, $date);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

$charge_max = 10; 

if ($data['total'] < $charge_max) {
    $stmt = $conn->prepare("INSERT INTO planification (id_cadeau, id_atelier, date_fabrication) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $id_cadeau, $id_atelier, $date);
    $stmt->execute();

    echo "<h2>✅ Cadeau planifié avec succès !</h2>";
} else {
    echo "<h2>❌ L'atelier est déjà plein pour cette date.</h2>";
}

echo "<p><a href='index.php'>🔙 Retour à l'accueil</a></p>";

$bdd->deconnexion();
?>
