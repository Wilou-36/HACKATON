<?php
require_once('bdd.php');

$pdo = null;
try {
    $pdo = new PDO("mysql:host=172.16.119.8;dbname=Noel;charset=utf8", "papanoel", "papanoel");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Échec de la connexion : " . $e->getMessage());
}

$livraisons = [];
$dateChoisie = null;

if (isset($_POST['date_livraison']) && !empty($_POST['date_livraison'])) {
    $dateChoisie = $_POST['date_livraison'];

    // Requête pour récupérer toutes les livraisons du jour avec enfants, cadeaux et adresse
    $sql = "
    SELECT l.id AS livraison_id, l.date_livraison, l.date_arrive,
           e.id AS enfant_id, e.nom AS enfant_nom, e.prenom AS enfant_prenom,
           nc.nom AS cadeau_nom,
           c.rue, c.ville, c.pays
    FROM livraison l
    JOIN cadeau_livraison cl ON cl.livraison_id = l.id
    JOIN enfant e ON e.id = cl.enfant_id
    JOIN nom_cadeau nc ON nc.id = cl.cadeau_id
    JOIN coordonnee c ON c.id = e.adresse
    WHERE l.date_livraison = ?
    ORDER BY l.id, e.nom, nc.nom
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$dateChoisie]);
    $livraisons = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <title>Suivi des Livraisons - Le Père Noël Inc.</title>
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/suivi_atelier.css">
</head>
<body>
    <h1>📦 Suivi des Livraisons</h1>

    <form method="POST" action="">
        <label for="date_livraison">Choisis une date :</label>
        <input type="date" id="date_livraison" name="date_livraison" value="<?= htmlspecialchars($dateChoisie ?? '') ?>" required />
        <button type="submit">Voir les livraisons</button>
    </form>

    <?php if ($dateChoisie): ?>
        <h2>Livraisons prévues pour le <?= htmlspecialchars($dateChoisie) ?></h2>

        <?php if (empty($livraisons)): ?>
            <p>Aucune livraison prévue ce jour.</p>
        <?php else: ?>
            <?php
            // On groupe par livraison_id pour afficher proprement
            $grouped = [];
            foreach ($livraisons as $row) {
                $grouped[$row['livraison_id']]['dates'] = [
                    'date_livraison' => $row['date_livraison'],
                    'date_arrive' => $row['date_arrive']
                ];
                $grouped[$row['livraison_id']]['enfants'][] = [
                    'nom' => $row['enfant_nom'],
                    'prenom' => $row['enfant_prenom'],
                    'cadeau' => $row['cadeau_nom'],
                    'adresse' => $row['rue'] . ', ' . $row['ville'] . ', ' . $row['pays']
                ];
            }
            ?>

            <?php foreach ($grouped as $livraison_id => $data): ?>
                <section style="border:1px solid #ccc; margin:1em 0; padding:1em;">
                    <h3>Livraison #<?= $livraison_id ?></h3>
                    <p><strong>Date de livraison :</strong> <?= htmlspecialchars($data['dates']['date_livraison']) ?></p>
                    <p><strong>Date d'arrivée prévue :</strong> <?= htmlspecialchars($data['dates']['date_arrive']) ?></p>
                    <h4>Enfants et cadeaux :</h4>
                    <ul>
                        <?php foreach ($data['enfants'] as $enfant): ?>
                            <li>
                                <strong><?= htmlspecialchars($enfant['prenom'] . ' ' . $enfant['nom']) ?></strong> -
                                Cadeau : <?= htmlspecialchars($enfant['cadeau']) ?><br />
                                Adresse : <?= htmlspecialchars($enfant['adresse']) ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </section>
            <?php endforeach; ?>

        <?php endif; ?>
    <?php endif; ?>

</body>
</html>
