<?php
// Fichier: public/cart.php
require_once '../config/database.php';
if (session_status() === PHP_SESSION_NONE) { session_start(); }

// --- LOGIQUE (Optimisée & Sécurisée) ---
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = (int)$_GET['id']; // Cast en int pour sécurité
    
    if (!isset($_SESSION['cart'][$id])) {
        // Sécurité : si l'ID n'est pas dans le panier, on redirige
        header('Location: cart.php'); exit;
    }

    switch ($_GET['action']) {
        case 'add':
            $_SESSION['cart'][$id]++;
            break;
        case 'minus':
            if ($_SESSION['cart'][$id] > 1) {
                $_SESSION['cart'][$id]--;
            } else {
                unset($_SESSION['cart'][$id]);
            }
            break;
        case 'delete':
            unset($_SESSION['cart'][$id]);
            break;
    }
    header('Location: cart.php'); exit;
}

// Ajout via formulaire
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['product_id'])) {
    $id = (int)$_POST['product_id'];
    $qty = isset($_POST['quantity']) ? max(1, (int)$_POST['quantity']) : 1;
    $_SESSION['cart'][$id] = ($_SESSION['cart'][$id] ?? 0) + $qty;
    header('Location: cart.php'); exit;
}

include '../includes/header.php';
?>

<style>
    .cart-container { max-width: 1200px; margin: 0 auto; }
    .cart-item { transition: background 0.3s ease; }
    .cart-item:hover { background-color: #f9f9f9; }
    .product-img { width: 80px; height: 80px; object-fit: cover; border-radius: 12px; }
    .btn-qty { width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; border-radius: 50%; border: 1px solid #ddd; background: white; transition: all 0.2s; }
    .btn-qty:hover { background: #000; color: #fff; border-color: #000; }
    .summary-card { background: #f8f9fa; border-radius: 20px; padding: 30px; position: sticky; top: 20px; }
    .premium-font { font-family: 'Playfair Display', serif; } /* Si disponible */
    .trash-icon { color: #999; transition: color 0.2s; }
    .trash-icon:hover { color: #dc3545; }
</style>

<div class="container py-5 cart-container">
    
    <?php if (empty($_SESSION['cart'])): ?>
        <div class="text-center py-5">
            <div class="mb-4 text-muted" style="font-size: 4rem; opacity: 0.3;">🛍️</div>
            <h2 class="fw-bold mb-3">Votre panier est vide</h2>
            <p class="text-muted mb-4">Il semble que vous n'ayez pas encore trouvé votre bonheur.</p>
            <a href="shop.php" class="btn btn-dark btn-lg rounded-pill px-5 shadow-sm">Découvrir la collection</a>
        </div>

    <?php else: 
        // Récupération sécurisée des produits
        $ids = array_keys($_SESSION['cart']);
        if (empty($ids)) { 
            // Double check si le panier est vide après nettoyage
             echo "<script>window.location.href='cart.php';</script>"; exit; 
        }

        // Utilisation de placeholders (?) pour la sécurité SQL
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $stmt = $pdo->prepare("SELECT * FROM products WHERE id IN ($placeholders)");
        $stmt->execute($ids);
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $totals = [];
    ?>

    <div class="row g-5">
        <div class="col-lg-8">
            <div class="d-flex justify-content-between align-items-end mb-4">
                <h2 class="fw-bold m-0 premium-font">Mon Panier</h2>
                <span class="text-muted"><?php echo count($_SESSION['cart']); ?> articles</span>
            </div>

            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-body p-0">
                    <?php foreach ($products as $p): 
                        $qty = $_SESSION['cart'][$p['id']];
                        $sub = $p['price'] * $qty;
                        $totals[$p['currency']] = ($totals[$p['currency']] ?? 0) + $sub;
                        // Placeholder si pas d'image en BDD
                        $img = !empty($p['image']) ? $p['image'] : 'https://via.placeholder.com/150';
                    ?>
                    
                    <div class="cart-item d-flex align-items-center p-4 border-bottom">
                        <div class="flex-shrink-0">
                            <img src="<?php echo htmlspecialchars($img); ?>" alt="<?php echo htmlspecialchars($p['name']); ?>" class="product-img shadow-sm">
                        </div>
                        
                        <div class="flex-grow-1 ms-4">
                            <h5 class="mb-1 fw-bold text-dark"><?php echo htmlspecialchars($p['name']); ?></h5>
                            <p class="text-muted small mb-0">Réf: #<?php echo $p['id']; ?></p>
                        </div>

                        <div class="d-flex align-items-center mx-4">
                            <a href="cart.php?action=minus&id=<?php echo $p['id']; ?>" class="btn-qty text-decoration-none">-</a>
                            <span class="fw-bold mx-3" style="min-width: 20px; text-align: center;"><?php echo $qty; ?></span>
                            <a href="cart.php?action=add&id=<?php echo $p['id']; ?>" class="btn-qty text-decoration-none">+</a>
                        </div>

                        <div class="text-end" style="min-width: 100px;">
                            <div class="fw-bold text-dark fs-5"><?php echo number_format($sub, 0, ',', ' '); ?> <small><?php echo $p['currency']; ?></small></div>
                            <?php if($qty > 1): ?>
                                <small class="text-muted fst-italic"><?php echo number_format($p['price'], 0); ?> /unité</small>
                            <?php endif; ?>
                        </div>

                        <div class="ms-4">
                            <a href="cart.php?action=delete&id=<?php echo $p['id']; ?>" class="trash-icon fs-5" title="Supprimer">
                                🗑️ </a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <div class="mt-4">
                <a href="shop.php" class="text-decoration-none text-muted fw-bold">
                    ← Continuer mes achats
                </a>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="summary-card">
                <h4 class="fw-bold mb-4 premium-font">Résumé de la commande</h4>
                
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Sous-total</span>
                    <span class="fw-bold">
                        <?php foreach($totals as $cur => $val) { echo number_format($val, 0, ',', ' ') . " " . $cur . "<br>"; } ?>
                    </span>
                </div>
                <div class="d-flex justify-content-between mb-4">
                    <span class="text-muted">Livraison</span>
                    <span class="text-success small">Calculé à l'étape suivante</span>
                </div>

                <hr class="my-4" style="opacity: 0.1">

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <span class="fs-5 fw-bold text-dark">Total</span>
                    <span class="fs-4 fw-bold text-dark">
                         <?php foreach($totals as $cur => $val) { echo number_format($val, 0, ',', ' ') . " <small>$cur</small>"; } ?>
                    </span>
                </div>

                <a href="order.php" class="btn btn-success w-100 py-3 rounded-pill shadow fw-bold d-flex align-items-center justify-content-center gap-2 transition-transform">
                    <span>Commander via WhatsApp</span>
                    <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16"><path d="M13.601 2.326A7.854 7.854 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.933 7.933 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.898 7.898 0 0 0 13.6 2.326zM7.994 14.521a6.573 6.573 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.557 6.557 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592z"/></svg>
                </a>
                
                <div class="text-center mt-3">
                    <small class="text-muted" style="font-size: 0.8rem;">🔒 Paiement sécurisé & crypté</small>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>