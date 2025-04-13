document.querySelector('.sidebar').addEventListener('click', function(e) {
    if (e.target.classList.contains('nav-link')) {
      
      // Ваш код обработки клика
      
      // Удаляем активный класс у всех ссылок
      document.querySelectorAll('.nav-link').forEach(link => {
        link.classList.remove('active');
      });
      
      // Добавляем активный класс к текущей ссылке
      e.target.classList.add('active');
    }
  });