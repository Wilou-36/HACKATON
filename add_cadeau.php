<?php
require_once('bdd.php');
$bdd = new BDD();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($bdd->connexion()) {
        $mysqli = $bdd->mysqli;

        $nom = $_POST['nom'];
        $type = $_POST['type'];
        $enfant_id = $_POST['enfant_id'];

        // Ajouter le cadeau dans nom_cadeau
        $stmt = $mysqli->prepare("INSERT INTO nom_cadeau (id, nom, type) VALUES (NULL, ?, ?)");
        $stmt->bind_param("ss", $nom, $type);
        $stmt->execute();

        $cadeau_id = $mysqli->insert_id;

        // Associer le cadeau à l'enfant
        $stmt2 = $mysqli->prepare("INSERT INTO cadeau (enfant_id, cadeau_id) VALUES (?, ?)");
        $stmt2->bind_param("ii", $enfant_id, $cadeau_id);
        $stmt2->execute();

        echo "Cadeau ajouté et assigné à l'enfant.";
    } else {
        echo "Erreur de connexion à la base de données.";
    }
}
?>

<form method="POST">
    <input type="text" name="nom" placeholder="Nom du cadeau" required>
    <input type="text" name="type" placeholder="Type de cadeau" required>
    <input type="number" name="enfant_id" placeholder="ID de l'enfant" required>
    <button type="submit">Ajouter le cadeau</button>
</form>
