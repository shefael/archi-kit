<?php
// Fichier: public/shop.php
require_once '../config/database.php';
include '../includes/header.php';

$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Requête sécurisée
if (!empty($search)) {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE name LIKE ? OR description LIKE ? ORDER BY id DESC");
    $stmt->execute(["%$search%", "%$search%"]);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $products = $pdo->query("SELECT * FROM products ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
}
?>

<style>
    .shop-header { padding: 60px 0; background: #fff; }
    .search-wrapper { position: relative; max-width: 600px; margin: 0 auto; }
    .search-input { 
        padding: 15px 25px 15px 50px; 
        border-radius: 50px; 
        border: 1px solid #eee; 
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        transition: all 0.3s;
    }
    .search-input:focus { border-color: #000; box-shadow: 0 10px 40px rgba(0,0,0,0.1); outline: none; }
    .search-icon { position: absolute; left: 20px; top: 50%; transform: translateY(-50%); color: #999; }

    .product-card { 
        transition: transform 0.4s cubic-bezier(0.165, 0.84, 0.44, 1), box-shadow 0.4s;
        border: none;
        background: #fff;
    }
    .product-card:hover { 
        transform: translateY(-8px); 
        box-shadow: 0 20px 40px rgba(0,0,0,0.08) !important; 
    }
    .img-container { overflow: hidden; position: relative; }
    .product-img { transition: transform 0.6s ease; }
    .product-card:hover .product-img { transform: scale(1.08); }
    
    .price-tag { font-weight: 800; color: #1a1a1a; font-size: 1.2rem; }
    .category-label { font-size: 0.7rem; text-transform: uppercase; letter-spacing: 1px; color: #adb5bd; font-weight: 700; }
    
    .btn-add-cart { 
        background: #000; color: #fff; border: none; border-radius: 10px; 
        padding: 12px; font-weight: 600; transition: 0.3s; 
    }
    .btn-add-cart:hover { background: #333; transform: scale(1.02); }
</style>

<section class="shop-header text-center">
    <div class="container">
        <h1 class="display-5 fw-bold mb-4">Notre Boutique</h1>
        
        <div class="search-wrapper">
            <form action="shop.php" method="GET">
                <span class="search-icon">🔍</span>
                <input type="text" name="search" class="search-input w-100" 
                       placeholder="Quel article recherchez-vous ?" 
                       value="<?php echo htmlspecialchars($search); ?>">
            </form>
        </div>

        <?php if (!empty($search)): ?>
            <div class="mt-4">
                <p class="text-muted">Résultats pour "<strong><?php echo htmlspecialchars($search); ?></strong>" 
                   — <a href="shop.php" class="text-dark fw-bold">Tout afficher</a></p>
            </div>
        <?php endif; ?>
    </div>
</section>

<div class="container pb-5">
    <div class="row g-4">
        <?php if (count($products) > 0): ?>
            <?php foreach ($products as $p): 
                $img_path = !empty($p['image']) ? "assets/images/".$p['image'] : "assets/images/default.jpg";
            ?>
            <div class="col-6 col-md-4 col-lg-3">
                <div class="card h-100 product-card shadow-sm rounded-4">
                    <div class="img-container rounded-t-4">
                        <a href="product.php?id=<?php echo $p['id']; ?>">
                            <img src="<?php echo $img_path; ?>" class="product-img card-img-top" 
                                 style="height:280px; object-fit:cover;" 
                                 alt="<?php echo htmlspecialchars($p['name']); ?>">
                        </a>
                    </div>
                    
                    <div class="card-body px-3 pb-3">
                        <div class="category-label mb-1">Nouveauté</div>
                        <h6 class="card-title fw-bold mb-2">
                            <a href="product.php?id=<?php echo $p['id']; ?>" class="text-decoration-none text-dark">
                                <?php echo htmlspecialchars($p['name']); ?>
                            </a>
                        </h6>
                        
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <span class="price-tag">
                                <?php echo number_format($p['price'], 0, ',', ' '); ?> 
                                <small style="font-size: 0.7em;"><?php echo $p['currency']; ?></small>
                            </span>
                        </div>

                        <div class="mt-3">
                            <form action="cart.php" method="POST">
                                <input type="hidden" name="product_id" value="<?php echo $p['id']; ?>">
                                <button type="submit" class="btn-add-cart w-100 d-flex align-items-center justify-content-center gap-2">
                                    <small>Ajouter au panier</small>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>

        <?php else: ?>
            <div class="col-12 text-center py-5">
                <div style="font-size: 50px;">🔍</div>
                <h3 class="fw-bold mt-3">Aucun résultat trouvé</h3>
                <p class="text-muted">Nous n'avons trouvé aucun article correspondant à votre recherche.</p>
                <a href="shop.php" class="btn btn-dark rounded-pill px-4">Voir toute la collection</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include '../includes/footer.php'; ?>