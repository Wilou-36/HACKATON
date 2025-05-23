<?php
require_once("bdd.php");

$stmt = $pdo->prepare("SELECT id, nom FROM nom_cadeau");
$stmt->execute();
$cadeaux = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Lettre au Père Noël</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <h1>🎅 Écris ta lettre au Père Noël</h1>
  <form method="POST" action="traitement_lettre.php">
    <input type="text" name="nom" placeholder="Nom" required><br>
    <input type="text" name="prenom" placeholder="Prénom" required><br>
    <input type="text" name="rue" placeholder="Adresse" required><br>
    <input type="text" name="ville" placeholder="Ville" required><br>
    <input type="text" name="pays" placeholder="Pays" required><br>
    <label for="souhait">Quel cadeau veux-tu ?</label>
    <select name="souhait" id="souhait" required>
        <option value="">-- Choisis un cadeau --</option>
        <?php
        require_once("bdd.php");
        $stmt = $pdo->prepare("SELECT id, nom FROM nom_cadeau");
        $stmt->execute();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<option value='" . htmlspecialchars($row['id']) . "'>" . htmlspecialchars($row['nom']) . "</option>";
        }
        ?>
    </select>
    <button type="submit">Envoyer la lettre</button>
  </form>
</body>
</html>
