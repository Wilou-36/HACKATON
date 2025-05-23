<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'bdd.php'; // Ce fichier doit contenir directement l’objet $pdo (voir plus bas)

$id_cadeau = $_POST['cadeau'] ?? null;
$id_atelier = $_POST['atelier'] ?? null;
$date = $_POST['date'] ?? null;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Planification Cadeau</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h1>🎄 Planification d'un Cadeau 🎁</h1>

        <?php
        if (!$id_cadeau || !$id_atelier || !$date) {
            echo '<h2 class="error">❌ Données manquantes. Veuillez remplir tous les champs.</h2>';
            echo "<p><a href='index.php'>🔙 Retour à l'accueil</a></p>";
            exit;
        }

        // Assure-toi que $pdo est bien défini dans bdd.php
        if (!$pdo) {
            echo '<h2 class="error">❌ Erreur de connexion à la base de données.</h2>';
            echo "<p><a href='index.php'>🔙 Retour à l'accueil</a></p>";
            exit;
        }

        try {
            $stmt = $pdo->prepare("SELECT COUNT(*) AS total FROM planification WHERE id_atelier = ? AND date_fabrication = ?");
            $stmt->execute([$id_atelier, $date]);
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            $charge_max = 10;

            if ($data['total'] < $charge_max) {
                $stmt = $pdo->prepare("INSERT INTO planification (id_cadeau, id_atelier, date_fabrication) VALUES (?, ?, ?)");
                $stmt->execute([$id_cadeau, $id_atelier, $date]);
                echo '<h2 class="success">✅ Cadeau planifié avec succès !</h2>';
            } else {
                echo '<h2 class="error">❌ L\'atelier est déjà complet ce jour-là.</h2>';
            }

            echo "<p><a href='index.html'>🔙 Retour à l'accueil</a></p>";

        } catch (PDOException $e) {
            echo '<h2 class="error">❌ Erreur : ' . htmlspecialchars($e->getMessage()) . '</h2>';
            echo "<p><a href='index.html'>🔙 Retour à l'accueil</a></p>";
        }
        ?>
    </div>
</body>
</html>
