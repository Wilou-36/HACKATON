<?php
    require_once('bdd.php');
    $bdd = new BDD();

    if ($db->connexion()) {
        $db->getEnfants();
        $db->deconnexion();
    } else {
        echo "Connexion échouée.";
    }

    ?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>🎅 Le Père Noël Inc. - Accueil</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <h1>🎅 Le Père Noël Inc.</h1>
  <p class="emoji">Bienvenue ! Choisis une action magique ✨</p>

  <div class="menu">
    <a href="form_lettre.php">📬 Écrire une lettre</a>
    <a href="form_planif.php">🛠️ Planifier un cadeau</a>
  </div>

  <footer>
    <p>&copy; 2025 - Le Père Noël Inc. | WIWY Informatics</p>
  </footer>
</body>
</html>
