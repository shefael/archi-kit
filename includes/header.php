<?php if (session_status() === PHP_SESSION_NONE) { session_start(); } ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ARCHI KIT | Boutique Exclusive</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    
    <style>
        body { font-family: 'Inter', sans-serif; color: #1a1a1a; background-color: #fcfcfc; }
        .navbar { 
            backdrop-filter: blur(10px); 
            background-color: rgba(255, 255, 255, 0.8) !important; 
            border-bottom: 1px solid rgba(0,0,0,0.05);
            padding: 15px 0;
        }
        .navbar-brand { 
            font-family: 'Playfair Display', serif; 
            font-weight: 800; 
            letter-spacing: 1px; 
            color: #000 !important;
            font-size: 1.5rem;
        }
        .nav-link { 
            color: #444 !important; 
            font-weight: 500; 
            margin-left: 20px;
            transition: color 0.3s;
        }
        .nav-link:hover { color: #000 !important; }
        
        /* Badge Panier Stylisé */
        .cart-badge {
            position: absolute;
            top: -5px;
            right: -10px;
            background: #000;
            color: #fff;
            font-size: 0.65rem;
            padding: 2px 6px;
            border-radius: 50px;
            font-weight: 800;
        }
        .cart-icon-wrapper { position: relative; }
        
        /* Animation au défilement */
        .navbar.scrolled { padding: 10px 0; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg sticky-top">
  <div class="container">
    <a class="navbar-brand" href="index.php">ARCHI KIT</a>
    
    <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto align-items-center">
        <li class="nav-item"><a class="nav-link" href="shop.php">Boutique</a></li>
        <li class="nav-item">
          <a class="nav-link cart-icon-wrapper" href="cart.php">
            <span>Panier</span>
            <?php 
              $cart_count = isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0; 
              if ($cart_count > 0): 
            ?>
              <span class="cart-badge"><?php echo $cart_count; ?></span>
            <?php endif; ?>
          </a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<main class="min-vh-100">