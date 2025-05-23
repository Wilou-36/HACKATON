<?php
$host = '172.16.119.8';
$dbname = 'Noel';
$user = 'papanoel';
$password = 'papanoel';
 
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Échec de la connexion : " . $e->getMessage();
}
 

?>
 