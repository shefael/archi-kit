<?php
// Fichier: public/cart.php
require_once '../config/database.php';

if (session_status() === PHP_SESSION_NONE) { 
    session_start(); 
}

// --- LOGIQUE DE GESTION DES QUANTITÉS ---
if (isset($_GET['action'])) {
    $id = $_GET['id'];
    
    if ($_GET['action'] == 'add') {
        $_SESSION['cart'][$id] = ($_SESSION['cart'][$id] ?? 0) + 1;
    }
    elseif ($_GET['action'] == 'minus') {
        if ($_SESSION['cart'][$id] > 1) {
            $_SESSION['cart'][$id] -= 1;
        } else {
            unset($_SESSION['cart'][$id]);
        }
    }
    elseif ($_GET['action'] == 'delete') {
        unset($_SESSION['cart'][$id]);
    }
    
    // Redirection avec effet de smooth
    header('Location: cart.php?updated=1');
    exit;
}

// Réception via formulaire
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['product_id'])) {
    $id = $_POST['product_id'];
    $qty = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
    $_SESSION['cart'][$id] = ($_SESSION['cart'][$id] ?? 0) + $qty;
    header('Location: cart.php?added=1');
    exit;
}

include '../includes/header.php';
?>

<!-- Style additionnel pour les effets premium -->
<style>
    .premium-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 20px 40px -10px rgba(0, 0, 0, 0.15);
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
    }

    .premium-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 30px 50px -15px rgba(0, 0, 0, 0.25);
    }

    .table-premium {
        border-collapse: separate;
        border-spacing: 0 15px;
    }

    .table-premium tbody tr {
        background: white;
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
        transition: all 0.3s ease;
    }

    .table-premium tbody tr:hover {
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
        transform: scale(1.01);
    }

    .quantity-selector {
        background: #f8f9fa;
        border-radius: 50px;
        padding: 3px;
        display: inline-flex;
        align-items: center;
        border: 1px solid #e9ecef;
    }

    .quantity-btn {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        border: none;
        background: white;
        color: #2c3e50;
        font-weight: bold;
        transition: all 0.2s ease;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }

    .quantity-btn:hover {
        background: #2c3e50;
        color: white;
        transform: scale(1.1);
    }

    .quantity-value {
        min-width: 40px;
        text-align: center;
        font-weight: 600;
        color: #2c3e50;
    }

    .btn-delete {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        background: #fff5f5;
        color: #e53e3e;
        border: 1px solid #fed7d7;
    }

    .btn-delete:hover {
        background: #e53e3e;
        color: white;
        transform: rotate(90deg);
    }

    .total-section {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 20px;
        padding: 25px;
        margin-top: 20px;
    }

    .empty-cart {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        border-radius: 30px;
        padding: 60px 20px;
        text-align: center;
    }

    .empty-cart-icon {
        font-size: 80px;
        margin-bottom: 20px;
        animation: float 3s ease-in-out infinite;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-20px); }
    }

    .btn-premium {
        padding: 12px 30px;
        border-radius: 50px;
        font-weight: 600;
        letter-spacing: 0.5px;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .btn-premium::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        transform: translate(-50%, -50%);
        transition: width 0.6s, height 0.6s;
    }

    .btn-premium:hover::before {
        width: 300px;
        height: 300px;
    }

    .btn-success-premium {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        border: none;
        color: white;
        box-shadow: 0 10px 20px rgba(56, 239, 125, 0.3);
    }

    .btn-success-premium:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 30px rgba(56, 239, 125, 0.4);
    }

    .notification-toast {
        position: fixed;
        top: 20px;
        right: 20px;
        background: white;
        border-radius: 50px;
        padding: 15px 30px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        transform: translateX(400px);
        animation: slideIn 0.5s forwards;
        z-index: 1000;
    }

    @keyframes slideIn {
        to { transform: translateX(0); }
    }
</style>

<!-- Notifications -->
<?php if (isset($_GET['added'])): ?>
<div class="notification-toast" id="notification">
    <span class="text-success">✓ Article ajouté au panier</span>
</div>
<script>
    setTimeout(() => {
        document.getElementById('notification').style.display = 'none';
    }, 3000);
</script>
<?php endif; ?>

<div class="container mt-5">
    <!-- En-tête avec animation -->
    <div class="d-flex align-items-center mb-5">
        <h2 class="display-6 fw-bold" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
            🛒 Mon Panier
        </h2>
        <?php if (!empty($_SESSION['cart'])): ?>
        <span class="badge bg-primary rounded-pill ms-3 px-3 py-2">
            <?php echo array_sum($_SESSION['cart']); ?> article(s)
        </span>
        <?php endif; ?>
    </div>
    
    <?php if (empty($_SESSION['cart'])): ?>
        <!-- Version premium du panier vide -->
        <div class="empty-cart">
            <div class="empty-cart-icon">🛍️</div>
            <h3 class="fw-bold mb-3">Votre panier est vide</h3>
            <p class="text-muted mb-4">Découvrez nos magnifiques articles et faites-vous plaisir !</p>
            <a href="shop.php" class="btn btn-primary btn-premium px-5 py-3">
                Explorer la boutique
                <i class="ms-2">→</i>
            </a>
        </div>
    <?php else:
        $ids = implode(',', array_keys($_SESSION['cart']));
        $products = $pdo->query("SELECT * FROM products WHERE id IN ($ids)")->fetchAll(PDO::FETCH_ASSOC);
        $totals = [];
    ?>
    
    <!-- Tableau premium -->
    <div class="premium-card rounded-4 p-4">
        <div class="table-responsive">
            <table class="table table-premium">
                <thead style="background: transparent;">
                    <tr>
                        <th class="border-0 fw-semibold text-muted">Produit</th>
                        <th class="border-0 fw-semibold text-muted">Prix</th>
                        <th class="text-center border-0 fw-semibold text-muted">Quantité</th>
                        <th class="border-0 fw-semibold text-muted">Sous-total</th>
                        <th class="text-end border-0 fw-semibold text-muted">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $p):
                        $qty = $_SESSION['cart'][$p['id']];
                        $sub = $p['price'] * $qty;
                        $totals[$p['currency']] = ($totals[$p['currency']] ?? 0) + $sub;
                    ?>
                    <tr>
                        <td class="align-middle border-0">
                            <div class="d-flex align-items-center">
                                <div style="width: 50px; height: 50px; background: #f8f9fa; border-radius: 10px; margin-right: 15px; overflow: hidden;">
                                    <img src="assets/images/<?php echo $p['image']; ?>" alt="<?php echo $p['name']; ?>" style="width: 100%; height: 100%; object-fit: cover;">
                                </div>
                                <strong class="fs-6"><?php echo htmlspecialchars($p['name']); ?></strong>
                            </div>
                        </td>
                        <td class="align-middle border-0">
                            <span class="fw-semibold text-primary">
                                <?php echo number_format($p['price'], 0); ?> <?php echo $p['currency']; ?>
                            </span>
                        </td>
                        <td class="text-center align-middle border-0">
                            <div class="quantity-selector">
                                <a href="cart.php?action=minus&id=<?php echo $p['id']; ?>" class="quantity-btn text-decoration-none">−</a>
                                <span class="quantity-value"><?php echo $qty; ?></span>
                                <a href="cart.php?action=add&id=<?php echo $p['id']; ?>" class="quantity-btn text-decoration-none">+</a>
                            </div>
                        </td>
                        <td class="align-middle border-0">
                            <strong class="fs-5" style="color: #2c3e50;">
                                <?php echo number_format($sub, 0); ?> <?php echo $p['currency']; ?>
                            </strong>
                        </td>
                        <td class="text-end align-middle border-0">
                            <a href="cart.php?action=delete&id=<?php echo $p['id']; ?>" class="btn-delete text-decoration-none" onclick="return confirm('Supprimer cet article ?')">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M3 6h18M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"></path>
                                </svg>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Section total premium -->
        <div class="total-section mt-4">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h5 class="fw-light mb-2">Récapitulatif</h5>
                    <p class="small opacity-75 mb-0">Livraison offerte à partir de 100 000 F</p>
                </div>
                <div class="col-md-6">
                    <?php foreach ($totals as $cur => $val): ?>
                    <div class="d-flex justify-content-end align-items-baseline mb-2">
                        <span class="fw-light me-3">Total <?php echo $cur; ?> :</span>
                        <span class="display-6 fw-bold"><?php echo number_format($val, 0, ',', ' '); ?> <?php echo $cur; ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        
        <!-- Actions premium -->
        <div class="d-flex flex-wrap justify-content-between align-items-center mt-4 pt-3 border-top">
            <a href="shop.php" class="btn btn-outline-secondary btn-premium px-4">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-2">
                    <path d="M19 12H5M12 19l-7-7 7-7"/>
                </svg>
                Continuer mes achats
            </a>
            
            <?php if (!empty($totals)): ?>
            <a href="order.php" class="btn btn-success-premium btn-premium px-5 py-3">
                Commander sur WhatsApp
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="ms-2">
                    <path d="M5 12h14M12 5l7 7-7 7"/>
                </svg>
            </a>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>
</div>

<script>
// Animation au scroll
document.addEventListener('DOMContentLoaded', function() {
    const elements = document.querySelectorAll('.premium-card, .total-section');
    elements.forEach((el, index) => {
        setTimeout(() => {
            el.style.opacity = '1';
            el.style.transform = 'translateY(0)';
        }, index * 100);
    });
});
</script>

<?php include '../includes/footer.php'; ?>