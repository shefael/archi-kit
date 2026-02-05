<?php
// Fichier: config/database.php

$host = 'localhost';
$db_name = 'boutique_whatsapp';
$username = 'root'; // À modifier selon ton serveur (ex: 'root' sur XAMPP)
$password = '';     // À modifier selon ton serveur

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db_name;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>