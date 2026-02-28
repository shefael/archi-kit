<?php
// Fichier: includes/header.php
if (session_status() === PHP_SESSION_NONE) { 
    session_start(); 
}

$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="ARCHI KIT - Boutique exclusive d'articles uniques et élégants">
    <title>ARCHI KIT | Boutique Exclusive</title>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Playfair+Display:wght@400;700;900&display=swap" rel="stylesheet">
    
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- AOS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../assets/css/style.css">
    
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        body { 
            font-family: 'Inter', sans-serif; 
            color: #1e293b; 
            background: #f8fafc;
        }

        /* Navbar simplifiée */
        .navbar {
            background: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(0,0,0,0.05);
            padding: 15px 0;
            transition: all 0.3s ease;
        }

        .navbar.scrolled {
            padding: 10px 0;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        }

        .navbar-brand {
            font-family: 'Playfair Display', serif;
            font-weight: 900;
            font-size: 1.5rem;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            display: flex;
            align-items: center;
        }

        .navbar-brand i {
            font-size: 1.2rem;
            margin-right: 8px;
            background: linear-gradient(135deg, #FFD700, #FFA500);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .nav-link {
            color: #2d3748 !important;
            font-weight: 500;
            font-size: 0.9rem;
            padding: 8px 20px !important;
            border-radius: 50px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
        }

        .nav-link i {
            margin-right: 8px;
            font-size: 0.9rem;
        }

        .nav-link:hover {
            background: var(--primary-gradient);
            color: white !important;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        }

        .nav-link.active {
            background: var(--primary-gradient);
            color: white !important;
        }

        /* Cart Badge */
        .cart-icon-wrapper {
            position: relative;
            display: inline-flex;
            align-items: center;
        }

        .cart-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: linear-gradient(135deg, #FFD700, #FFA500);
            color: #1e293b;
            font-size: 0.65rem;
            font-weight: 700;
            padding: 3px 6px;
            border-radius: 50px;
            min-width: 18px;
            text-align: center;
            border: 2px solid white;
        }

        /* Mobile */
        @media (max-width: 991px) {
            .navbar-nav {
                background: white;
                border-radius: 20px;
                padding: 15px;
                margin-top: 15px;
                box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            }
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg sticky-top">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            <i class="fas fa-crown"></i>
            ARCHI KIT
        </a>
        
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link <?php echo $current_page == 'shop.php' ? 'active' : ''; ?>" href="shop.php">
                        <i class="fas fa-store"></i>
                        Boutique
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link <?php echo $current_page == 'cart.php' ? 'active' : ''; ?>" href="cart.php">
                        <div class="cart-icon-wrapper">
                            <i class="fas fa-shopping-bag"></i>
                            Panier
                            <?php 
                                $cart_count = isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0; 
                                if ($cart_count > 0): 
                            ?>
                                <span class="cart-badge"><?php echo $cart_count; ?></span>
                            <?php endif; ?>
                        </div>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<main class="min-vh-100">