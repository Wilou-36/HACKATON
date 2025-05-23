<?php
require_once('bdd.php');

// Connexion PDO
try {
    $pdo = new PDO("mysql:host=172.16.119.8;dbname=Noel;charset=utf8", "papanoel", "papanoel");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

$lutinId = $_GET['lutin'] ?? null;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <title>Suivi des lutins - Le Père Noël Inc.</title>
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/suivi_atelier.css">

</head>
<body>
    <h1>🧝 Suivi des lutins</h1>

    <form method="GET" action="">
        <label for="lutin">Choisir un lutin :</label>
        <select name="lutin" id="lutin" onchange="this.form.submit()">
            <option value="">-- Sélectionnez un lutin --</option>
            <?php
            // Récupérer tous les lutins
            $stmt = $pdo->query("SELECT id, nom FROM lutin ORDER BY nom");
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $selected = ($lutinId == $row['id']) ? 'selected' : '';
                echo "<option value=\"" . htmlspecialchars($row['id']) . "\" $selected>" . htmlspecialchars($row['nom']) . "</option>";
            }
            ?>
        </select>
    </form>

<?php if ($lutinId): ?>
    <div id="lutin-info">
        <?php
        // Récupérer l'atelier(s) du lutin
        $sqlAtelier = "SELECT a.id, a.nom FROM atelier a
                    JOIN Asso_8 ass ON a.id = ass.id
                    WHERE ass.id_1 = :lutin_id";
        $stmtAtelier = $pdo->prepare($sqlAtelier);
        $stmtAtelier->execute(['lutin_id' => $lutinId]);
        $ateliers = $stmtAtelier->fetchAll(PDO::FETCH_ASSOC);

        if (!$ateliers) {
            echo "<p class='vide'>Ce lutin n'est affecté à aucun atelier.</p>";
        } else {
            echo "<h2>Atelier(s) du lutin :</h2><ul>";
            foreach ($ateliers as $atelier) {
                echo "<li>" . htmlspecialchars($atelier['nom']) . "</li>";
            }
            echo "</ul>";

            foreach ($ateliers as $atelier) {
                echo "<h3>Cadeaux fabriqués dans l'atelier '" . htmlspecialchars($atelier['nom']) . "' :</h3>";

                $sqlCadeaux = "SELECT nc.nom AS cadeau_nom, p.date_fabrication
                            FROM planification p
                            JOIN nom_cadeau nc ON p.id_cadeau = nc.id
                            WHERE p.id_atelier = :atelier_id
                            ORDER BY p.date_fabrication DESC";

                $stmtCadeaux = $pdo->prepare($sqlCadeaux);
                $stmtCadeaux->execute(['atelier_id' => $atelier['id']]);
                $cadeaux = $stmtCadeaux->fetchAll(PDO::FETCH_ASSOC);

                if (!$cadeaux) {
                    echo "<p class='vide'>Aucun cadeau fabriqué dans cet atelier.</p>";
                } else {
                    echo "<ul>";
                    foreach ($cadeaux as $cadeau) {
                        echo "<li>🎁 " . htmlspecialchars($cadeau['cadeau_nom']) . " <span class='date'>(fabriqué le " . htmlspecialchars($cadeau['date_fabrication']) . ")</span></li>";
                    }
                    echo "</ul>";
                }
            }
        }
        ?>
    </div>
<?php endif; ?>


</body>
</html>
