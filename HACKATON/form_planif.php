<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Planification d'un cadeau</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <h1>🛠️ Planifier un cadeau dans un atelier</h1>
  <form method="POST" action="planification.php">
    <label>Cadeau :</label>
    <select name="cadeau" required>
      <option value="1">Trottinette</option>
      <option value="2">Lego Star Wars</option>
      <!-- Ajoute d'autres cadeaux ici -->
    </select><br><br>

    <label>Atelier :</label>
    <select name="atelier" required>
      <option value="1">Atelier Nord</option>
      <option value="2">Atelier Sud</option>
      <!-- Ajoute d'autres ateliers ici -->
    </select><br><br>

    <label>Date :</label>
    <input type="date" name="date" required><br><br>

    <button type="submit">Planifier 🎁</button>
  </form>
</body>
</html>
