<?php
require_once('bdd.php');

$pdo = null;
try {
    $pdo = new PDO("mysql:host=172.16.119.8;dbname=Noel;charset=utf8", "papanoel", "papanoel");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Échec de la connexion : " . $e->getMessage());
}

$atelier_id = $_POST['atelier_id'] ?? null;
$atelier = null;
$lutins = [];
$cadeaux = [];


// Récupérer la liste des ateliers pour le menu déroulant
$stmt = $pdo->query("SELECT id, nom, capacite_max FROM atelier ORDER BY nom");
$ateliers = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($atelier_id) {
    // Récupérer les infos de l'atelier choisi
    $stmt = $pdo->prepare("SELECT id, nom, capacite_max FROM atelier WHERE id = ?");
    $stmt->execute([$atelier_id]);
    $atelier = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($atelier) {       

        // Récupérer les lutins qui travaillent dans cet atelier (table Asso_8)
        $stmt = $pdo->prepare("
            SELECT l.id, l.nom 
            FROM lutin l
            JOIN Asso_8 a ON a.id_1 = l.id
            WHERE a.id = ?
            ORDER BY l.nom
        ");
        $stmt->execute([$atelier_id]);
        $lutins = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Récupérer les cadeaux fabriqués par ces lutins dans cet atelier
        // On joint planification + cadeaux + nom_cadeau + lutins
        // planification : id, id_cadeau, id_atelier, date_fabrication
        // On doit supposer que chaque lutin est affecté à l'atelier via Asso_8,
        // mais on ne sait pas encore dans ta base comment relier lutins et cadeaux.
        // Si la base ne relie pas cadeau-lutin, on peut juste afficher les cadeaux fabriqués dans cet atelier.

        $stmt = $pdo->prepare("
            SELECT nc.nom AS cadeau_nom, p.date_fabrication
            FROM planification p
            JOIN nom_cadeau nc ON nc.id = p.id_cadeau
            WHERE p.id_atelier = ?
            ORDER BY p.date_fabrication DESC
        ");
        $stmt->execute([$atelier_id]);
        $cadeaux = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <title>Suivi des Ateliers - Le Père Noël Inc.</title>
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/suivi_atelier.css">
</head>
<body>
    <h1>🛠️ Suivi des Ateliers</h1>

    <form method="POST" action="">
        <label for="atelier_id">Choisis un atelier :</label>
        <select id="atelier_id" name="atelier_id" required>
            <option value="">-- Sélectionne un atelier --</option>
            <?php foreach ($ateliers as $a): ?>
                <option value="<?= htmlspecialchars($a['id']) ?>" <?= ($atelier_id == $a['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($a['nom']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Voir l'atelier</button>
    </form>

   <?php if ($atelier): ?>
    <div id="atelier-info">
        <h2>Atelier : <?= htmlspecialchars($atelier['nom']) ?></h2>
        <p><strong>Capacité max :</strong> <?= htmlspecialchars($atelier['capacite_max']) ?></p>

        <h3>Lutins qui y travaillent :</h3>
        <?php if (count($lutins) === 0): ?>
            <p class="vide">Aucun lutin affecté à cet atelier.</p>
        <?php else: ?>
            <ul>
                <?php foreach ($lutins as $lutin): ?>
                    <li><?= htmlspecialchars($lutin['nom']) ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <h3>Cadeaux fabriqués dans cet atelier :</h3>
        <?php if (count($cadeaux) === 0): ?>
            <p class="vide">Aucun cadeau fabriqué pour cet atelier.</p>
        <?php else: ?>
            <ul>
                <?php foreach ($cadeaux as $cadeau): ?>
                    <li>
                        🎁 <?= htmlspecialchars($cadeau['cadeau_nom']) ?>
                        <span class="date">(fabriqué le <?= htmlspecialchars($cadeau['date_fabrication']) ?>)</span>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
<?php endif; ?>

</body>
</html>
