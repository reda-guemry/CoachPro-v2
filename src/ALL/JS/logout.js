import verifyevrypage from './requestvalidsesion.js';


export default function logout() {
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