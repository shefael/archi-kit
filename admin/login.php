<?php
session_start();
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    // ... après avoir récupéré $user dans la base de données ...
if ($user && password_verify($password, $user['password'])) {
    $_SESSION['admin_logged_in'] = true;
    header('Location: dashboard.php');
    exit;
} else {
    $error = "Identifiants incorrects";
}
}
?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<div class="container mt-5" style="max-width:400px;">
    <h3>Connexion Admin</h3>
    <?php if(isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
    <form method="post">
        <input type="text" name="username" class="form-control mb-2" placeholder="Utilisateur" required>
        <input type="password" name="password" class="form-control mb-2" placeholder="Mot de passe" required>
        <button type="submit" class="btn btn-dark w-100">Se connecter</button>
    </form>
</div>