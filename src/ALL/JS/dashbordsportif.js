

function loadStats(bookings) {
    document.getElementById('totalBookings').textContent  = bookings.length;
    document.getElementById('pendingBookings').textContent  = bookings.filter(b => b.status === 'pending').length;    
    document.getElementById('acceptedBookings').textContent  = bookings.filter(b => b.status === 'accepted').length;
}


function loadStatscoach(coach) {
    document.getElementById('availableCoaches').textContent = coach.length;
}
// Booking Modal
function openBookingModal(coachId, coachName) {
    document.getElementById('selectedCoachId').value = coachId;
    document.getElementById('selectedCoachName').textContent = coachName;
    document.getElementById('bookingModal').classList.remove('hidden');
    
    // Set minimum date to today
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('bookingDate').setAttribute('min', today);
}

function closeBookingModal() {
    document.getElementById('bookingModal').classList.add('hidden');
    document.getElementById('bookingForm').reset();
}
window.closeBookingModal = closeBookingModal ; 

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
