</main> <footer class="bg-white border-top py-5 mt-5">
    <div class="container">
        <div class="row g-4 align-items-center">
            <div class="col-md-4 text-center text-md-start">
                <h4 class="fw-bold mb-2">ARCHI KIT</h4>
                <p class="text-muted small mb-0">&copy; <?php echo date('Y'); ?>. Tous droits réservés.</p>
            </div>
            
            <div class="col-md-4 text-center">
                <p class="small text-muted mb-1">Besoin d'aide ?</p>
                <a href="https://wa.me/243895975137" class="text-dark fw-bold text-decoration-none small">
                    <span class="text-success">●</span> Assistance WhatsApp Directe
                </a>
            </div>
            
            <div class="col-md-4 text-center text-md-end">
                <ul class="list-inline mb-0">
                    <li class="list-inline-item"><a href="shop.php" class="text-muted small text-decoration-none">Boutique</a></li>
                    <li class="list-inline-item ms-3"><a href="../admin/login.php" class="text-muted small text-decoration-none">Admin</a></li>
                </ul>
            </div>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Petit script pour changer l'apparence de la navbar au scroll
    window.addEventListener('scroll', function() {
        if (window.scrollY > 50) {
            document.querySelector('.navbar').classList.add('scrolled');
        } else {
            document.querySelector('.navbar').classList.remove('scrolled');
        }
    });
</script>
</body>
</html>