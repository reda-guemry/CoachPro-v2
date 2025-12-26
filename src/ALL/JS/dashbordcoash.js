
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


// Set minimum date for availability form
const today = new Date().toISOString().split('T')[0];
document.getElementById('availDate').setAttribute('min', today);