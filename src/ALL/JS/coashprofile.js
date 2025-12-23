// Character counter for bio

import verifyevrypage from './requestvalidsesion.js';

verifyevrypage()

function getcoashdata() { 
    fetch("../../BACK/API/getalldataofcoash.php")
        .then(res => res.json())
        .then(data => {
            document.getElementById('firstName').value = data.first_name ;
            document.getElementById('lastName').value = data.last_name ;
            document.getElementById('email').value = data.email ;
            document.getElementById('bio').value = data.bio ;
            document.getElementById('experienceYears').value = data.experience_year ;
            document.getElementById('certifications').value = data.certification ;
            document.getElementById('annerdexperience').textContent = data.experience_year ;

            if (data.photo) {
                document.getElementById('profilePhoto').src = data.photo;
            }

            updateBioCount();
            document.getElementById('displayName').textContent = `${data.first_name} ${data.last_name}`;
            document.getElementById('displayEmail').textContent = data.email;


            const userSports = data.sports.map(s => s.sport_id);

            fetch("../../BACK/API/getallsportdisp.php")
                .then(res => res.json())
                .then(allSports => {
                    const sportifcheck = document.querySelector("#sportselect");
                    sportifcheck.innerHTML = allSports.map(ele => {
                        const checked = userSports.includes(ele.sport_id) ? 'checked' : '';
                        return `
                            <label class="flex items-center p-3 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-purple-600 transition duration-300">
                                <input type="checkbox" name="sports" value="${ele.sport_id}" class="w-5 h-5 text-purple-600 rounded focus:ring-purple-500" ${checked}>
                                <span class="ml-3 text-gray-700">${ele.sport_name}</span>
                            </label>
                        `;
                    }).join('');
                });
        })
        .catch(error => console.log(error))
}
getcoashdata()

fetch("../../BACK/API/statiquerpofile.php") 
    .then(rep => rep.json())
    .then(data => {
        document.getElementById("seansereserver").textContent = data.allboking ? data.allboking : 0 ; 
        document.getElementById("ratmoyenne").textContent = data.allboking ? parseFloat(data.getavrage).toFixed(1) : 0.0 ; 
    })
    .catch(error => console.error(error))


const bioTextarea = document.getElementById('bio');
const bioCount = document.getElementById('bioCount');

function updateBioCount() {
    const count = bioTextarea.value.length;
    bioCount.textContent = count;
    if (count > 500) {
        bioCount.classList.add('text-red-500');
        bioTextarea.value = bioTextarea.value.substring(0, 500);
    } else {
        bioCount.classList.remove('text-red-500');
    }
}

bioTextarea.addEventListener('input', updateBioCount);
updateBioCount();

// Photo preview
function previewPhoto(event) {
    const file = event.target.files[0];
    if (file) {
        // Validate file type
        if (!file.type.startsWith('image/')) {
            Swal.fire({
                icon: 'error',
                title: 'Erreur',
                text: 'Veuillez sélectionner une image valide',
                confirmButtonColor: '#7c3aed'
            });
            return;
        }

        // Validate file size (max 5MB)
        if (file.size > 5 * 1024 * 1024) {
            Swal.fire({
                icon: 'error',
                title: 'Erreur',
                text: 'L\'image ne doit pas dépasser 5MB',
                confirmButtonColor: '#7c3aed'
            });
            return;
        }

        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('profilePhoto').src = e.target.result;
            
            Swal.fire({
                icon: 'success',
                title: 'Photo mise à jour!',
                text: 'N\'oubliez pas d\'enregistrer vos modifications',
                confirmButtonColor: '#7c3aed',
                timer: 2000
            });
        };
        reader.readAsDataURL(file);
    }
}
window.previewPhoto = previewPhoto ; 

// Form validation and submission
document.getElementById('profileForm').addEventListener('submit', function(e) {
    e.preventDefault();

    // Get form values
    const firstName = document.getElementById('firstName').value.trim();
    const lastName = document.getElementById('lastName').value.trim();
    const email = document.getElementById('email').value.trim();
    const bio = document.getElementById('bio').value.trim();
    const experienceYears = document.getElementById('experienceYears').value;
    const certifications = document.getElementById('certifications').value.trim();
    const currentPassword = document.getElementById('currentPassword').value;
    const newPassword = document.getElementById('newPassword').value;
    const confirmNewPassword = document.getElementById('confirmNewPassword').value;
    const photoInput = document.getElementById("photoInput");

    // Get selected sports
    const selectedSports = Array.from(document.querySelectorAll('input[name="sports"]:checked')).map(cb => cb.value);

    // Regex patterns
    const nameRegex = /^[a-zA-ZÀ-ÿ\s'-]{2,50}$/;
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/;

    let isValid = true;

    // Reset errors
    document.querySelectorAll('.text-red-500').forEach(error => error.classList.add('hidden'));

    // Validate names
    if (!nameRegex.test(firstName) || !nameRegex.test(lastName)) {
        Swal.fire({
            icon: 'error',
            title: 'Erreur',
            text: 'Nom ou prénom invalide',
            confirmButtonColor: '#7c3aed'
        });
        return;
    }

    // Validate email
    if (!emailRegex.test(email)) {
        Swal.fire({
            icon: 'error',
            title: 'Erreur',
            text: 'Email invalide',
            confirmButtonColor: '#7c3aed'
        });
        return;
    }
    // Validate sports selection
    if (selectedSports.length === 0) {
        document.getElementById('sportsError').classList.remove('hidden');
        Swal.fire({
            icon: 'error',
            title: 'Erreur',
            text: 'Veuillez sélectionner au moins une discipline sportive',
            confirmButtonColor: '#7c3aed'
        });
        return;
    }

    // Validate password if changing
    if (newPassword || confirmNewPassword) {
        if (!currentPassword) {
            Swal.fire({
                icon: 'error',
                title: 'Erreur',
                text: 'Veuillez entrer votre mot de passe actuel',
                confirmButtonColor: '#7c3aed'
            });
            return;
        }

        if (!passwordRegex.test(newPassword)) {
            document.getElementById('passwordError').classList.remove('hidden');
            return;
        }

        if (newPassword !== confirmNewPassword) {
            document.getElementById('confirmPasswordError').classList.remove('hidden');
            return;
        }
    }

    const profileFormData = new FormData();

    profileFormData.append("firstName", firstName);
    profileFormData.append("lastName", lastName);
    profileFormData.append("email", email);
    profileFormData.append("bio", bio);
    profileFormData.append("experienceYears", experienceYears);
    profileFormData.append("certifications", certifications);

    selectedSports.forEach(sportId => {
        profileFormData.append("sports[]", sportId); 
    });

    if (newPassword) profileFormData.append("newPassword", newPassword);
    if (currentPassword) profileFormData.append("currentPassword", currentPassword);

    if (photoInput.files[0]) {
        profileFormData.append("photo", photoInput.files[0]);
    }

    
    fetch("../../BACK/API/updateprofile.php", {
        method: "POST",
        body: profileFormData
    })
        .then(res => res.json())
        .then(response => {
            console.log(response)
            if (response.success) {
                getcoashdata() 
                Swal.fire({
                    icon: 'success',
                    title: 'Profil mis à jour!',
                    text: response.message || 'Vos modifications ont été enregistrées avec succès',
                    confirmButtonColor: '#7c3aed'
                    
                }).then(() => {
                    document.getElementById('currentPassword').value = '';
                    document.getElementById('newPassword').value = '';
                    document.getElementById('confirmNewPassword').value = '';
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur',
                    text: response.message || 'Erreur lors de la mise à jour du profil',
                    confirmButtonColor: '#7c3aed'
                });
            }
        })
        .catch(error => console.error(error));

});

// Reset form
function resetForm() {
    Swal.fire({
        title: 'Réinitialiser le formulaire?',
        text: "Toutes les modifications non enregistrées seront perdues",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#7c3aed',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Oui, réinitialiser',
        cancelButtonText: 'Annuler'
    }).then((result) => {
        if (result.isConfirmed) {
            getcoashdata()
            updateBioCount();
            Swal.fire({
                icon: 'success',
                title: 'Réinitialisé!',
                text: 'Le formulaire a été réinitialisé',
                confirmButtonColor: '#7c3aed',
                timer: 2000
            });
        }
    });
}
window.resetForm = resetForm ;

// Logout
function logout() {
    Swal.fire({
        title: 'Déconnexion',
        text: "Voulez-vous vous déconnecter?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#7c3aed',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Oui',
        cancelButtonText: 'Non'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch("../../BACK/API/logout.php")
                .then(rep => rep.text())
                .then(reponse => reponse == "success" ? verifyevrypage() : console.log(reponse))
                .catch(error => console.error(error))
        }
    });
}
window.logout = logout ;  

// Initialize display
document.getElementById('displayName').textContent = `${document.getElementById('firstName').value} ${document.getElementById('lastName').value}`;
document.getElementById('displayEmail').textContent = document.getElementById('email').value;