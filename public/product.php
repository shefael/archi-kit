<?php
require_once '../config/database.php';
include '../includes/header.php';
$id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch();
?>

<div class="row bg-white p-4 rounded shadow-sm">
    <div class="col-md-6 mb-3">
        <img src="assets/images/<?php echo $product['image']; ?>" class="img-fluid rounded border">
    </div>
    <div class="col-md-6">
        <h1 class="display-5 fw-bold"><?php echo htmlspecialchars($product['name']); ?></h1>
        <hr>
        <p class="fs-4"><strong>Prix :</strong> <span class="text-success"><?php echo number_format($product['price'], 0); ?> <?php echo htmlspecialchars($product['currency']); ?></span></p>
        <p><strong>Description :</strong><br> <?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
        
        <form action="cart.php" method="POST" class="mt-4">
            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
            <input type="hidden" name="action" value="add">
            
            <div class="mb-3" style="max-width: 200px;">
                <label class="form-label fw-bold">Nombre de pièces :</label>
                <input type="number" name="quantity" value="1" min="1" class="form-control text-center">
            </div>
            
            <button type="submit" class="btn btn-primary btn-lg w-100 shadow">🛒 Ajouter au panier</button>
        </form>
    </div>
</div>