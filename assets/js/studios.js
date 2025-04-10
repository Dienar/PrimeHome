// Обработка избранного для студий
document.querySelectorAll('.btn-favorite[data-property-type="studio"]').forEach(btn => {
    btn.addEventListener('click', function() {
        const propertyId = this.getAttribute('data-property-id');
        const isFavorite = this.classList.contains('active');
        
        if (!isLoggedIn()) {
            window.location.href = 'login.php';
            return;
        }
        
        toggleFavorite(propertyId, isFavorite, this, 'studio');
    });
});