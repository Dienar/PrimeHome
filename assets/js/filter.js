// Добавьте этот код в ваш script.js или создайте новый файл filter.js
document.addEventListener('DOMContentLoaded', function() {
    // Получаем все элементы фильтрации
    const districtFilter = document.getElementById('district');
    const roomsFilter = document.getElementById('rooms');
    const priceFilter = document.getElementById('price');
    const searchForm = document.querySelector('.search-form');
    const propertyCards = document.querySelectorAll('.studio-card');
    
    // Обработчик формы фильтрации
    searchForm.addEventListener('submit', function(e) {
        e.preventDefault();
        filterProperties();
    });
    
    // Обработчики изменений фильтров
    districtFilter.addEventListener('change', filterProperties);
    roomsFilter.addEventListener('change', filterProperties);
    priceFilter.addEventListener('input', filterProperties);
    
    // Функция фильтрации
    function filterProperties() {
        // Добавляем анимацию загрузки
        const container = document.querySelector('.studios-grid'); 
        const studiosImg = document.querySelector('.studios-grid-img');
        container.style.opacity = '0.5';
        container.style.pointerEvents = 'none';
        
        // Задержка для анимации (300ms вместо 3000ms - 3 секунды это слишком долго)
        setTimeout(() => {
            const districtValue = districtFilter.value;
            const roomsValue = roomsFilter.value;
            const priceValue = parsePrice(priceFilter.value);
           

            propertyCards.forEach(card => {
                const cardDistrict = card.querySelector('.studio-address')?.textContent || '';
                const cardTitle = card.querySelector('.studio-title')?.textContent || '';
                const cardPrice = parsePrice(card.querySelector('.studio-price')?.textContent || '0');
                const cardRooms = extractRoomsFromTitle(cardTitle);
                
                
                const districtMatch = !districtValue || cardDistrict.includes(districtValue);
                const priceMatch = !priceValue || cardPrice <= priceValue;
                const roomsMatch = !roomsValue || checkRoomsMatch(cardRooms, roomsValue);
                
                if (districtMatch && priceMatch && roomsMatch) {
                    card.style.display = 'block';
                    studiosImg.style.display = 'none';
                } else {
                    card.style.display = 'none';
                    studiosImg.style.display = 'block';
                    
                }
            });
            
            // Возвращаем нормальное состояние после фильтрации
            container.style.opacity = '1';
            container.style.pointerEvents = 'auto';
        }, 300); // Уменьшил время до 300ms для лучшего UX
    }
    
    // Вспомогательные функции
    function parsePrice(priceStr) {
        return parseInt(priceStr.replace(/\D/g, '')) || 0;
    }
    
    function extractRoomsFromTitle(title) {
        const roomsMatch = title.match(/(\d+)-?к|однокомнатная|двухкомнатная|трехкомнатная|четырехкомнатная/i);
        if (!roomsMatch) return 0;
        
        if (roomsMatch[1]) return parseInt(roomsMatch[1]);
        
        if (title.match(/однокомнатная/i)) return 1;
        if (title.match(/двухкомнатная/i)) return 2;
        if (title.match(/трехкомнатная/i)) return 3;
        if (title.match(/четырехкомнатная/i)) return 4;
        
        return 0;
    }
    
    function checkRoomsMatch(cardRooms, filterRooms) {
        if (filterRooms === '4+') return cardRooms >= 4;
        return cardRooms === parseInt(filterRooms);
    }
    
    // Инициализация фильтра при загрузке
    filterProperties();
});
