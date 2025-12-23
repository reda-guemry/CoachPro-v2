import logout from './logout.js';
window.logout = logout;



function getallavai() {  
    fetch("../../BACK/API/getallavaili.php")
        .then(rep => rep.json())
        .then(data => {  
            loadAvailabilities(data.datainsert);
            loadStats(data.datainsert);  
        })
        .catch(error =>console.error(error))
}

function getallpendigres() {
    fetch("../../BACK/API/getallpendres.php")
        .then(rep => rep.json())
        .then(data =>{
            loadPendingRequests(data) ; 
            loadStatspanding(data) ; 
        })
        .catch(error =>console.error(error))
}

function getAcceptedSessions() {
    fetch("../../BACK/API/acceptedBookings.php")
        .then(res => res.json())
        .then(data => {
            loadAcceptedSessions(data)
            loadnextsesion(data)
            console.log(data)
        })
        .catch(err => console.error(err));
}
function allreviewfetch() {
    fetch("../../BACK/API/getallreviewcoash.php")
        .then(rep => rep.json())
        .then(data => {
            console.log(data) ; 
            loadReviews(data) ;
            affichmoyen(data) ;  
        })
        .catch(err => console.error(err));
}
allreviewfetch() 

// Load stats
function loadStats(data) {
    const today = new Date().toISOString().split('T')[0];
    const tomorrow = new Date(Date.now() + 86400000).toISOString().split('T')[0];
    
    document.getElementById('todaySessions').textContent = data.filter(s => s.availabilites_date === today ).length;
    document.getElementById('tomorrowSessions').textContent = data.filter(s => s.availabilites_date === tomorrow).length;
    
    
}

function affichmoyen(data) {
    const avgRating = data.length > 0 ? (data.reduce((sum, r) => sum + r.ratting, 0) / data.length).toFixed(1) : '0.0';
    document.getElementById('averageRating').textContent = avgRating;
}

function loadnextsesion(data) {
    const nextSession = data.sort((a, b) => new Date(a.date) - new Date(b.date))[0];

    if (nextSession) {
        document.getElementById('nextSessionAlert').classList.remove('hidden');
        document.getElementById('nextSessionInfo').textContent = `${nextSession.first_name} ${nextSession.last_name} - ${nextSession.availabilites_date} à ${nextSession.start_time} - ${nextSession.end_time}`;
    }
}

function loadStatspanding(data) {
    document.getElementById('pendingRequests').textContent = data.filter(b => b.status === 'pending').length;
}

// Load pending requests
function loadPendingRequests(data) {
    const list = document.getElementById('pendingRequestsList');
    
    const pending = data.filter(b => b.status === 'pending');

    if (pending.length === 0) {
        list.innerHTML = '<p class="text-gray-500 text-center py-8">Aucune demande en attente</p>';
        return;
    }

    list.innerHTML = pending.map(request => {
        const coachName = `${request.first_name} ${request.last_name}`;
        const time = `${request.start_time} - ${request.end_time}`;

        return `
        <div class="border-2 border-yellow-200 bg-yellow-50 rounded-lg p-4">
            <div class="flex justify-between items-start mb-3">
                <div>
                    <h4 class="font-bold text-gray-800 text-lg">
                        <i class="fas fa-user text-purple-600 mr-2"></i>${coachName}
                    </h4>
                    <p class="text-sm text-gray-600 mt-1">
                        <i class="fas fa-calendar mr-1"></i>${request.availabilites_date}
                    </p>
                    <p class="text-sm text-gray-600">
                        <i class="fas fa-clock mr-1"></i>${time}
                    </p>
                </div>
                <span class="px-3 py-1 bg-yellow-200 text-yellow-800 rounded-full text-xs font-semibold">
                    <i class="fas fa-hourglass-half mr-1"></i>En attente
                </span>
            </div>
            <div class="flex space-x-2">
                <button onclick="acceptBooking(${request.booking_id})" class="flex-1 bg-green-500 hover:bg-green-600 text-white font-semibold py-2 rounded-lg transition duration-300">
                    <i class="fas fa-check mr-1"></i>Accepter
                </button>
                <button onclick="rejectBooking(${request.booking_id})" class="flex-1 bg-red-500 hover:bg-red-600 text-white font-semibold py-2 rounded-lg transition duration-300">
                    <i class="fas fa-times mr-1"></i>Refuser
                </button>
            </div>
        </div>
        `;
    }).join('');
}

// Load accepted sessions
function loadAcceptedSessions(data) {
    const list = document.getElementById('acceptedSessionsList');
    
    if (data.length === 0) {
        list.innerHTML = '<p class="text-gray-500 text-center py-8">Aucune séance validée</p>';
        return;
    }

     list.innerHTML = data.map(session => {
        const sportifName = `${session.first_name} ${session.last_name}`;
        const timeRange = `${session.start_time} - ${session.end_time}`;

        return `
        <div class="border-2 border-green-200 bg-green-50 rounded-lg p-4">
            <div class="flex justify-between items-start">
                <div>
                    <h4 class="font-bold text-gray-800">
                        <i class="fas fa-user text-purple-600 mr-2"></i>${sportifName}
                    </h4>
                    <p class="text-sm text-gray-600 mt-1">
                        <i class="fas fa-calendar mr-1"></i>${session.availabilites_date}
                    </p>
                    <p class="text-sm text-gray-600">
                        <i class="fas fa-clock mr-1"></i>${timeRange}
                    </p>
                </div>
                <span class="px-3 py-1 bg-green-200 text-green-800 rounded-full text-xs font-semibold">
                    <i class="fas fa-check-circle mr-1"></i>Validée
                </span>
            </div>
        </div>
        `;
    }).join('');
}

// Load availabilities
function loadAvailabilities(data) {
    const list = document.getElementById('availabilitiesList');
    
    if (data.length === 0) {
         list.innerHTML = '<p class="text-gray-500 text-center py-8">Aucune disponibilité</p>';
         return;
    }

    const statusColors = {
        available: 'bg-green-100 text-green-800',
        booked: 'bg-blue-100 text-blue-800',
        cancelled: 'bg-red-100 text-red-800'
    };

    const statusText = {
        available: 'Disponible',
        booked: 'Réservée',
        cancelled: 'Annulée'
    };

    list.innerHTML =  data.map(avail => `
        <div class="border-2 border-gray-200 rounded-lg p-3 mb-2">
            <div class="flex justify-between items-start mb-2">
                <div class="text-sm">
                    <p class="font-semibold text-gray-800">${avail.availabilites_date}</p>
                    <p class="text-gray-600">${avail.start_time} - ${avail.end_time}</p>
                </div>
                <span class="px-2 py-1 rounded text-xs font-semibold ${statusColors[avail.status]}">${statusText[avail.status]}</span>
            </div>
            ${avail.status === 'available' ? `
                <button onclick="deleteAvailability(${avail.availability_id})" class="w-full bg-red-500 hover:bg-red-600 text-white text-xs py-1 rounded transition duration-300">
                    <i class="fas fa-trash mr-1"></i>Supprimer
                </button>
            ` : ''}
        </div>
    `).join('');

}

// Load reviews
function loadReviews(reviews) {
    const list = document.getElementById('reviewsList');
    
    if (reviews.length === 0) {
        list.innerHTML = '<p class="text-gray-500 text-center py-8">Aucun avis</p>';
        return;
    }

    list.innerHTML = reviews.map(review => `
        <div class="border-2 border-gray-200 rounded-lg p-3">
            <div class="flex justify-between items-start mb-2">
                <p class="font-semibold text-gray-800 text-sm">
                    ${review.first_name} ${review.last_name}
                </p>

                <div class="flex items-center">
                    ${Array(review.ratting).fill().map(() =>
                        '<i class="fas fa-star text-yellow-500 text-xs"></i>'
                    ).join('')}
                    ${Array(5 - review.ratting).fill().map(() =>
                        '<i class="fas fa-star text-gray-300 text-xs"></i>'
                    ).join('')}
                </div>
            </div>

            <p class="text-gray-600 text-xs mb-1">${review.commentaire}</p>
        </div>
    `).join('');
}

// Delete availability
function deleteAvailability(availId) {
    Swal.fire({
        title: 'Supprimer cette disponibilité?',
        text: 'Cette action est irréversible',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Oui, supprimer',
        cancelButtonText: 'Annuler'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch("../../BACK/API/deletavailibilter.php" , {
                method : "POST", 
                headers : { "Content-Type": "application/json"} , 
                body : JSON.stringify({ availId: availId })
            })
                .then(rep => rep.text())
                .then(data => {
                    getallpendigres();
                    getallavai() ;
                    Swal.fire({
                        icon: 'success',
                        title: 'Supprimée!',
                        text: 'La disponibilité a été supprimée',
                        confirmButtonColor: '#7c3aed'
                    });
                })
                .catch(error => console.error(error))
        }
    });
}

window.deleteAvailability = deleteAvailability;

// Set minimum date for availability form
const today = new Date().toISOString().split('T')[0];
document.getElementById('availDate').setAttribute('min', today);