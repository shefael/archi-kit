<?php
// Fichier: public/shop.php
require_once '../config/database.php';
include '../includes/header.php';

// On récupère le terme recherché s'il existe
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Préparation de la requête SQL selon la recherche
if (!empty($search)) {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE name LIKE ? OR description LIKE ? ORDER BY id DESC");
    $stmt->execute(["%$search%", "%$search%"]);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $products = $pdo->query("SELECT * FROM products ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
}
?>

<div class="row mb-5 justify-content-center">
    <div class="col-md-6">
        <form action="shop.php" method="GET" class="d-flex shadow-sm rounded">
            <input type="text" name="search" class="form-control border-0" 
                   placeholder="Rechercher un article..." 
                   value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit" class="btn btn-primary px-4">🔍</button>
        </form>
    </div>
</div>

<div class="row">
    <?php if (count($products) > 0): ?>
        <h2 class="h4 mb-4">
            <?php echo !empty($search) ? "Résultats pour : '" . htmlspecialchars($search) . "'" : "Tous nos articles"; ?>
        </h2>
        
        <?php foreach ($products as $p): ?>
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm border-0 rounded-4 overflow-hidden">
                <img src="assets/images/<?php echo $p['image']; ?>" class="card-img-top" style="height:220px; object-fit:cover;">
                <div class="card-body text-center">
                    <h5 class="card-title fw-bold"><?php echo htmlspecialchars($p['name']); ?></h5>
                    <p class="text-primary fs-5"><?php echo number_format($p['price'], 0, ',', ' '); ?> <?php echo htmlspecialchars($p['currency']); ?></p>
                    
                    <div class="d-grid gap-2">
                        <a href="product.php?id=<?php echo $p['id']; ?>" class="btn btn-outline-dark btn-sm">Détails</a>
                        <form action="cart.php" method="POST">
                            <input type="hidden" name="product_id" value="<?php echo $p['id']; ?>">
                            <input type="hidden" name="action" value="add">
                            <button type="submit" class="btn btn-success w-100">🛒 Ajouter au panier</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>

    <?php else: ?>
        <div class="col-12 text-center py-5">
            <div class="fs-1">😟</div>
            <h3 class="text-muted mt-3">Désolé, nous n'avons pas ce produit pour le moment.</h3>
            <p>Essayez un autre mot-clé ou <a href="shop.php" class="text-primary">affichez tous les articles</a>.</p>
        </div>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>