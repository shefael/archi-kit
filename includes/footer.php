</main>

<footer class="footer-premium">
    <div class="container">
        <div class="row">
            <!-- Colonne 1: Brand et description -->
            <div class="col-lg-6" data-aos="fade-up">
                <div class="footer-brand">
                    <i class="fas fa-crown footer-brand-icon"></i>
                    <span class="footer-brand-text">ARCHI KIT</span>
                </div>
                <p class="footer-description">
                    Découvrez une sélection exclusive d'articles uniques et élégants, 
                    soigneusement choisis pour ceux qui apprécient le raffinement et la qualité.
                </p>
            </div>

            <!-- Colonne 2: Besoin d'aide simplifié -->
            <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
                <h5 class="footer-title">Besoin d'aide ?</h5>
                
                <div class="contact-info">
                    <!-- Téléphone uniquement -->
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-phone-alt"></i>
                        </div>
                        <div>
                            <span class="contact-label">Téléphone</span>
                            <a href="tel:+243895975137" class="contact-value">+243 89 597 5137</a>
                        </div>
                    </div>

                    <!-- WhatsApp uniquement -->
                    <div class="contact-item">
                        <div class="contact-icon" style="background: rgba(37, 211, 102, 0.1);">
                            <i class="fab fa-whatsapp" style="color: #25D366;"></i>
                        </div>
                        <div>
                            <span class="contact-label">WhatsApp</span>
                            <a href="https://wa.me/243895975137" class="contact-value" target="_blank">Assistance directe</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Copyright Bar simplifiée -->
        <div class="footer-bottom">
            <div class="row">
                <div class="col-md-6">
                    <p class="copyright">
                        &copy; <?php echo date('Y'); ?> ARCHI KIT. Tous droits réservés.
                    </p>
                </div>
                <div class="col-md-6 text-md-end">
                    <ul class="footer-bottom-links">
                        <li><a href="../admin/login.php">Admin</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</footer>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

<!-- Script simplifié -->
<script>
    // Initialisation AOS
    AOS.init({
        duration: 600,
        once: true
    });

    // Gestion du scroll pour la navbar (uniquement)
    window.addEventListener('scroll', function() {
        const navbar = document.querySelector('.navbar');
        if (window.scrollY > 50) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    });

    // Animation des liens
    document.querySelectorAll('.nav-link').forEach(link => {
        link.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
        });
        
        link.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
</script>

<!-- Styles simplifiés pour le footer -->
<style>
    .footer-premium {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        color: #94a3b8;
        margin-top: 60px;
        padding: 50px 0 0;
    }

    .footer-brand {
        display: flex;
        align-items: center;
        margin-bottom: 20px;
    }

    .footer-brand-icon {
        font-size: 1.8rem;
        background: linear-gradient(135deg, #FFD700, #FFA500);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-right: 10px;
    }

    .footer-brand-text {
        font-family: 'Playfair Display', serif;
        font-size: 1.5rem;
        font-weight: 800;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .footer-description {
        font-size: 0.9rem;
        line-height: 1.6;
        max-width: 400px;
        color: #cbd5e1;
    }

    .footer-title {
        color: white;
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 20px;
        position: relative;
        padding-bottom: 10px;
    }

    .footer-title::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 40px;
        height: 2px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .contact-info {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .contact-item {
        display: flex;
        align-items: center;
    }

    .contact-icon {
        width: 40px;
        height: 40px;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
        color: #667eea;
    }

    .contact-label {
        display: block;
        font-size: 0.75rem;
        color: #94a3b8;
        margin-bottom: 2px;
    }

    .contact-value {
        color: white;
        text-decoration: none;
        font-size: 0.95rem;
        font-weight: 500;
        transition: color 0.3s;
    }

    .contact-value:hover {
        color: #667eea;
    }

    .footer-bottom {
        border-top: 1px solid rgba(255, 255, 255, 0.1);
        margin-top: 40px;
        padding: 20px 0;
    }

    .copyright {
        margin: 0;
        font-size: 0.85rem;
        color: #94a3b8;
    }

    .footer-bottom-links {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .footer-bottom-links li {
        display: inline-block;
    }

    .footer-bottom-links a {
        color: #94a3b8;
        text-decoration: none;
        font-size: 0.85rem;
        transition: color 0.3s;
    }

    .footer-bottom-links a:hover {
        color: white;
    }

    @media (max-width: 768px) {
        .footer-description {
            max-width: 100%;
        }
        
        .footer-bottom-links {
            margin-top: 10px;
            text-align: left;
        }
    }
</style>

</body>
</html>