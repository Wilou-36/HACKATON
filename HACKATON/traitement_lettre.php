<?php
require_once 'bdd.php';

$nom = $_POST['nom'];
$prenom = $_POST['prenom'];
$adresse = $_POST['adresse'];
$pays = $_POST['pays'];
$ville = $_POST['ville'];
$souhait = $_POST['souhait'];

$bdd = new BDD();
if (!$bdd->connexion()) {
    die("❌ Erreur de connexion à la base de données.");
}

$pdo = $bdd->pdo;

// Sécurisation
$nom = htmlspecialchars($nom);
$prenom = htmlspecialchars($prenom);
$adresse = htmlspecialchars($adresse);
$pays = htmlspecialchars($pays);
$ville = htmlspecialchars($ville);
$souhait = htmlspecialchars($souhait);

try {
    // 1. Vérifier ou insérer coordonnée
    $stmt = $pdo->prepare("SELECT id FROM coordonnee WHERE pays = ? AND ville = ?");
    $stmt->execute([$pays, $ville]);
    $coord = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$coord) {
        $stmt = $pdo->prepare("INSERT INTO coordonnee (pays, ville) VALUES (?, ?)");
        $stmt->execute([$pays, $ville]);
        $id_coord = $pdo->lastInsertId();
    } else {
        $id_coord = $coord['id'];
    }

    // 2. Ajouter enfant
    $stmt = $pdo->prepare("INSERT INTO enfant (nom, prenom, adresse) VALUES (?, ?, ?)");
    $stmt->execute([$nom, $prenom, $id_coord]);
    $id_enfant = $pdo->lastInsertId();

    // 3. Vérifier cadeau souhaité
    $stmt = $pdo->prepare("SELECT id FROM nom_cadeau WHERE nom = ?");
    $stmt->execute([$souhait]);
    $cadeau = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$cadeau) {
        echo "Le Père Noël ne fabrique pas ce cadeau.";
        exit;
    }
    $id_cadeau = $cadeau['id'];

    // 4. Ajouter enfant_gentil
    $stmt = $pdo->prepare("INSERT INTO enfant_gentil (enfant_id, cadeau_id) VALUES (?, ?)");
    $stmt->execute([$id_enfant, $id_cadeau]);

    // 5. Log
    $message = "Lettre créée pour $prenom $nom - Souhait: $souhait - " . date("Y-m-d H:i:s");
    openlog("perenoel", LOG_PID | LOG_PERROR, LOG_LOCAL0);
    syslog(LOG_INFO, $message);
    closelog();

    echo "<h2>🎄 Lettre enregistrée pour $prenom $nom !</h2>";
    echo "<p>Cadeau souhaité : <strong>$souhait</strong></p>";
    echo "<p><a href='index.php'>🔙 Retour à l'accueil</a></p>";

} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}

$bdd->deconnexion();
?>
