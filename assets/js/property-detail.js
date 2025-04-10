document.addEventListener('DOMContentLoaded', function() {
    // Открытие модального окна контакта
    document.getElementById('contactOwnerBtn').addEventListener('click', function() {
        document.getElementById('contactModal').style.display = 'flex';
    });
    
    // Закрытие модальных окон
    document.querySelectorAll('.close-modal').forEach(btn => {
        btn.addEventListener('click', function() {
            this.closest('.modal').style.display = 'none';
        });
    });
    document.getElementById('btnback').addEventListener('click', function(){
        local = localStorage.getItem('location');
        window.location.href = local;
    })

    // Клик вне модального окна
    window.addEventListener('click', function(e) {
        if (e.target.classList.contains('modal')) {
            e.target.style.display = 'none';
        }
    });
    
    // Отправка формы контакта
    document.getElementById('contactForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        fetch('api/contact-owner.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Ваш запрос успешно отправлен! Мы свяжемся с вами в ближайшее время.');
                document.getElementById('contactModal').style.display = 'none';
                this.reset();
            } else {
                alert('Произошла ошибка: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Произошла ошибка при отправке запроса');
        });
    });
    
    // Обработка избранного
    const favoriteBtn = document.querySelector('.btn-favorite');
    if (favoriteBtn) {
        favoriteBtn.addEventListener('click', function() {
            const propertyId = this.getAttribute('data-property-id');
            const propertyType = this.getAttribute('data-property-type');
            const isFavorite = this.classList.contains('active');
            
            toggleFavorite(propertyId, isFavorite, this, propertyType);
        });
    }
});

// Функция для работы с избранным
function toggleFavorite(propertyId, isFavorite, button, propertyType) {
    const formData = new FormData();
    formData.append('property_id', propertyId);
    formData.append('property_type', propertyType);
    formData.append('action', isFavorite ? 'remove' : 'add');
    
    fetch('api/favorites.php', {
        method: 'POST',
        body: formData,
        credentials: 'same-origin'
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (isFavorite) {
                button.classList.remove('active');
                button.innerHTML = '<i class="far fa-heart"></i> В избранное';
            } else {
                button.classList.add('active');
                button.innerHTML = '<i class="fas fa-heart"></i> В избранном';
            }
        } else {
            alert(data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Произошла ошибка при обновлении избранного');
    });
}
