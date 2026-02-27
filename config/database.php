<?php
// Fichier: config/database.php

$host = 'localhost';
$db_name = 'boutique_whatsapp';
$username = 'root'; // À modifier selon ton serveur (ex: 'root' sur XAMPP)
$password = '';     // À modifier selon ton serveur

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db_name;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    
    // Définir le fuseau horaire
    $pdo->exec("SET time_zone = '+01:00'");
    
} catch(PDOException $e) {
    // Log plus détaillé pour le développement
    error_log("Erreur de connexion BDD: " . $e->getMessage());
    die("Erreur de connexion à la base de données. Veuillez réessayer plus tard.");
}

// Fonction utilitaire pour exécuter des requêtes en toute sécurité
function executeQuery($pdo, $sql, $params = []) {
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    } catch(PDOException $e) {
        error_log("Erreur SQL: " . $e->getMessage());
        throw $e;
    }
}

// Fonction pour obtenir les paramètres de site
function getSiteSettings($pdo) {
    static $settings = null;
    if ($settings === null) {
        $stmt = $pdo->query("SELECT * FROM settings WHERE id = 1");
        $settings = $stmt->fetch() ?: [
            'site_name' => 'Archi-Kit',
            'currency' => 'XOF',
            'delivery_fee' => 0,
            'free_delivery_min' => 150000
        ];
    }
    return $settings;
}
?>