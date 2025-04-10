const loginBtn = document.getElementById('loginBtn');

loginBtn.addEventListener('click', function(e) {
    if(location.href === 'index.php'){
    e.preventDefault();
    }else{
       window.location.href = "index.php";
    }
});


