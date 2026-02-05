<?php
// Fichier: admin/edit_product.php
session_start();
if (!isset($_SESSION['admin_logged_in'])) { header('Location: login.php'); exit; }
require_once '../config/database.php';

$id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch();

if (!$product) { header('Location: dashboard.php'); exit; }

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $currency = $_POST['currency'];
    $description = $_POST['description'];
    $image_name = $product['image'];

    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image_name = time() . '_' . preg_replace("/[^a-zA-Z0-9.]/", "_", $_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], "../public/assets/images/" . $image_name);
    }

    $stmt = $pdo->prepare("UPDATE products SET name=?, description=?, price=?, currency=?, image=? WHERE id=?");
    $stmt->execute([$name, $description, $price, $currency, $image_name, $id]);
    
    header("Location: dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier Produit</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5" style="max-width: 700px;">
    <div class="card shadow border-0">
        <div class="card-header bg-warning">
            <h3 class="mb-0">Modifier l'article</h3>
        </div>
        <div class="card-body">
            <form method="post" enctype="multipart/form-data">
                <div class="mb-3">
                    <label class="form-label">Nom du produit</label>
                    <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($product['name']); ?>" required>
                </div>

                <div class="row">
                    <div class="col-md-8 mb-3">
                        <label class="form-label">Prix</label>
                        <input type="number" name="price" class="form-control" value="<?php echo $product['price']; ?>" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Devise</label>
                        <input type="text" name="currency" class="form-control" value="<?php echo htmlspecialchars($product['currency']); ?>" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="3"><?php echo htmlspecialchars($product['description']); ?></textarea>
                </div>

                <div class="mb-3 text-center border p-2 bg-white">
                    <label class="form-label d-block text-start text-muted">Image actuelle :</label>
                    <img src="../public/assets/images/<?php echo $product['image']; ?>" width="120" class="rounded mb-2">
                    <input type="file" name="image" class="form-control">
                </div>

                <div class="d-flex justify-content-between">
                    <a href="dashboard.php" class="btn btn-secondary">Annuler</a>
                    <button type="submit" class="btn btn-primary px-4">Mettre à jour</button>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>