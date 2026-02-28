<?php
// Fichier: public/shop.php
require_once '../config/database.php';
include '../includes/header.php';

// Recherche uniquement (suppression des filtres)
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Requête SQL simplifiée
if (!empty($search)) {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE name LIKE ? OR description LIKE ? ORDER BY id DESC");
    $stmt->execute(["%$search%", "%$search%"]);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $products = $pdo->query("SELECT * FROM products ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!-- Styles premium pour la boutique -->
<style>
    /* Hero Section réduite */
    .shop-hero {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 40px 0; /* Réduit de 80px à 40px */
        margin-bottom: 40px;
        border-radius: 0 0 30px 30px;
        position: relative;
        overflow: hidden;
    }

    .shop-hero::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.2) 0%, transparent 70%);
        animation: rotate 30s linear infinite;
    }

    @keyframes rotate {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }

    .hero-content {
        position: relative;
        z-index: 1;
        color: white;
        text-align: center;
    }

    .hero-title {
        font-size: 2.2rem; /* Réduit de 3.5rem à 2.2rem */
        font-weight: 700;
        margin-bottom: 10px;
        text-shadow: 0 2px 20px rgba(0,0,0,0.2);
    }

    .hero-subtitle {
        font-size: 1rem; /* Réduit de 1.2rem à 1rem */
        opacity: 0.9;
        max-width: 500px;
        margin: 0 auto;
    }

    /* Barre de recherche */
    .search-wrapper {
        max-width: 500px;
        margin: -25px auto 40px; /* Ajusté */
        position: relative;
        z-index: 10;
    }

    .search-box {
        background: white;
        border-radius: 50px;
        padding: 5px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        display: flex;
        align-items: center;
    }

    .search-input {
        flex: 1;
        border: none;
        padding: 12px 20px;
        font-size: 1rem;
        background: transparent;
    }

    .search-input:focus {
        outline: none;
    }

    .search-btn {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        padding: 12px 30px;
        border-radius: 50px;
        font-weight: 500;
        margin: 3px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .search-btn:hover {
        transform: translateX(3px);
        box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
    }

    /* Compteur de résultats */
    .results-count {
        background: white;
        padding: 12px 20px;
        border-radius: 50px;
        margin-bottom: 30px;
        display: inline-block;
        box-shadow: 0 5px 15px rgba(0,0,0,0.03);
        font-size: 0.95rem;
    }

    .count-badge {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 4px 12px;
        border-radius: 30px;
        font-size: 0.8rem;
        margin-left: 10px;
    }

    /* Grille de produits - taille réduite */
    .products-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); /* Réduit de 300px à 240px */
        gap: 20px; /* Réduit de 30px à 20px */
        margin-top: 20px;
    }

    .product-card {
        background: white;
        border-radius: 20px; /* Réduit de 40px à 20px */
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
        border: 1px solid rgba(226, 232, 240, 0.3);
    }

    .product-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(102, 126, 234, 0.15);
    }

    .product-image-wrapper {
        position: relative;
        overflow: hidden;
        height: 200px; /* Réduit de 280px à 200px */
        background: #f8fafc;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .product-image {
        max-width: 90%;
        max-height: 90%;
        width: auto;
        height: auto;
        object-fit: contain; /* Changé de cover à contain pour centraliser */
        transition: all 0.4s ease;
    }

    .product-card:hover .product-image {
        transform: scale(1.05);
    }

    .product-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(102, 126, 234, 0.9);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: all 0.3s ease;
    }

    .product-card:hover .product-overlay {
        opacity: 1;
    }

    .overlay-btn {
        padding: 10px 20px;
        background: white;
        border-radius: 30px;
        color: #667eea;
        text-decoration: none;
        font-size: 0.9rem;
        font-weight: 600;
        transform: translateY(10px);
        transition: all 0.3s ease;
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }

    .product-card:hover .overlay-btn {
        transform: translateY(0);
    }

    .overlay-btn:hover {
        background: #667eea;
        color: white;
    }

    .product-info {
        padding: 15px;
        text-align: center;
    }

    .product-category {
        color: #667eea;
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 5px;
    }

    .product-name {
        font-size: 1rem;
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 8px;
        line-height: 1.3;
        height: 40px;
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }

    .product-price {
        font-size: 1.3rem;
        font-weight: 700;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 10px;
    }

    .add-to-cart-mini {
        width: 100%;
        padding: 8px;
        background: linear-gradient(135deg, #48bb78, #38a169);
        color: white;
        border: none;
        border-radius: 30px;
        font-size: 0.9rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .add-to-cart-mini:hover {
        transform: scale(1.02);
        box-shadow: 0 5px 15px rgba(72, 187, 120, 0.3);
    }

    /* État vide */
    .empty-state {
        text-align: center;
        padding: 50px 20px;
        background: white;
        border-radius: 30px;
        margin: 20px 0;
    }

    .empty-emoji {
        font-size: 4rem;
        margin-bottom: 15px;
    }

    .empty-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 10px;
    }

    .reset-filters {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 10px 30px;
        border-radius: 50px;
        text-decoration: none;
        font-size: 0.9rem;
        display: inline-block;
        transition: all 0.3s ease;
    }

    .reset-filters:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
    }

    /* Animations */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .product-card {
        animation: fadeInUp 0.5s ease forwards;
        opacity: 0;
    }
</style>

<!-- Hero Section réduite -->
<div class="shop-hero">
    <div class="container hero-content">
        <h1 class="hero-title">Notre Collection</h1>
        <p class="hero-subtitle">Découvrez des articles uniques et élégants, sélectionnés pour vous</p>
    </div>
</div>

<!-- Barre de recherche -->
<div class="container">
    <div class="search-wrapper">
        <form action="shop.php" method="GET" class="search-box">
            <input type="text" 
                   name="search" 
                   class="search-input" 
                   placeholder="Rechercher un article..."
                   value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit" class="search-btn">Rechercher</button>
        </form>
    </div>

    <!-- Compteur de résultats -->
    <?php if (!empty($search)): ?>
    <div class="results-count">
        <strong><?php echo count($products); ?></strong> résultat(s) pour "<?php echo htmlspecialchars($search); ?>"
        <a href="shop.php" class="count-badge text-decoration-none text-white">✖ Réinitialiser</a>
    </div>
    <?php endif; ?>

    <!-- Grille de produits -->
    <?php if (count($products) > 0): ?>
    <div class="products-grid">
        <?php foreach ($products as $p): ?>
        <div class="product-card">
            <div class="product-image-wrapper">
                <img src="assets/images/<?php echo $p['image']; ?>" 
                     class="product-image" 
                     alt="<?php echo htmlspecialchars($p['name']); ?>"
                     onerror="this.src='assets/images/placeholder.jpg'">
                
                <div class="product-overlay">
                    <a href="product.php?id=<?php echo $p['id']; ?>" class="overlay-btn">
                        Voir détail
                    </a>
                </div>
            </div>
            
            <div class="product-info">
                <div class="product-category"><?php echo htmlspecialchars($p['currency']); ?></div>
                <h3 class="product-name"><?php echo htmlspecialchars($p['name']); ?></h3>
                <div class="product-price">
                    <?php echo number_format($p['price'], 0, ',', ' '); ?> <?php echo $p['currency']; ?>
                </div>
                
                <form action="cart.php" method="POST">
                    <input type="hidden" name="product_id" value="<?php echo $p['id']; ?>">
                    <input type="hidden" name="action" value="add">
                    <input type="hidden" name="quantity" value="1">
                    <button type="submit" class="add-to-cart-mini">
                        <i class="fas fa-shopping-cart me-1"></i> Ajouter
                    </button>
                </form>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <?php else: ?>
    <!-- État vide -->
    <div class="empty-state">
        <div class="empty-emoji">🔍</div>
        <h2 class="empty-title">Aucun résultat</h2>
        <p class="text-muted mb-4">Aucun article ne correspond à votre recherche.</p>
        <a href="shop.php" class="reset-filters">Voir tous les articles</a>
    </div>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>