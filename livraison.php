<?php
require_once('bdd.php');
$bdd = new BDD();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($bdd->connexion()) {
        $mysqli = $bdd->mysqli;

        $enfant_id = $_POST['enfant_id'];
        $cadeau_id = $_POST['cadeau_id'];

        // Vérifie que l'enfant a bien reçu ce cadeau
        $stmt = $mysqli->prepare("
            SELECT e.nom, e.prenom, nc.nom 
            FROM enfant e
            JOIN cadeau c ON e.id = c.enfant_id
            JOIN nom_cadeau nc ON c.cadeau_id = nc.id
            WHERE e.id = ? AND nc.id = ?
        ");
        $stmt->bind_param("ii", $enfant_id, $cadeau_id);
        $stmt->execute();
        $stmt->bind_result($nom, $prenom, $cadeau_nom);

        if ($stmt->fetch()) {
            $datetime = date('Y-m-d H:i:s');

            // Journalisation dans Syslog
            openlog("PereNoelApp", LOG_PID | LOG_PERROR, LOG_LOCAL0);
            syslog(LOG_INFO, "[$datetime] Livraison : $prenom $nom a reçu le cadeau '$cadeau_nom'");
            closelog();

            echo "Livraison enregistrée dans les logs !";
        } else {
            echo "Aucun enregistrement trouvé pour cette combinaison enfant/cadeau.";
        }
    } else {
        echo "Connexion échouée.";
    }
}
?>

<form method="POST">
    <input type="number" name="enfant_id" placeholder="ID de l'enfant" required>
    <input type="number" name="cadeau_id" placeholder="ID du cadeau" required>
    <button type="submit">Valider la livraison</button>
</form>
