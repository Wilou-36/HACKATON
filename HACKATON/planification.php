<?php
require_once 'bdd.php';

$id_cadeau = $_POST['cadeau'];
$id_atelier = $_POST['atelier'];
$date = $_POST['date'];

$bdd = new BDD();
if (!$bdd->connexion()) {
    die("❌ Erreur de connexion à la base de données.");
}

$pdo = $bdd->pdo;

try {
    // Vérifier la charge pour cette date et cet atelier
    $stmt = $pdo->prepare("SELECT COUNT(*) AS total FROM planification WHERE id_atelier = ? AND date_fabrication = ?");
    $stmt->execute([$id_atelier, $date]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    $charge_max = 10;

    if ($data['total'] < $charge_max) {
        // Insertion planification
        $stmt = $pdo->prepare("INSERT INTO planification (id_cadeau, id_atelier, date_fabrication) VALUES (?, ?, ?)");
        $stmt->execute([$id_cadeau, $id_atelier, $date]);

        echo "<h2>✅ Cadeau planifié avec succès !</h2>";
    } else {
        echo "<h2>❌ L'atelier est déjà complet ce jour-là.</h2>";
    }

    echo "<p><a href='index.php'>🔙 Retour à l'accueil</a></p>";

} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}

$bdd->deconnexion();
?>
