
import verifyevrypage from './requestvalidsesion.js';
import logout from './logout.js';
window.logout = logout;

verifyevrypage() ; 

function fetallcoash() {
    fetch("../../BACK/API/selectallcoaches.php")
        .then(rep => rep.json())
        .then(coaches => {
            loadCoaches(coaches) ; 
            loadStatscoach(coaches) ; 
        })
        .catch(error => console.error(error))

}
fetallcoash() ; 

// function getallbooking() {
//     fetch("../../BACK/API/allboking.php")
//         .then(rep => rep.json())
//         .then(data => { 
//             loadBookings(data) ; 
//             loadStats(data) ; 
//         })
//         .catch(error => console.error(error))
// }

fetch("../../BACK/API/getallsportdisp.php")
    .then(res => res.json())
    .then(allSports => {
        const sportifcheck = document.querySelector("#sportFilter");
        sportifcheck.innerHTML = `
            <option value="">Tous les sports</option>
            ${allSports.map(ele => {
                return `
                    <option value="${ele.sport_id}">${ele.sport_name}</option>
                `;
            }).join('')}
        `
    });
 

function loadStats(bookings) {
    document.getElementById('totalBookings').textContent  = bookings.length;
    document.getElementById('pendingBookings').textContent  = bookings.filter(b => b.status === 'pending').length;    
    document.getElementById('acceptedBookings').textContent  = bookings.filter(b => b.status === 'accepted').length;
}
function loadStatscoach(coach) {
    document.getElementById('availableCoaches').textContent = coach.length;
}


// Load coaches
function loadCoaches(filteredCoaches) {
    coachsList.innerHTML = filteredCoaches.map(coach => `
        <div class="border-2 border-gray-200 rounded-lg p-4 hover:border-purple-600 transition duration-300">
            <div class="flex items-start space-x-4">
                <img src="${coach.photo}" alt="${coach.first_name} ${coach.last_name}" class="w-20 h-20 rounded-full object-cover">
                <div class="flex-1">
                    <h3 class="text-lg font-bold text-gray-800">${coach.first_name} ${coach.last_name}</h3>
                    <p class="text-sm text-gray-600">
                        <i class="fas fa-trophy text-purple-600 mr-1"></i>
                        ${coach.sports.map(s => s.sport_name).join(', ')}
                    </p>
                    <p class="text-sm text-gray-600"><i class="fas fa-certificate text-purple-600 mr-1"></i>${coach.certification}</p>
                    <div class="flex items-center mt-2">
                        <span class="text-yellow-500 mr-1"><i class="fas fa-star"></i></span>
                        <span class="text-sm font-semibold">${coach.rating || 'N/A'}</span>
                        <span class="text-sm text-gray-500 ml-2">${coach.experience_year} ans d'exp.</span>
                    </div>
                </div>
                <button onclick="openBookingModal(${coach.coach_id}, '${coach.first_name} ${coach.last_name}')" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg text-sm transition duration-300">
                    <i class="fas fa-calendar-plus mr-1"></i>Réserver
                </button>
            </div>
        </div>
    `).join('');
}

// Lolt ad bookings
// function loadBookings(data) {
//     const bookingsList = document.getElementById('bookingsList');
    
//     if (data.length === 0) {
//         bookingsList.innerHTML = '<p class="text-gray-500 text-center py-8">Aucune réservation</p>';
//         return;
//     }
//     const statusColors = {
//         pending: 'bg-yellow-100 text-yellow-800',
//         accepted: 'bg-green-100 text-green-800',
//         rejected: 'bg-red-100 text-red-800',
//         cancelled: 'bg-gray-100 text-gray-800'
//     };

//     const statusText = {
//         pending: 'En attente',
//         accepted: 'Acceptée',
//         rejected: 'Refusée',
//         cancelled: 'Annulée'
//     };

//     console.log(data) ; 

//     bookingsList.innerHTML = data.map(booking => {

//         const coachName = `${booking.first_name} ${booking.last_name}`;
//         const date = booking.availabilites_date;
//         const time = `${booking.start_time} - ${booking.end_time}`;

//         return `
//             <div class="border-2 border-gray-200 rounded-lg p-4">
//                 <div class="flex justify-between items-start mb-2">
//                     <h4 class="font-bold text-gray-800">${coachName}</h4>
//                     <span class="px-2 py-1 rounded text-xs font-semibold 
//                         ${statusColors[booking.status] || ''}">
//                         ${statusText[booking.status] || booking.status}
//                     </span>
//                 </div>

//                 <p class="text-sm text-gray-600">
//                     <i class="fas fa-calendar mr-1"></i>${date}
//                 </p>

//                 <p class="text-sm text-gray-600">
//                     <i class="fas fa-clock mr-1"></i>${time}
//                 </p>

//                 <div class="flex space-x-2 mt-3">
//                     ${booking.status === 'accepted' ? `
//                         <button 
//                             onclick="openReviewModal(${booking.booking_id} , ${booking.user_id})"
//                             class="flex-1 bg-purple-600 hover:bg-purple-700 
//                             text-white px-3 py-1 rounded text-xs transition duration-300">
//                             <i class="fas fa-star mr-1"></i>Avis
//                         </button>
//                     ` : ''}

//                     ${(booking.status === 'pending' || booking.status === 'accepted') ? `
//                         <button 
//                             onclick="cancelBooking(${booking.booking_id})"
//                             class="flex-1 bg-red-500 hover:bg-red-600 
//                             text-white px-3 py-1 rounded text-xs transition duration-300">
//                             <i class="fas fa-times mr-1"></i>Annuler
//                         </button>
//                     ` : ''}
//                 </div>
//             </div>
//         `;
//     }).join('');
// }

// Booking Modal
function openBookingModal(coachId, coachName) {
    document.getElementById('selectedCoachId').value = coachId;
    document.getElementById('selectedCoachName').textContent = coachName;
    document.getElementById('bookingModal').classList.remove('hidden');
    
    // Set minimum date to today
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('bookingDate').setAttribute('min', today);
}
window.openBookingModal = openBookingModal ; 

function closeBookingModal() {
    document.getElementById('bookingModal').classList.add('hidden');
    document.getElementById('bookingForm').reset();
}
window.closeBookingModal = closeBookingModal ; 

document.getElementById('bookingDate').addEventListener("change" , (e) => {
    const dateselect = e.currentTarget.value ; 
    const coachId = document.getElementById('selectedCoachId').value;
    
    fetch("../PHP/dayoptionreser.php" , {
        method : "POST" , 
        headers : { "Content-Type": "application/json"} , 
        body : JSON.stringify({
            dateselect: dateselect,
            coach_id: coachId
        })
    })
        .then(rep => rep.json())
        .then(data => {
            const select = document.getElementById('availabilitySelect');
            select.innerHTML = '<option value="">Choisissez un horaire</option>';
            console.log(data)
            if (data.status === "success") {
                const availableSlots = data.data.filter(av => av.status === 'available');
                availableSlots.forEach(av => {
                    const option = document.createElement("option");
                    option.value = av.availability_id;
                    option.textContent = `${av.start_time} - ${av.end_time}`;
                    select.appendChild(option);
                });
            } else {
                const option = document.createElement("option");
                option.value = "";
                option.textContent = data.message;
                option.disabled = true;
                select.appendChild(option);
            }
        })
        .catch(error => console.error(error))
})

// Submit booking
// document.getElementById('bookingForm').addEventListener('submit', function(e) {
//     e.preventDefault();
    
//     const coachId = document.getElementById('selectedCoachId').value;
//     const coachName = document.getElementById('selectedCoachName').textContent;
//     const date = document.getElementById('bookingDate').value;
//     const availabilityId = document.getElementById('availabilitySelect').value;
//     const timeSlot = document.getElementById('availabilitySelect').options[document.getElementById('availabilitySelect').selectedIndex].value;

//     // Add booking (Replace with API call)
//     let booking = {
//         coach_id: coachId,
//         date: date,
//         time: timeSlot,
//         status: 'pending'
//     };
    
//     fetch("../../BACK/API/addreservation.php" , {
//         method : "POST", 
//         headers :{ "Content-Type": "application/json" },
//         body : JSON.stringify(booking)
//     })
//         .then(rep => rep.json())
//         .then(data => data.status == "success" ? getallbooking() : console.log(data))
//         .catch(error => console.log(error))

//     Swal.fire({
//         icon: 'success',
//         title: 'Réservation envoyée!',
//         text: 'Votre demande a été envoyée au coach.',
//         confirmButtonColor: '#7c3aed'
//     });

//     closeBookingModal();
//     // loadStats();
// });

// Review Modal
function openReviewModal(bookingId , coash_id) {
    document.getElementById('reviewBookingId').value = bookingId;
    document.getElementById('coash_id').value = coash_id;
    document.getElementById('reviewModal').classList.remove('hidden');
}
window.openReviewModal = openReviewModal ; 

function closeReviewModal() {
    document.getElementById('reviewModal').classList.add('hidden');
    document.getElementById('reviewForm').reset();
    document.querySelectorAll('#ratingStars i').forEach(star => {
        star.classList.remove('text-yellow-500');
        star.classList.add('text-gray-300');
    });
}
window.closeReviewModal = closeReviewModal ; 

// Rating stars
document.querySelectorAll('#ratingStars i').forEach(star => {
    star.addEventListener('click', function() {
        const rating = this.getAttribute('data-rating');
        //modifier input value 
        document.getElementById('ratingValue').value = rating;
        
        document.querySelectorAll('#ratingStars i').forEach(s => {
            s.classList.remove('text-yellow-500');
            s.classList.add('text-gray-300');
        });
        
        for (let i = 0; i < rating; i++) {
            document.querySelectorAll('#ratingStars i')[i].classList.remove('text-gray-300');
            document.querySelectorAll('#ratingStars i')[i].classList.add('text-yellow-500');
        }
    });
});

// Submit review
document.getElementById('reviewForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const rating = document.getElementById('ratingValue').value;
    const comment = document.getElementById('reviewComment').value;
    const reviewBookingId = document.getElementById("reviewBookingId").value
    const coash_id = document.getElementById("coash_id").value

    if (!rating) {
        Swal.fire({
            icon: 'error',
            title: 'Erreur',
            text: 'Veuillez sélectionner une note',
            confirmButtonColor: '#7c3aed'
        });
        return;
    }

    const datarating = new FormData() ; 

    datarating.append("rating" , rating) ; 
    datarating.append("comment" , comment) ; 
    datarating.append("reviewBookingId" , reviewBookingId) ; 
    datarating.append("coash_id" , coash_id) ; 


    fetch("../../BACK/API/addreviemtocoash.php" , {
        method : "POST" ,
        body : datarating 
    })
        .then(rep => rep.text())
        .then(data => {
            if(data = 'succes') {
                Swal.fire({
                    icon: 'success',
                    title: 'Merci!',
                    text: 'Votre avis a été enregistré.',
                    confirmButtonColor: '#7c3aed'
                });
                closeReviewModal();
            }

        })
        .catch(error => console.log(error))

});

// Cancel booking
// function cancelBooking(bookingId) {
//     Swal.fire({
//         title: 'Confirmer l\'annulation?',
//         text: "Cette action est irréversible",
//         icon: 'warning',
//         showCancelButton: true,
//         confirmButtonColor: '#ef4444',
//         cancelButtonColor: '#6b7280',
//         confirmButtonText: 'Oui, annuler',
//         cancelButtonText: 'Non'
//     }).then((result) => {
//         if (result.isConfirmed) {
//             fetch("../../BACK/API/canceledboking.php" , {
//                 method : "POST", 
//                 headers : { "Content-Type": "application/json"} , 
//                 body : JSON.stringify({ bookingId: bookingId })
//             })
//                 .then(rep => rep.text())
//                 .then(data => {
//                     getallbooking();
//                     Swal.fire({
//                         icon: 'success',
//                         title: 'Annulée!',
//                         text: 'La réservation a été annulée.',
//                         confirmButtonColor: '#7c3aed'
//                     });
//                 })
//                 .catch(error => console.error(error))
                
            
//         }
//     });
// }
window.cancelBooking = cancelBooking ; 

// Search and filter
document.getElementById('searchCoach').addEventListener('input', function(e) {
    const search = e.target.value.toLowerCase();

    const valuuecherch = new FormData();
    valuuecherch.append("likethis", search);
    if(search) {
        fetch("../../BACK/API/cherchbyname.php" , {
            method : "POST" , 
            body : valuuecherch
        })
            .then(rep => rep.json())
            .then(data => loadCoaches(data))
            .catch(error => console.error(error))
    }else{
        fetallcoash() ; 
    }
    
});

document.getElementById('sportFilter').addEventListener('change', function(e) {
    console.log(e.target.value)
    const sportidselect = e.target.value;

    const sportselected = new FormData();
    sportselected.append("sportselect", sportidselect);
    if(sportidselect) {
        fetch("../../BACK/API/selectbysport.php" , {
            method : "POST" , 
            body : sportselected
        })
            .then(rep => rep.json())
            .then(data => loadCoaches(data))
            .catch(error => console.error(error))
    }else{
        fetallcoash() ; 
    }
});


getallbooking();


