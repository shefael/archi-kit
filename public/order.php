<?php
// Fichier: public/order.php
session_start();
require_once '../config/database.php';

// CONFIGURATION : TON NUMÉRO WHATSAPP (Ex: 243...)
$my_whatsapp_number = "243000000000"; 

if (empty($_SESSION['cart'])) {
    header('Location: shop.php');
    exit;
}

$ids = implode(',', array_keys($_SESSION['cart']));
$stmt = $pdo->query("SELECT * FROM products WHERE id IN ($ids)");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

$message = "Bonjour, je souhaite commander les articles suivants :\n\n";
$total_par_devise = [];

foreach ($products as $p) {
    $qty = $_SESSION['cart'][$p['id']];
    $subtotal = $p['price'] * $qty;
    $devise = $p['currency'];
    
    $message .= "▪️ " . $p['name'] . "\n";
    $message .= "   Qté : " . $qty . " pc(s)\n";
    $message .= "   Prix : " . number_format($subtotal, 0, ',', ' ') . " " . $devise . "\n\n";
    
    // On groupe les totaux par devise au cas où il y a un mélange
    if (!isset($total_par_devise[$devise])) {
        $total_par_devise[$devise] = 0;
    }
    $total_par_devise[$devise] += $subtotal;
}

$message .= "--------------------------\n";
$message .= "💰 *TOTAL A PAYER :*\n";
foreach ($total_par_devise as $dev => $somme) {
    $message .= "👉 " . number_format($somme, 0, ',', ' ') . " " . $dev . "\n";
}

$url = "https://wa.me/" . $my_whatsapp_number . "?text=" . urlencode($message);

// Vider le panier après la commande
unset($_SESSION['cart']);

header("Location: $url");
exit;