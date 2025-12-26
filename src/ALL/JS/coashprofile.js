

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


// Initialize display
document.getElementById('displayName').textContent = `${document.getElementById('firstName').value} ${document.getElementById('lastName').value}`;
document.getElementById('displayEmail').textContent = document.getElementById('email').value;