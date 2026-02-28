/**
 * MAIN.JS - Scripts globaux pour toutes les pages
 * ARCHI KIT - Boutique Exclusive
 */

// ========================================
// INITIALISATION GLOBALE
// ========================================
document.addEventListener('DOMContentLoaded', () => {
    initAOS();
    initNavbarScroll();
    initNavLinks();
    setActiveNavLink();
    initImageFallback();
    
    // Initialisations spécifiques selon la page
    const currentPage = window.location.pathname.split('/').pop();
    
    switch(currentPage) {
        case 'shop.php':
            initShopPage();
            break;
        case 'product.php':
            initProductPage();
            break;
        case 'cart.php':
            initCartPage();
            break;
        case 'order.php':
            initOrderPage();
            break;
    }
});

// ========================================
// FONCTIONS GLOBALES
// ========================================

// Initialisation AOS
function initAOS() {
    if (typeof AOS !== 'undefined') {
        AOS.init({
            duration: 600,
            once: true,
            offset: 50
        });
    }
}

// Gestion du scroll pour la navbar
function initNavbarScroll() {
    const navbar = document.querySelector('.navbar');
    if (!navbar) return;

    window.addEventListener('scroll', () => {
        if (window.scrollY > 50) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    });
}

// Animation des liens de navigation
function initNavLinks() {
    document.querySelectorAll('.nav-link').forEach(link => {
        link.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
        });
        
        link.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
}

// Détection de la page active
function setActiveNavLink() {
    const currentPage = window.location.pathname.split('/').pop();
    document.querySelectorAll('.nav-link').forEach(link => {
        const href = link.getAttribute('href');
        if (href === currentPage || (currentPage === '' && href === 'index.php')) {
            link.classList.add('active');
        }
    });
}

// Gestionnaire d'images par défaut
function initImageFallback() {
    document.querySelectorAll('img').forEach(img => {
        img.addEventListener('error', function() {
            this.src = 'assets/images/placeholder.jpg';
        });
    });
}

// ========================================
// SYSTÈME DE NOTIFICATIONS
// ========================================
function showNotification(message, type = 'info') {
    // Créer le conteneur si nécessaire
    let container = document.getElementById('notificationContainer');
    if (!container) {
        container = document.createElement('div');
        container.id = 'notificationContainer';
        container.style.position = 'fixed';
        container.style.top = '100px';
        container.style.right = '30px';
        container.style.zIndex = '1000';
        document.body.appendChild(container);
    }

    // Créer la notification
    const notification = document.createElement('div');
    notification.className = 'notification-toast';
    
    // Configuration selon le type
    const config = {
        success: { bg: 'linear-gradient(135deg, #11998e, #38ef7d)', icon: '✓' },
        error: { bg: 'linear-gradient(135deg, #ee5a24, #ff6b6b)', icon: '⚠' },
        info: { bg: 'linear-gradient(135deg, #667eea, #764ba2)', icon: 'ℹ' }
    };
    
    const { bg, icon } = config[type] || config.info;
    
    notification.style.background = bg;
    notification.style.color = 'white';
    notification.style.padding = '15px 30px';
    notification.style.borderRadius = '50px';
    notification.style.boxShadow = '0 10px 30px rgba(0,0,0,0.2)';
    notification.style.marginBottom = '10px';
    notification.style.transform = 'translateX(400px)';
    notification.style.transition = 'transform 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55)';
    
    notification.innerHTML = `<span style="margin-right: 10px;">${icon}</span>${message}`;
    
    container.appendChild(notification);
    
    // Animation d'entrée
    setTimeout(() => {
        notification.style.transform = 'translateX(0)';
    }, 100);
    
    // Auto-suppression
    setTimeout(() => {
        notification.style.transform = 'translateX(400px)';
        setTimeout(() => notification.remove(), 500);
    }, 3000);
}

// Rendre disponible globalement
window.showNotification = showNotification;

// ========================================
// FONCTIONS SPÉCIFIQUES SHOP
// ========================================
function initShopPage() {
    // Animation des cartes produits
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);

    document.querySelectorAll('.product-card').forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(30px)';
        card.style.transition = 'all 0.5s ease';
        observer.observe(card);
    });
}

// ========================================
// FONCTIONS SPÉCIFIQUES PRODUCT
// ========================================
function initProductPage() {
    // Gestion de la quantité
    const quantityInput = document.getElementById('quantity');
    if (!quantityInput) return;

    window.incrementQuantity = function() {
        let value = parseInt(quantityInput.value) || 1;
        const max = parseInt(quantityInput.getAttribute('max')) || 99;
        if (value < max) {
            quantityInput.value = value + 1;
            animateButton('plus');
        }
    };

    window.decrementQuantity = function() {
        let value = parseInt(quantityInput.value) || 1;
        if (value > 1) {
            quantityInput.value = value - 1;
            animateButton('minus');
        }
    };

    function animateButton(type) {
        const btn = type === 'plus' 
            ? document.querySelector('.quantity-btn:last-child')
            : document.querySelector('.quantity-btn:first-child');
        
        if (btn) {
            btn.style.transform = 'scale(0.9)';
            setTimeout(() => {
                btn.style.transform = 'scale(1)';
            }, 200);
        }
    }
}

// ========================================
// FONCTIONS SPÉCIFIQUES CART
// ========================================
function initCartPage() {
    // Confirmation de suppression
    document.querySelectorAll('.btn-delete').forEach(btn => {
        btn.addEventListener('click', function(e) {
            if (!confirm('Voulez-vous vraiment supprimer cet article ?')) {
                e.preventDefault();
            }
        });
    });
    
    // Animation d'apparition
    const elements = document.querySelectorAll('.cart-table, .cart-total');
    elements.forEach((el, index) => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(20px)';
        el.style.transition = 'all 0.5s ease';
        
        setTimeout(() => {
            el.style.opacity = '1';
            el.style.transform = 'translateY(0)';
        }, index * 100);
    });
}

// ========================================
// FONCTIONS SPÉCIFIQUES ORDER
// ========================================
function initOrderPage() {
    // Animation d'apparition
    const elements = document.querySelectorAll('.order-product-item, .order-total');
    elements.forEach((el, index) => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(20px)';
        el.style.transition = 'all 0.5s ease';
        
        setTimeout(() => {
            el.style.opacity = '1';
            el.style.transform = 'translateY(0)';
        }, index * 100);
    });
}

window.confirmAndRedirect = function(url) {
    const totalItems = document.querySelectorAll('.order-product-item').length;
    const message = `Confirmation\n\nVous allez être redirigé vers WhatsApp avec ${totalItems} article(s).\nVoulez-vous continuer ?`;
    
    if (confirm(message)) {
        showNotification('Redirection vers WhatsApp...', 'success');
        setTimeout(() => {
            window.location.href = url;
        }, 1500);
    }
};