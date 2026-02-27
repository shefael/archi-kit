<?php
// Fichier: public/cart.php
require_once '../config/database.php';
if (session_status() === PHP_SESSION_NONE) { session_start(); }

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
    header('Location: cart.php'); exit;
}

// Réception via formulaire (Détails ou Boutique)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['product_id'])) {
    $id = $_POST['product_id'];
    $qty = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
    $_SESSION['cart'][$id] = ($_SESSION['cart'][$id] ?? 0) + $qty;
    header('Location: cart.php'); exit;
}

include '../includes/header.php';
?>

<div class="container mt-4">
    <h2 class="mb-4">🛒 Mon Panier</h2>
    
    <?php if (empty($_SESSION['cart'])): ?>
        <div class="alert alert-light border shadow-sm text-center py-5">
            <h4>Votre panier est vide</h4>
            <a href="shop.php" class="btn btn-primary mt-3">Aller à la boutique</a>
        </div>
    <?php else: 
        $ids = implode(',', array_keys($_SESSION['cart']));
        $products = $pdo->query("SELECT * FROM products WHERE id IN ($ids)")->fetchAll(PDO::FETCH_ASSOC);
        $totals = [];
    ?>
    <div class="card shadow-sm border-0">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Produit</th>
                    <th>Prix</th>
                    <th class="text-center">Quantité</th>
                    <th>Sous-total</th>
                    <th class="text-end">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $p): 
                    $qty = $_SESSION['cart'][$p['id']];
                    $sub = $p['price'] * $qty;
                    $totals[$p['currency']] = ($totals[$p['currency']] ?? 0) + $sub;
                ?>
                <tr>
                    <td><strong><?php echo htmlspecialchars($p['name']); ?></strong></td>
                    <td><?php echo number_format($p['price'], 0); ?> <?php echo $p['currency']; ?></td>
                    <td class="text-center">
                        <div class="btn-group border rounded shadow-sm">
                            <a href="cart.php?action=minus&id=<?php echo $p['id']; ?>" class="btn btn-sm btn-light fw-bold">-</a>
                            <span class="btn btn-sm btn-white disabled fw-bold px-3"><?php echo $qty; ?></span>
                            <a href="cart.php?action=add&id=<?php echo $p['id']; ?>" class="btn btn-sm btn-light fw-bold">+</a>
                        </div>
                    </td>
                    <td><strong><?php echo number_format($sub, 0); ?> <?php echo $p['currency']; ?></strong></td>
                    <td class="text-end">
                        <a href="cart.php?action=delete&id=<?php echo $p['id']; ?>" class="btn btn-sm btn-outline-danger">❌</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="card-footer bg-white p-4 text-end">
            <?php foreach ($totals as $cur => $val): ?>
                <h3 class="fw-bold text-success">Total : <?php echo number_format($val, 0, ',', ' '); ?> <?php echo $cur; ?></h3>
            <?php endforeach; ?>
            <hr>
            <div class="d-flex justify-content-between align-items-center">
                <a href="shop.php" class="btn btn-outline-secondary">Continuer mes achats</a>
                <a href="order.php" class="btn btn-success btn-lg px-5 shadow">Commander sur WhatsApp ✅</a>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>