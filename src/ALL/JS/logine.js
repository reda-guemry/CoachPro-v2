




function loginrequest(datajson) {
    fetch("../../BACK/API/logine.php" , {
            method : "POST" , 
            body : datajson
        })
        .then(rep => rep.text())
        .then(data => {
            if(data == "success"){
                document.getElementById('loginForm').reset()
                verifysesionlog() ;
            }else {
                window.location.href = "login.html" ; 
            }
        })
        .catch(eroor => console.log(eroor))
}




document.getElementById('togglePassword').addEventListener('click', function() {
    const password = document.getElementById('password');
    const icon = this.querySelector('i');
    
    if (password.type === 'password') {
        password.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        password.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
});

// document.getElementById('loginForm').addEventListener('submit', (e) => {
//     e.preventDefault();
    
//     const logindata = new FormData(document.getElementById('loginForm')) ; 
//     const email = document.getElementById('email').value;
//     const password = document.getElementById('password').value;
//     const emailError = document.getElementById('emailError');
//     const passwordError = document.getElementById('passwordError');
    
    
//     const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
//     let isValid = true;
    
//     emailError.classList.add('hidden');
//     passwordError.classList.add('hidden');
    
//     if (!emailRegex.test(email)) {
//         emailError.classList.remove('hidden');
//         isValid = false;
//     }
    
//     if (password.length < 6) {
//         passwordError.textContent = 'Le mot de passe doit contenir au moins 6 caractères';
//         passwordError.classList.remove('hidden');
//         isValid = false;
//     }
    
//     if (isValid) {
//         const logindata = new FormData(document.getElementById('loginForm')) ; 
//         loginrequest(logindata) ; 

//     }
// });