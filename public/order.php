<?php
// Fichier: public/order.php
session_start();
require_once '../config/database.php';

// CONFIGURATION : TON NUMÉRO WHATSAPP
$my_whatsapp_number = "243000000000"; // À remplacer par ton vrai numéro

if (empty($_SESSION['cart'])) {
    header('Location: shop.php');
    exit;
}

$ids = implode(',', array_keys($_SESSION['cart']));
$stmt = $pdo->query("SELECT * FROM products WHERE id IN ($ids)");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Inclure le header après la logique mais avant l'affichage
include '../includes/header.php';
?>

<!-- Styles premium pour la page de commande -->
<style>
    .premium-container {
        max-width: 900px;
        margin: 0 auto;
        padding: 20px;
    }

    .order-card {
        background: white;
        border-radius: 40px;
        box-shadow: 0 30px 60px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        position: relative;
    }

    .order-header {
        background: linear-gradient(135deg, #25D366 0%, #128C7E 100%);
        padding: 40px;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .order-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.2) 0%, transparent 70%);
        animation: rotate 20s linear infinite;
    }

    @keyframes rotate {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }

    .whatsapp-icon {
        font-size: 80px;
        margin-bottom: 20px;
        animation: pulse 2s ease-in-out infinite;
        position: relative;
        z-index: 1;
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.1); }
    }

    .order-header h1 {
        color: white;
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 10px;
        position: relative;
        z-index: 1;
        text-shadow: 0 2px 10px rgba(0,0,0,0.2);
    }

    .order-header p {
        color: rgba(255,255,255,0.9);
        font-size: 1.1rem;
        position: relative;
        z-index: 1;
    }

    .order-content {
        padding: 40px;
    }

    .summary-title {
        display: flex;
        align-items: center;
        margin-bottom: 30px;
        color: #2c3e50;
    }

    .summary-title span {
        width: 40px;
        height: 40px;
        background: #f0f9f4;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
        color: #25D366;
        font-weight: bold;
    }

    .product-item {
        background: #f8fafc;
        border-radius: 20px;
        padding: 20px;
        margin-bottom: 15px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: all 0.3s ease;
        border: 1px solid rgba(37, 211, 102, 0.1);
    }

    .product-item:hover {
        transform: translateX(10px);
        border-color: #25D366;
        box-shadow: 0 10px 30px rgba(37, 211, 102, 0.1);
    }

    .product-info h3 {
        font-size: 1.2rem;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 5px;
    }

    .product-meta {
        display: flex;
        gap: 20px;
        color: #64748b;
        font-size: 0.95rem;
    }

    .product-price {
        font-size: 1.3rem;
        font-weight: 700;
        color: #25D366;
    }

    .divider {
        height: 2px;
        background: linear-gradient(90deg, transparent, #25D366, #128C7E, transparent);
        margin: 30px 0;
    }

    .total-section {
        background: linear-gradient(135deg, #f8fafc 0%, #eef2f6 100%);
        border-radius: 30px;
        padding: 30px;
        margin: 30px 0;
    }

    .total-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 0;
        border-bottom: 1px dashed #cbd5e1;
    }

    .total-row:last-child {
        border-bottom: none;
    }

    .total-label {
        font-size: 1.1rem;
        color: #64748b;
    }

    .total-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: #2c3e50;
    }

    .grand-total {
        font-size: 2rem;
        color: #25D366;
        font-weight: 800;
    }

    .customer-info {
        background: white;
        border-radius: 20px;
        padding: 25px;
        border: 1px solid #e2e8f0;
        margin-top: 30px;
    }

    .info-row {
        display: flex;
        align-items: center;
        margin-bottom: 15px;
        padding: 10px;
        border-radius: 15px;
        background: #f8fafc;
    }

    .info-icon {
        width: 45px;
        height: 45px;
        background: #25D366;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        margin-right: 15px;
        font-size: 1.2rem;
    }

    .whatsapp-button {
        display: block;
        background: linear-gradient(135deg, #25D366 0%, #128C7E 100%);
        color: white;
        text-decoration: none;
        padding: 20px;
        border-radius: 30px;
        text-align: center;
        font-size: 1.3rem;
        font-weight: 600;
        margin: 40px 0 20px;
        transition: all 0.3s ease;
        border: none;
        width: 100%;
        cursor: pointer;
        box-shadow: 0 20px 40px rgba(37, 211, 102, 0.3);
        position: relative;
        overflow: hidden;
    }

    .whatsapp-button::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.3);
        transform: translate(-50%, -50%);
        transition: width 0.6s, height 0.6s;
    }

    .whatsapp-button:hover {
        transform: translateY(-3px);
        box-shadow: 0 30px 60px rgba(37, 211, 102, 0.4);
        color: white;
    }

    .whatsapp-button:hover::before {
        width: 300px;
        height: 300px;
    }

    .back-to-cart {
        display: inline-flex;
        align-items: center;
        color: #64748b;
        text-decoration: none;
        font-weight: 500;
        margin-top: 20px;
        transition: all 0.3s ease;
    }

    .back-to-cart:hover {
        color: #25D366;
        transform: translateX(-5px);
    }

    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255,255,255,0.9);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 1000;
        opacity: 0;
        pointer-events: none;
        transition: all 0.3s ease;
    }

    .loading-overlay.active {
        opacity: 1;
        pointer-events: all;
    }

    .loader {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, #25D366 0%, #128C7E 100%);
        border-radius: 50%;
        animation: bounce 1s ease-in-out infinite;
    }

    @keyframes bounce {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.2); }
    }
</style>

<div class="premium-container">
    <!-- Overlay de chargement -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loader"></div>
    </div>

    <div class="order-card">
        <!-- En-tête WhatsApp -->
        <div class="order-header">
            <div class="whatsapp-icon">📱</div>
            <h1>Finaliser sur WhatsApp</h1>
            <p>Votre commande sera envoyée directement sur WhatsApp</p>
        </div>

        <!-- Contenu de la commande -->
        <div class="order-content">
            <!-- Résumé de la commande -->
            <div class="summary-title">
                <span>📋</span>
                <h2>Récapitulatif de votre commande</h2>
            </div>

            <?php
            $message = "Bonjour, je souhaite commander les articles suivants :\n\n";
            $total_par_devise = [];

            foreach ($products as $p):
                $qty = $_SESSION['cart'][$p['id']];
                $subtotal = $p['price'] * $qty;
                $devise = $p['currency'];
                
                // Construction du message pour WhatsApp
                $message .= "▪️ " . $p['name'] . "\n";
                $message .= "   Qté : " . $qty . " pc(s)\n";
                $message .= "   Prix : " . number_format($subtotal, 0, ',', ' ') . " " . $devise . "\n\n";
                
                if (!isset($total_par_devise[$devise])) {
                    $total_par_devise[$devise] = 0;
                }
                $total_par_devise[$devise] += $subtotal;
            ?>

            <!-- Affichage des produits -->
            <div class="product-item">
                <div class="product-info">
                    <h3><?php echo htmlspecialchars($p['name']); ?></h3>
                    <div class="product-meta">
                        <span>Quantité: <strong><?php echo $qty; ?> pièce(s)</strong></span>
                        <span>Prix unitaire: <strong><?php echo number_format($p['price'], 0, ',', ' '); ?> <?php echo $devise; ?></strong></span>
                    </div>
                </div>
                <div class="product-price">
                    <?php echo number_format($subtotal, 0, ',', ' '); ?> <?php echo $devise; ?>
                </div>
            </div>

            <?php endforeach; ?>

            <!-- Divider avec animation -->
            <div class="divider"></div>

            <!-- Section des totaux -->
            <div class="total-section">
                <h3 class="mb-4" style="color: #2c3e50;">Détail des paiements</h3>
                
                <?php 
                $message .= "------------------------\n";
                $message .= "💰 *TOTAL A PAYER :*\n";
                
                foreach ($total_par_devise as $dev => $somme): 
                    $message .= "👉 " . number_format($somme, 0, ',', ' ') . " " . $dev . "\n";
                ?>
                
                <div class="total-row">
                    <span class="total-label">Total <?php echo $dev; ?></span>
                    <span class="total-value"><?php echo number_format($somme, 0, ',', ' '); ?> <?php echo $dev; ?></span>
                </div>
                
                <?php endforeach; ?>
                
                <div class="total-row mt-3">
                    <span class="total-label fw-bold">Total général</span>
                    <div class="text-end">
                        <?php foreach ($total_par_devise as $dev => $somme): ?>
                        <div class="grand-total"><?php echo number_format($somme, 0, ',', ' '); ?> <?php echo $dev; ?></div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Informations client -->
            <div class="customer-info">
                <h4 class="mb-3" style="color: #2c3e50;">📝 Informations importantes</h4>
                
                <div class="info-row">
                    <div class="info-icon">📍</div>
                    <div>
                        <small class="text-muted">Adresse de livraison</small>
                        <div class="fw-bold">À préciser sur WhatsApp</div>
                    </div>
                </div>
                
                <div class="info-row">
                    <div class="info-icon">📞</div>
                    <div>
                        <small class="text-muted">Contact</small>
                        <div class="fw-bold">Numéro de téléphone à communiquer</div>
                    </div>
                </div>
                
                <div class="info-row">
                    <div class="info-icon">💳</div>
                    <div>
                        <small class="text-muted">Mode de paiement</small>
                        <div class="fw-bold">À discuter sur WhatsApp</div>
                    </div>
                </div>
            </div>

            <?php
            // Construction de l'URL WhatsApp
            $url = "https://wa.me/" . $my_whatsapp_number . "?text=" . urlencode($message);
            ?>

            <!-- Bouton WhatsApp avec confirmation -->
            <button onclick="confirmAndRedirect('<?php echo $url; ?>')" class="whatsapp-button">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor" class="me-2" style="display: inline-block; vertical-align: middle;">
                    <path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91C2.13 13.91 2.78 15.79 3.97 17.3L2 22L6.84 20.09C8.34 20.97 10.07 21.5 12.04 21.5C17.5 21.5 21.96 17.04 21.96 11.58C21.96 6.12 17.5 2 12.04 2Z M12.04 4.5C16.14 4.5 19.46 7.82 19.46 11.91C19.46 16 16.14 19.32 12.04 19.32C10.36 19.32 8.79 18.81 7.49 17.92L4.96 18.85L5.97 16.51C5.01 15.17 4.46 13.57 4.46 11.91C4.46 7.82 7.78 4.5 11.88 4.5H12.04Z M9.46 8.5C9.24 8.5 9.02 8.5 8.84 8.5C8.66 8.5 8.38 8.5 8.1 8.86C7.82 9.22 7.13 9.94 7.13 11.41C7.13 12.88 8.19 14.3 8.34 14.5C8.49 14.7 10.09 17.21 12.55 17.21C15.01 17.21 15.87 15.31 16.06 14.91C16.25 14.51 16.25 14.16 16.16 14.06C16.07 13.96 15.87 13.86 15.57 13.66C15.27 13.46 14.07 12.87 13.8 12.77C13.53 12.67 13.33 12.62 13.13 12.92C12.93 13.22 12.38 13.96 12.21 14.16C12.04 14.36 11.87 14.41 11.57 14.21C11.27 14.01 10.47 13.71 9.87 13.08C9.4 12.6 9.07 12.02 8.93 11.7C8.79 11.38 8.91 11.21 9.06 11.01C9.21 10.81 9.36 10.62 9.51 10.42C9.66 10.22 9.71 10.06 9.51 9.71C9.31 9.36 8.98 8.5 8.78 8.5H9.46Z"/>
                </svg>
                Envoyer la commande sur WhatsApp
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="ms-2" style="display: inline-block; vertical-align: middle;">
                    <path d="M5 12h14M12 5l7 7-7 7"/>
                </svg>
            </button>

            <!-- Lien retour panier -->
            <a href="cart.php" class="back-to-cart">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-2">
                    <path d="M19 12H5M12 19l-7-7 7-7"/>
                </svg>
                Modifier mon panier
            </a>

            <!-- Message de confirmation -->
            <p class="text-center text-muted mt-4 small">
                En cliquant sur le bouton, vous serez redirigé vers WhatsApp<br>
                pour finaliser votre commande en toute simplicité.
            </p>
        </div>
    </div>
</div>

<!-- Script de confirmation -->
<script>
function confirmAndRedirect(url) {
    // Afficher l'overlay de chargement
    document.getElementById('loadingOverlay').classList.add('active');
    
    // Compter les articles
    const totalItems = <?php echo array_sum($_SESSION['cart']); ?>;
    
    // Message de confirmation personnalisé
    const message = `Confirmation\n\n` +
        `Vous allez être redirigé vers WhatsApp avec :\n` +
        `📦 ${totalItems} article(s)\n` +
        `💰 Total: <?php foreach ($total_par_devise as $dev => $somme) { echo number_format($somme, 0, ',', ' ') . ' ' . $dev . ' '; } ?>\n\n` +
        `Voulez-vous continuer ?`;
    
    if (confirm(message)) {
        // Simuler un délai pour l'effet de chargement
        setTimeout(() => {
            // Vider le panier
            <?php unset($_SESSION['cart']); ?>
            // Rediriger vers WhatsApp
            window.location.href = url;
        }, 1500);
    } else {
        // Cacher l'overlay si annulation
        document.getElementById('loadingOverlay').classList.remove('active');
    }
}

// Animation d'apparition
document.addEventListener('DOMContentLoaded', function() {
    const elements = document.querySelectorAll('.product-item, .total-section, .customer-info');
    elements.forEach((el, index) => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(20px)';
        el.style.transition = 'all 0.5s ease';
        
        setTimeout(() => {
            el.style.opacity = '1';
            el.style.transform = 'translateY(0)';
        }, index * 100);
    });
});
</script>

<?php
// Vider le panier APRÈS avoir affiché la page
// Note: Le panier est vidé après la redirection dans le script JavaScript
// pour permettre l'affichage du récapitulatif
include '../includes/footer.php';
?>