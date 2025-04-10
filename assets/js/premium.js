document.addEventListener('DOMContentLoaded', function() {
    // Обработка кнопок запроса
    document.querySelectorAll('.btn-request').forEach(btn => {
        btn.addEventListener('click', function() {
            const propertyId = this.getAttribute('data-property-id');
            document.getElementById('requestPropertyId').value = propertyId;
            
            const modal = document.getElementById('requestModal');
            modal.style.display = 'flex';
        });
    });
    
    // Закрытие модального окна
    document.querySelectorAll('.close-modal').forEach(btn => {
        btn.addEventListener('click', function() {
            const modal = this.closest('.modal');
            modal.style.display = 'none';
        });
    });
    
    // Отправка формы запроса
    document.getElementById('propertyRequestForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        fetch('api/premium-request.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Ваш запрос успешно отправлен! Мы свяжемся с вами в ближайшее время.');
                document.getElementById('requestModal').style.display = 'none';
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
});