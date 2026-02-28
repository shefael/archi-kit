<?php
// Fichier: public/product.php
require_once '../config/database.php';
include '../includes/header.php';

$id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch();

// Redirection si le produit n'existe pas
if (!$product) {
    header('Location: shop.php');
    exit;
}

// Produits similaires (même catégorie ou gamme de prix)
$similarStmt = $pdo->prepare("SELECT * FROM products WHERE id != ? AND currency = ? ORDER BY RAND() LIMIT 4");
$similarStmt->execute([$id, $product['currency']]);
$similarProducts = $similarStmt->fetchAll();
?>

<!-- Styles premium pour la page produit -->
<style>
    .product-premium {
        background: linear-gradient(135deg, #f5f7fa 0%, #ffffff 100%);
        border-radius: 50px;
        padding: 40px;
        box-shadow: 0 30px 60px rgba(0,0,0,0.1);
        margin: 30px 0;
    }

    .image-container {
        position: relative;
        border-radius: 40px;
        overflow: hidden;
        box-shadow: 0 30px 50px -20px rgba(0,0,0,0.3);
        background: white;
        padding: 20px;
        transition: all 0.5s ease;
    }

    .image-container:hover {
        transform: scale(1.02);
        box-shadow: 0 40px 70px -20px rgba(0,0,0,0.4);
    }

    .product-image {
        width: 100%;
        height: auto;
        border-radius: 30px;
        transition: all 0.5s ease;
    }

    .image-badge {
        position: absolute;
        top: 20px;
        right: 20px;
        background: linear-gradient(135deg, #FFD700, #FFA500);
        color: white;
        padding: 10px 20px;
        border-radius: 50px;
        font-weight: bold;
        box-shadow: 0 10px 20px rgba(255,215,0,0.3);
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }

    .product-title {
        font-size: 3rem;
        font-weight: 800;
        background: linear-gradient(135deg, #2c3e50, #3498db);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 20px;
    }

    .product-price {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 20px 30px;
        border-radius: 30px;
        color: white;
        display: inline-block;
        margin: 20px 0;
        box-shadow: 0 20px 40px rgba(102, 126, 234, 0.3);
    }

    .price-number {
        font-size: 3rem;
        font-weight: 800;
        line-height: 1;
    }

    .price-currency {
        font-size: 1.5rem;
        opacity: 0.9;
    }

    .product-description {
        background: white;
        padding: 30px;
        border-radius: 30px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.05);
        margin: 30px 0;
        position: relative;
        overflow: hidden;
    }

    .product-description::before {
        content: '"';
        position: absolute;
        top: -20px;
        left: 20px;
        font-size: 150px;
        color: #f0f2f5;
        font-family: serif;
        z-index: 0;
    }

    .product-description p {
        position: relative;
        z-index: 1;
        font-size: 1.1rem;
        line-height: 1.8;
        color: #4a5568;
    }

    .features-list {
        list-style: none;
        padding: 0;
        margin: 30px 0;
    }

    .features-list li {
        padding: 15px 0;
        border-bottom: 1px dashed #e2e8f0;
        display: flex;
        align-items: center;
    }

    .features-list li:last-child {
        border-bottom: none;
    }

    .feature-icon {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        margin-right: 15px;
        font-size: 1.2rem;
    }

    .quantity-selector-premium {
        background: white;
        border-radius: 100px;
        padding: 10px;
        display: inline-flex;
        align-items: center;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        border: 1px solid #e2e8f0;
    }

    .quantity-btn-premium {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        border: none;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        font-size: 1.5rem;
        font-weight: bold;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .quantity-btn-premium:hover {
        transform: scale(1.1);
        box-shadow: 0 10px 20px rgba(102, 126, 234, 0.4);
    }

    .quantity-btn-premium:active {
        transform: scale(0.95);
    }

    .quantity-input-premium {
        width: 80px;
        border: none;
        text-align: center;
        font-size: 1.5rem;
        font-weight: 600;
        color: #2c3e50;
        background: transparent;
    }

    .quantity-input-premium:focus {
        outline: none;
    }

    .add-to-cart-btn {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        color: white;
        border: none;
        padding: 20px 40px;
        border-radius: 100px;
        font-size: 1.3rem;
        font-weight: 600;
        width: 100%;
        transition: all 0.3s ease;
        box-shadow: 0 20px 40px rgba(56, 239, 125, 0.3);
        position: relative;
        overflow: hidden;
    }

    .add-to-cart-btn::before {
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

    .add-to-cart-btn:hover::before {
        width: 300px;
        height: 300px;
    }

    .add-to-cart-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 30px 60px rgba(56, 239, 125, 0.4);
    }

    .similar-products-title {
        font-size: 2rem;
        font-weight: 700;
        margin: 60px 0 30px;
        position: relative;
        display: inline-block;
    }

    .similar-products-title::after {
        content: '';
        position: absolute;
        bottom: -10px;
        left: 0;
        width: 80px;
        height: 4px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 2px;
    }

    .similar-card {
        background: white;
        border-radius: 30px;
        overflow: hidden;
        box-shadow: 0 15px 30px rgba(0,0,0,0.1);
        transition: all 0.4s ease;
        height: 100%;
        position: relative;
    }

    .similar-card:hover {
        transform: translateY(-15px);
        box-shadow: 0 30px 50px rgba(0,0,0,0.2);
    }

    .similar-image {
        height: 250px;
        overflow: hidden;
        position: relative;
    }

    .similar-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: all 0.6s ease;
    }

    .similar-card:hover .similar-image img {
        transform: scale(1.1);
    }

    .similar-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.9), rgba(118, 75, 162, 0.9));
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: all 0.4s ease;
    }

    .similar-card:hover .similar-overlay {
        opacity: 1;
    }

    .view-btn {
        color: white;
        text-decoration: none;
        padding: 12px 30px;
        border: 2px solid white;
        border-radius: 50px;
        font-weight: 600;
        transform: translateY(20px);
        transition: all 0.4s ease;
    }

    .similar-card:hover .view-btn {
        transform: translateY(0);
    }

    .view-btn:hover {
        background: white;
        color: #667eea;
    }

    .similar-info {
        padding: 20px;
        text-align: center;
    }

    .similar-name {
        font-weight: 700;
        margin-bottom: 10px;
        color: #2c3e50;
    }

    .similar-price {
        color: #667eea;
        font-weight: 600;
        font-size: 1.2rem;
    }

    .breadcrumb-premium {
        background: transparent;
        padding: 20px 0;
        margin-bottom: 30px;
    }

    .breadcrumb-item a {
        color: #667eea;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .breadcrumb-item a:hover {
        color: #764ba2;
        padding-left: 5px;
    }

    .stock-badge {
        display: inline-block;
        padding: 8px 20px;
        border-radius: 50px;
        font-weight: 600;
        margin: 20px 0;
    }

    .in-stock {
        background: #f0fff4;
        color: #38a169;
        border: 1px solid #9ae6b4;
    }

    .low-stock {
        background: #fffaf0;
        color: #dd6b20;
        border: 1px solid #fbd38d;
    }
</style>

<div class="container mt-4">
    <!-- Fil d'Ariane -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb breadcrumb-premium">
            <li class="breadcrumb-item"><a href="shop.php">Boutique</a></li>
            <li class="breadcrumb-item"><a href="shop.php?category=<?php echo urlencode($product['currency']); ?>"><?php echo $product['currency']; ?></a></li>
            <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($product['name']); ?></li>
        </ol>
    </nav>

    <!-- Produit principal -->
    <div class="product-premium">
        <div class="row g-5">
            <!-- Colonne image -->
            <div class="col-lg-6">
                <div class="image-container">
                    <img src="assets/images/<?php echo $product['image']; ?>" 
                         class="product-image" 
                         alt="<?php echo htmlspecialchars($product['name']); ?>">
                    
                    <!-- Badge nouveau / promotion (exemple) -->
                    <div class="image-badge">
                        ⚡ Nouveau
                    </div>
                </div>
            </div>

            <!-- Colonne informations -->
            <div class="col-lg-6">
                <!-- Catégorie -->
                <div class="text-uppercase text-muted mb-2" style="letter-spacing: 2px;">
                    <?php echo $product['currency']; ?> Collection
                </div>

                <!-- Titre -->
                <h1 class="product-title">
                    <?php echo htmlspecialchars($product['name']); ?>
                </h1>

                <!-- Prix -->
                <div class="product-price">
                    <span class="price-number"><?php echo number_format($product['price'], 0, ',', ' '); ?></span>
                    <span class="price-currency"> <?php echo htmlspecialchars($product['currency']); ?></span>
                </div>

                <!-- Stock (simulé) -->
                <?php 
                $stock = rand(5, 50); // Simulation de stock, à remplacer par une vraie donnée
                $stockClass = $stock > 10 ? 'in-stock' : 'low-stock';
                $stockText = $stock > 10 ? '✓ En stock' : '⚠ Plus que ' . $stock . ' en stock';
                ?>
                <div class="stock-badge <?php echo $stockClass; ?>">
                    <?php echo $stockText; ?>
                </div>

                <!-- Description -->
                <div class="product-description">
                    <p><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
                </div>

                <!-- Formulaire d'achat -->
                <form action="cart.php" method="POST" class="mt-4" id="addToCartForm">
                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                    <input type="hidden" name="action" value="add">
                    
                    <div class="row g-3 align-items-end">
                        <div class="col-md-5">
                            <label class="form-label fw-bold text-muted mb-3">
                                Quantité
                            </label>
                            <div class="quantity-selector-premium">
                                <button type="button" class="quantity-btn-premium" onclick="decrementQuantity()">−</button>
                                <input type="number" name="quantity" id="quantity" value="1" min="1" max="99" class="quantity-input-premium" readonly>
                                <button type="button" class="quantity-btn-premium" onclick="incrementQuantity()">+</button>
                            </div>
                        </div>
                        
                        <div class="col-md-7">
                            <button type="submit" class="add-to-cart-btn">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-2">
                                    <path d="M4 7h16M10 11v6M14 11v6M5 7l1 12a2 2 0 002 2h8a2 2 0 002-2l1-12M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3"/>
                                </svg>
                                Ajouter au panier
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Actions supplémentaires -->
                <div class="d-flex gap-3 mt-4 pt-4 border-top">
                    <button class="btn btn-outline-secondary rounded-pill px-4" onclick="shareProduct()">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-2">
                            <path d="M4 12v8a2 2 0 002 2h12a2 2 0 002-2v-8M16 6l-4-4-4 4M12 2v13"/>
                        </svg>
                        Partager
                    </button>
                    <button class="btn btn-outline-danger rounded-pill px-4" onclick="addToWishlist()">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Produits similaires -->
    <?php if (!empty($similarProducts)): ?>
    <h2 class="similar-products-title">Vous pourriez aussi aimer</h2>
    
    <div class="row g-4 mb-5">
        <?php foreach ($similarProducts as $similar): ?>
        <div class="col-md-6 col-lg-3">
            <div class="similar-card">
                <div class="similar-image">
                    <img src="assets/images/<?php echo $similar['image']; ?>" 
                         alt="<?php echo htmlspecialchars($similar['name']); ?>">
                    <div class="similar-overlay">
                        <a href="product.php?id=<?php echo $similar['id']; ?>" class="view-btn">
                            Voir détails
                        </a>
                    </div>
                </div>
                <div class="similar-info">
                    <h5 class="similar-name"><?php echo htmlspecialchars($similar['name']); ?></h5>
                    <div class="similar-price">
                        <?php echo number_format($similar['price'], 0, ',', ' '); ?> 
                        <?php echo htmlspecialchars($similar['currency']); ?>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>

<!-- Scripts pour les interactions -->
<script>
// Gestion de la quantité
function incrementQuantity() {
    const input = document.getElementById('quantity');
    const max = parseInt(input.getAttribute('max')) || 99;
    let value = parseInt(input.value) || 1;
    if (value < max) {
        input.value = value + 1;
        animateButton('plus');
    }
}

function decrementQuantity() {
    const input = document.getElementById('quantity');
    let value = parseInt(input.value) || 1;
    if (value > 1) {
        input.value = value - 1;
        animateButton('minus');
    }
}

// Animation des boutons
function animateButton(type) {
    const btn = type === 'plus' ? 
        document.querySelector('.quantity-btn-premium:last-child') : 
        document.querySelector('.quantity-btn-premium:first-child');
    
    btn.style.transform = 'scale(0.9)';
    setTimeout(() => {
        btn.style.transform = 'scale(1)';
    }, 200);
}

// Partage du produit
function shareProduct() {
    if (navigator.share) {
        navigator.share({
            title: '<?php echo htmlspecialchars($product['name']); ?>',
            text: '<?php echo htmlspecialchars($product['description']); ?>',
            url: window.location.href
        });
    } else {
        // Fallback : copier le lien
        navigator.clipboard.writeText(window.location.href);
        showNotification('Lien copié dans le presse-papier !');
    }
}

// Wishlist (à implémenter avec PHP)
function addToWishlist() {
    showNotification('Ajouté à vos favoris ❤️');
}

// Notification personnalisée
function showNotification(message) {
    const notification = document.createElement('div');
    notification.className = 'notification-toast';
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 15px 30px;
        border-radius: 50px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        z-index: 1000;
        animation: slideIn 0.5s forwards;
    `;
    notification.textContent = message;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.animation = 'slideOut 0.5s forwards';
        setTimeout(() => notification.remove(), 500);
    }, 3000);
}

// Animation au scroll
document.addEventListener('DOMContentLoaded', function() {
    const elements = document.querySelectorAll('.product-premium, .similar-card');
    elements.forEach((el, index) => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(30px)';
        el.style.transition = 'all 0.6s ease';
        
        setTimeout(() => {
            el.style.opacity = '1';
            el.style.transform = 'translateY(0)';
        }, index * 100);
    });
});

// Confirmation d'ajout au panier
document.getElementById('addToCartForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const quantity = document.getElementById('quantity').value;
    showNotification(`✓ ${quantity} article(s) ajouté(s) au panier`);
    
    // Soumettre le formulaire après la notification
    setTimeout(() => {
        this.submit();
    }, 1000);
});
</script>

<?php include '../includes/footer.php'; ?>