<?php
// Fichier: admin/dashboard.php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
if (!isset($_SESSION['admin_logged_in'])) { header('Location: login.php'); exit; }
require_once '../config/database.php';

$products = $pdo->query("SELECT * FROM products ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Admin - Gestion</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-dark bg-dark mb-4">
    <div class="container">
        <span class="navbar-brand">🛠 Administration</span>
        <a href="../public/index.php" class="btn btn-info btn-sm">Voir la boutique</a>
    </div>
</nav>

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>📦 Stock Actuel</h3>
        <a href="add_product.php" class="btn btn-success">+ Nouveau Produit</a>
    </div>

    <div class="card shadow-sm">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Image</th>
                    <th>Nom</th>
                    <th>Prix</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $p): ?>
                <tr>
                    <td><img src="../public/assets/images/<?php echo $p['image']; ?>" width="50" height="50" style="object-fit:cover;" class="rounded shadow-sm"></td>
                    <td><?php echo htmlspecialchars($p['name']); ?></td>
                    <td><strong><?php echo number_format($p['price'], 0, ',', ' '); ?> <?php echo htmlspecialchars($p['currency']); ?></strong></td>
                    <td class="text-end">
                        <a href="edit_product.php?id=<?php echo $p['id']; ?>" class="btn btn-sm btn-warning">Modifier</a>
                        <a href="delete_product.php?id=<?php echo $p['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer ?')">Supprimer</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>