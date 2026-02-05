<?php
// Fichier: admin/delete_product.php
session_start();
if (!isset($_SESSION['admin_logged_in'])) { header('Location: login.php'); exit; }
require_once '../config/database.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Optionnel : Supprimer l'image du dossier pour ne pas encombrer le serveur
    $stmt = $pdo->prepare("SELECT image FROM products WHERE id = ?");
    $stmt->execute([$id]);
    $product = $stmt->fetch();
    
    if ($product && file_exists("../public/assets/images/" . $product['image'])) {
        // On ne supprime pas l'image par défaut "default.jpg" si tu en as une
        if ($product['image'] !== 'default.jpg') {
            unlink("../public/assets/images/" . $product['image']);
        }
    }

    // Suppression en base de données
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([$id]);
}

// Retour au tableau de bord
header("Location: dashboard.php");
exit;
?>