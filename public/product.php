<?php
require_once '../config/database.php';
include '../includes/header.php';

// Validation et sanitation de l'ID
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT, ['options' => ['default' => 0, 'min_range' => 1]]);
if (!$id) {
    http_response_code(400);
    echo "Produit introuvable.";
    include '../includes/footer.php';
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT id, name, description, price, currency, image, stock FROM products WHERE id = :id LIMIT 1");
    $stmt->execute([':id' => $id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    http_response_code(500);
    error_log("DB error: " . $e->getMessage());
    echo "Erreur serveur. Veuillez réessayer plus tard.";
    include '../includes/footer.php';
    exit;
}

if (!$product) {
    http_response_code(404);
    echo "Produit introuvable.";
    include '../includes/footer.php';
    exit;
}

// Protection du nom de fichier image contre directory traversal
$imageFile = basename($product['image'] ?? '');
$imagePath = 'assets/images/' . ($imageFile ?: 'default.png');
if (!file_exists($imagePath)) {
    $imagePath = 'assets/images/default.png';
}

// CSRF token simple pour le formulaire
session_start();
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf = $_SESSION['csrf_token'];

// Formatage prix avec Intl si disponible
$priceFormatted = number_format((float)$product['price'], 2, ',', ' ');
if (class_exists('NumberFormatter')) {
    $fmt = new NumberFormatter('fr_FR', NumberFormatter::CURRENCY);
    $priceFormatted = $fmt->formatCurrency((float)$product['price'], $product['currency'] ?? 'EUR');
}
?>
<div class="row">
    <div class="col-md-6">
        <img src="<?php echo htmlspecialchars($imagePath); ?>"
             alt="<?php echo htmlspecialchars($product['name']); ?>"
             class="img-fluid rounded shadow"
             loading="lazy">
    </div>

    <div class="col-md-6">
        <h1><?php echo htmlspecialchars($product['name']); ?></h1>
        <h2 class="text-success"><?php echo $priceFormatted; ?></h2>

        <?php if (isset($product['stock'])): ?>
            <p class="small text-muted">Stock disponible: <strong><?php echo (int)$product['stock']; ?></strong></p>
        <?php endif; ?>

        <p class="mt-4"><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>

        <form id="add-to-cart-form" action="cart.php" method="post" class="d-flex align-items-center">
            <input type="hidden" name="product_id" value="<?php echo (int)$product['id']; ?>">
            <input type="hidden" name="action" value="add">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf); ?>">
            <label for="qty" class="me-2">Quantité</label>
            <input id="qty" name="quantity" type="number" value="1" min="1" max="<?php echo max(1, (int)$product['stock']); ?>" class="form-control me-3" style="width:100px;">
            <button type="submit" class="btn btn-primary btn-lg">Ajouter au panier</button>
        </form>

        <div id="cart-message" class="mt-3" aria-live="polite"></div>
    </div>
</div>

<!-- JSON-LD pour SEO -->
<script type="application/ld+json">
{
  "@context": "https://schema.org/",
  "@type": "Product",
  "name": "<?php echo addslashes($product['name']); ?>",
  "image": "<?php echo addslashes($imagePath); ?>",
  "description": "<?php echo addslashes(strip_tags($product['description'])); ?>",
  "offers": {
    "@type": "Offer",
    "priceCurrency": "<?php echo addslashes($product['currency'] ?? 'EUR'); ?>",
    "price": "<?php echo (float)$product['price']; ?>",
    "availability": "<?php echo ((int)$product['stock'] > 0) ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock'; ?>"
  }
}
</script>

<!-- Optionnel: ajout au panier en AJAX pour meilleure UX -->
<script>
document.getElementById('add-to-cart-form').addEventListener('submit', function(e){
    e.preventDefault();
    const form = e.currentTarget;
    const data = new FormData(form);
    fetch(form.action, {
        method: 'POST',
        credentials: 'same-origin',
        body: data
    }).then(r => r.json())
      .then(json => {
          const msg = document.getElementById('cart-message');
          if (json.success) {
              msg.innerHTML = '<div class="alert alert-success">Produit ajouté au panier.</div>';
          } else {
              msg.innerHTML = '<div class="alert alert-danger">' + (json.error || 'Erreur lors de l\'ajout') + '</div>';
          }
      }).catch(() => {
          document.getElementById('cart-message').innerHTML = '<div class="alert alert-danger">Erreur réseau.</div>';
      });
});
</script>

<?php include '../includes/footer.php'; ?>
