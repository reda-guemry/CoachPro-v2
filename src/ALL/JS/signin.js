const roleInputs = document.querySelectorAll('input[name="role"]');
const coachFields = document.getElementById('coachFields');
const coachInputs = coachFields.querySelectorAll('input, textarea');

function toggleCoachFields() {
    const selectedRole = document.querySelector('input[name="role"]:checked').value;
    console.log(selectedRole)
    
    if (selectedRole === 'coach') {
        coachFields.classList.remove('hidden');
        coachInputs.forEach(input => {
            if (input.type !== 'checkbox' && input.id !== 'certifications') { 
                input.setAttribute('required', 'required');
            }
        });
    } else {
        coachFields.classList.add('hidden');
        coachInputs.forEach(input => {
            input.removeAttribute('required');
        });
    }
}


function togglePasswordVisibility(input, icon) {
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

function signinfetch(datajson) {
    fetch("../../BACK/API/signin.php" , {
            method : "POST" , 
            body : datajson
        })
        .then(rep => rep.text())
        .then(data => {
            if(data == "success"){
                document.getElementById('signupForm').reset();
                window.location.href = "login.html" 
            }else {
                console.log(data) 
            }
        })
        .catch(eroor => console.log(eroor))
}

document.getElementById('togglePassword').addEventListener('click', function() {
    const password = document.getElementById('password');
    const icon = this.querySelector('i');
    togglePasswordVisibility(password, icon);
});

document.getElementById('toggleConfirmPassword').addEventListener('click', function() {
    const password = document.getElementById('confirmPassword');
    const icon = this.querySelector('i');
    togglePasswordVisibility(password, icon);
});



roleInputs.forEach(input => {
    input.addEventListener('change', toggleCoachFields);
});

toggleCoachFields();