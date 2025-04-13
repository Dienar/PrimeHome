
const logBtn = document.getElementById('loginBtn');

logBtn.addEventListener('click', function(e) {
    if(location.href === 'index.php'){
    e.preventDefault();
    }else{
       window.location.href = "index.php";
    }
});