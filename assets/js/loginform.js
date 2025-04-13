document.querySelectorAll('.btn-favorite').forEach(btn => {
    btn.addEventListener('click', async function(e) {
    e.preventDefault();
    
    const propertyId = this.dataset.propertyId;
    const propertyType = this.dataset.propertyType;
    const isActive = this.classList.contains('active');
    
    try {
        const response = await fetch('handlers/favorite_handler.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `action=${isActive ? 'remove' : 'add'}&property_id=${propertyId}&property_type=${propertyType}`
        });
        
        const data = await response.json();
        
        if (data.success) {
            // Обновляем кнопку без перезагрузки
            this.classList.toggle('active');
            this.innerHTML = isActive 
                ? '<i class="far fa-heart"></i> В избранное'
                : '<i class="fas fa-heart"></i> В избранном';
            
            // Показываем постоянное уведомление
            showPersistentNotification(data.message);
        } else {
            showPersistentNotification(data.message, 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showPersistentNotification('Ошибка соединения', 'error');
    }
    });
    });
    
    // Функция для показа постоянного уведомления
    function showPersistentNotification(message, type = 'success') {
    // Удаляем предыдущие уведомления
    const oldNotifications = document.querySelectorAll('.persistent-notification');
    oldNotifications.forEach(el => el.remove());
    
    // Создаем новое уведомление
    const notification = document.createElement('div');
    notification.className = `persistent-notification ${type}`;
    notification.textContent = message;
    
    // Добавляем кнопку закрытия
    const closeBtn = document.createElement('span');
    closeBtn.className = 'close-notification';
    closeBtn.innerHTML = '&times;';
    closeBtn.addEventListener('click', () => notification.remove());
    
    notification.appendChild(closeBtn);
    document.body.appendChild(notification);
    
    // Автоматическое закрытие через 5 секунд
    setTimeout(() => {
    notification.style.opacity = '0';
    setTimeout(() => notification.remove(), 300);
    }, 5000);
    }

    
