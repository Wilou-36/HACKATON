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
    <input type="text" name="adresse" placeholder="Adresse" required><br>
    <input type="text" name="ville" placeholder="Ville" required><br>
    <input type="text" name="pays" placeholder="Pays" required><br>
    <input type="text" name="souhait" placeholder="Quel cadeau veux-tu ?" required><br>
    <button type="submit">Envoyer la lettre</button>
  </form>
</body>
</html>



