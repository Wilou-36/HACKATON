<?php
require_once('bdd.php');

if (
    isset($_POST['nom'], $_POST['prenom'], $_POST['rue'], $_POST['ville'], $_POST['pays'], $_POST['souhait'])
) {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $rue = $_POST['rue'];
    $ville = $_POST['ville'];
    $pays = $_POST['pays'];
    $souhait = $_POST['souhait'];

    try {
        // 1. Insertion adresse
        $stmt = $pdo->prepare("INSERT INTO coordonnee (rue, ville, pays) VALUES (?, ?, ?)");
        $stmt->execute([$rue, $ville, $pays]);
        $coordonnee_id = $pdo->lastInsertId();

        // 2. Insertion enfant
        $stmt = $pdo->prepare("INSERT INTO enfant (nom, prenom, adresse) VALUES (?, ?, ?)");
        $stmt->execute([$nom, $prenom, $coordonnee_id]);
        $enfant_id = $pdo->lastInsertId();

        // 3. Utiliser l'ID du cadeau sélectionné
        $cadeau_id = $souhait_id;


        // 4. Lier l'enfant au cadeau dans la table souhait
        $stmt = $pdo->prepare("INSERT INTO souhait (enfant_id, cadeau_id) VALUES (?, ?)");
        $stmt->execute([$enfant_id, $cadeau_id]);

        echo "<p>🎄 La lettre a bien été envoyée au Père Noël !</p>";
    } catch (PDOException $e) {
        echo "<p>❌ Erreur lors de l'envoi de la lettre : " . $e->getMessage() . "</p>";
    }
} else {
    echo "<p>Veuillez remplir tous les champs.</p>";
}
?>
