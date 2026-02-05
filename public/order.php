<?php
// Fichier: public/order.php
session_start();
require_once '../config/database.php';

// CONFIGURATION
$my_whatsapp_number = "243895975137"; // Sans le "+" pour l'API WhatsApp
$currency_symbol = "$"; // Ou "FC" selon ta préférence

// 1. Sécurité : Si panier vide, retour boutique
if (empty($_SESSION['cart'])) {
    header('Location: shop.php');
    exit;
}

// 2. Récupération des produits pour l'affichage (Lecture Seule)
$ids = array_keys($_SESSION['cart']);
// Sécurisation SQL avec des placeholders
$placeholders = implode(',', array_fill(0, count($ids), '?'));
$stmt = $pdo->prepare("SELECT * FROM products WHERE id IN ($placeholders)");
$stmt->execute($ids);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Calcul du total pour l'affichage
$total_order = 0;
foreach ($products as $p) {
    $total_order += $p['price'] * $_SESSION['cart'][$p['id']];
}

// 3. TRAITEMENT DU FORMULAIRE (Si l'utilisateur clique sur "Confirmer la commande")
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Récupération des données client
    $client_name = htmlspecialchars($_POST['name']);
    $client_address = htmlspecialchars($_POST['address']);
    $client_phone = htmlspecialchars($_POST['phone']);

    // Construction du message WhatsApp Stylisé
    $message  = "*NOUVELLE COMMANDE WEB* 🚀\n";
    $message .= "--------------------------------\n";
    $message .= "👤 *Client :* " . $client_name . "\n";
    $message .= "📍 *Adresse :* " . $client_address . "\n";
    $message .= "📞 *Tel :* " . $client_phone . "\n";
    $message .= "--------------------------------\n";
    $message .= "🛒 *Détails du panier :*\n\n";

    foreach ($products as $p) {
        $qty = $_SESSION['cart'][$p['id']];
        $subtotal = $p['price'] * $qty;
        $message .= "▪️ " . $p['name'] . " (x$qty)\n";
        $message .= "   └ " . number_format($subtotal, 0, ',', ' ') . " " . $p['currency'] . "\n";
    }

    $message .= "\n--------------------------------\n";
    $message .= "💰 *TOTAL : " . number_format($total_order, 0, ',', ' ') . " " . $currency_symbol . "*\n";
    $message .= "--------------------------------\n";
    $message .= "Merci de confirmer la disponibilité et la livraison.";

    // URL WhatsApp
    $url = "https://wa.me/" . $my_whatsapp_number . "?text=" . urlencode($message);

    // Vider le panier (On le fait ici, juste avant la redirection, pas avant)
    unset($_SESSION['cart']);
    
    // Redirection
    header("Location: $url");
    exit;
}

include '../includes/header.php';
?>

<style>
    .checkout-section { background-color: #f8f9fa; min-height: 80vh; }
    .form-control-lg { border-radius: 10px; font-size: 1rem; padding: 15px; border: 1px solid #e0e0e0; }
    .form-control-lg:focus { box-shadow: 0 0 0 4px rgba(37, 211, 102, 0.1); border-color: #25D366; } /* Focus couleur WhatsApp */
    .summary-card { border-radius: 20px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
    .step-indicator { text-transform: uppercase; letter-spacing: 2px; font-size: 0.8rem; font-weight: 700; color: #adb5bd; }
    .btn-whatsapp { background: #25D366; color: white; border: none; transition: all 0.3s ease; }
    .btn-whatsapp:hover { background: #128C7E; color: white; transform: translateY(-2px); box-shadow: 0 5px 15px rgba(37, 211, 102, 0.4); }
</style>

<div class="checkout-section py-5">
    <div class="container">
        
        <div class="text-center mb-5">
            <span class="step-indicator">Étape Finale</span>
            <h2 class="fw-bold mt-2">Validation de la commande</h2>
            <p class="text-muted">Finalisez vos informations pour la livraison.</p>
        </div>

        <div class="row g-5">
            <div class="col-lg-7">
                <div class="card bg-white p-4 p-md-5 shadow-sm rounded-4 border-0">
                    <h4 class="mb-4 fw-bold">📍 Informations de livraison</h4>
                    
                    <form action="order.php" method="POST">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label text-muted small fw-bold">Nom complet</label>
                                <input type="text" name="name" class="form-control form-control-lg" placeholder="Ex: Jean Kabuya" required>
                            </div>

                            <div class="col-12">
                                <label class="form-label text-muted small fw-bold">Numéro de téléphone</label>
                                <input type="tel" name="phone" class="form-control form-control-lg" placeholder="Ex: 099..." required>
                            </div>

                            <div class="col-12">
                                <label class="form-label text-muted small fw-bold">Adresse de livraison (Quartier, Avenue, N°)</label>
                                <textarea name="address" class="form-control form-control-lg" rows="2" placeholder="Ex: Q. Golf, Av. des Sports, N°12" required></textarea>
                            </div>
                        </div>

                        <div class="mt-4 pt-3 border-top">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="terms" checked required>
                                <label class="form-check-label small text-muted" for="terms">
                                    Je confirme vouloir envoyer cette commande via WhatsApp.
                                </label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-whatsapp w-100 btn-lg rounded-pill mt-4 py-3 fw-bold d-flex align-items-center justify-content-center gap-2">
                            <span>Confirmer la commande</span>
                            <svg width="24" height="24" fill="currentColor" viewBox="0 0 16 16"><path d="M13.601 2.326A7.854 7.854 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.933 7.933 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.898 7.898 0 0 0 13.6 2.326zM7.994 14.521a6.573 6.573 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.557 6.557 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592z"/></svg>
                        </button>
                    </form>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="summary-card bg-white p-4 sticky-top" style="top: 20px;">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold m-0">Votre Panier</h5>
                        <a href="cart.php" class="small text-decoration-none text-primary">Modifier</a>
                    </div>
                    
                    <div class="border-bottom pb-3 mb-3">
                        <?php foreach ($products as $p): 
                            $qty = $_SESSION['cart'][$p['id']];
                        ?>
                        <div class="d-flex justify-content-between mb-2 small">
                            <span><?php echo $qty; ?>x <?php echo htmlspecialchars($p['name']); ?></span>
                            <span class="fw-bold"><?php echo number_format($p['price'] * $qty, 0); ?> <?php echo $p['currency']; ?></span>
                        </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fs-5 fw-bold">Total à payer</span>
                        <span class="fs-4 fw-bold text-success"><?php echo number_format($total_order, 0, ',', ' '); ?> <?php echo $currency_symbol; ?></span>
                    </div>

                    <div class="alert alert-light border mt-4 mb-0 text-center">
                        <small class="text-muted">
                            📦 Le paiement se fait à la livraison ou par Mobile Money après confirmation WhatsApp.
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>