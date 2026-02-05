<?php
// Fichier: admin/add_product.php
session_start();
if (!isset($_SESSION['admin_logged_in'])) { header('Location: login.php'); exit; }
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $currency = $_POST['currency']; // Nouvelle devise
    $description = $_POST['description'];
    
    // Gestion de l'image
    $image_name = "default.jpg";
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image_name = time() . '_' . preg_replace("/[^a-zA-Z0-9.]/", "_", $_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], "../public/assets/images/" . $image_name);
    }

    $stmt = $pdo->prepare("INSERT INTO products (name, description, price, currency, image) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$name, $description, $price, $currency, $image_name]);
    
    header("Location: dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un Produit</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5" style="max-width: 700px;">
    <div class="card shadow border-0">
        <div class="card-header bg-success text-white">
            <h3 class="mb-0">Ajouter un nouvel article</h3>
        </div>
        <div class="card-body">
            <form method="post" enctype="multipart/form-data">
                <div class="mb-3">
                    <label class="form-label">Nom du produit</label>
                    <input type="text" name="name" class="form-control" required placeholder="Ex: Chaussure Nike">
                </div>

                <div class="row">
                    <div class="col-md-8 mb-3">
                        <label class="form-label">Prix (Chiffres uniquement)</label>
                        <input type="number" name="price" class="form-control" required placeholder="Ex: 50">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Devise</label>
                        <input type="text" name="currency" class="form-control" required placeholder="$, €, FCFA...">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="3"></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Photo de l'article</label>
                    <input type="file" name="image" class="form-control">
                </div>

                <div class="d-flex justify-content-between border-top pt-3">
                    <a href="dashboard.php" class="btn btn-secondary">Retour</a>
                    <button type="submit" class="btn btn-success px-4">Enregistrer l'article</button>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>