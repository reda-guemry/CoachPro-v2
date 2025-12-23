<?php 

    include("../PHP/connectdatabass.php") ; 
     
    session_start() ; 

    $coach_id = $_SESSION["usermpgine"];

    $datareponse = $connect -> prepare ("SELECT * FROM availabilites WHERE coach_id = ?") ;
    $datareponse -> execute([$coach_id]) ; 
    $availabilities = $datareponse -> fetchAll() ;

    $statusColors = [
        'available' => 'bg-green-100 text-green-800',
        'booked' => 'bg-blue-100 text-blue-800',
        'cancelled' => 'bg-red-100 text-red-800'
    ];

    $stmt = $connect->prepare("SELECT  b.booking_id, b.status, u.first_name, u.last_name, a.availabilites_date, a.start_time, a.end_time
        FROM bookings b
        INNER JOIN users u ON u.user_id = b.sportif_id
        INNER JOIN availabilites a ON a.availability_id = b.availability_id
        WHERE b.coach_id = ?
        AND b.status = 'pending'
    ");
    $stmt->execute([$coach_id]);

    $pendingBookings = $stmt->fetchAll();

    if($_SERVER["REQUEST_METHOD"] === 'POST'){
        if(isset($_POST["date"])) {
            $date = $_POST['date'];
            $start_time = $_POST['start'];
            $end_time = $_POST['end'];
            $status = "available";
            if($start_time < $end_time){
                $insetinavailibity = $connect->prepare("INSERT INTO availabilites (coach_id, availabilites_date, start_time, end_time, status) VALUES (?, ?, ?, ?, ?)");
        
                $insetinavailibity->execute([$coach_id, $date, $start_time, $end_time, $status]);
        
                $idinitial = $connect -> lastInsertId() ; 
                $datareponse = $connect -> query ("SELECT * FROM availabilites WHERE availability_id ='$idinitial'") ;   
            }
            header("Location: dashbordcoach.php");
            exit;
        }else if (isset($_POST["action"]) && $_POST["action"] === "accept") {
            echo $_POST["action"] ; 
            $bookingId = $_POST['booking_id'] ;

            $delete = $connect->prepare("UPDATE bookings SET status = 'accepted' WHERE booking_id  = ?");
            
            $delete->execute([$bookingId]);
            header("Location: dashbordcoach.php");
            exit;
        }else if (isset($_POST["action"]) && $_POST["action"] === "reject"){
            echo $_POST["action"] ; 

            $bookingId = $_POST['booking_id'] ;

            $stmt = $connect->prepare("SELECT availability_id FROM bookings WHERE booking_id = ?");
            $stmt->execute([$bookingId]);
            $booking = $stmt->fetch();

            $availabilityId = $booking['availability_id'];

            $update = $connect->prepare(query: "UPDATE availabilites SET status = 'available' WHERE availability_id = ?");
            $update->execute([$availabilityId]);

            $delete = $connect->prepare("UPDATE bookings SET status = 'cancelled' WHERE booking_id  = ?");
            $delete->execute([$bookingId]);
            header("Location: dashbordcoach.php");
            exit;
        }
    }
    
?>




<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Coach - CoachPro</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
    </style>
</head>
<body class="min-h-screen">
    
    
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <i class="fas fa-dumbbell text-3xl text-purple-600 mr-3"></i>
                    <span class="text-2xl font-bold text-gray-800">CoachPro</span>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="coashprofile.html" class="text-gray-700 hover:text-purple-600 font-medium transition duration-300">
                        <i class="fas fa-user-circle mr-1"></i>Mon Profil
                    </a>
                    <span class="text-gray-700 font-medium">Bonjour, <span id="coachName">Coach</span></span>
                    <button onclick="logout()" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition duration-300">
                        <i class="fas fa-sign-out-alt mr-2"></i>Déconnexion
                    </button>
                </div>
            </div>
        </div>
    </nav>


    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-semibold">En Attente</p>
                        <p class="text-3xl font-bold text-yellow-600" id="pendingRequests">0</p>
                    </div>
                    <div class="bg-yellow-100 rounded-full p-3">
                        <i class="fas fa-clock text-2xl text-yellow-600"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-semibold">Aujourd'hui</p>
                        <p class="text-3xl font-bold text-green-600" id="todaySessions">0</p>
                    </div>
                    <div class="bg-green-100 rounded-full p-3">
                        <i class="fas fa-calendar-day text-2xl text-green-600"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-semibold">Demain</p>
                        <p class="text-3xl font-bold text-blue-600" id="tomorrowSessions">0</p>
                    </div>
                    <div class="bg-blue-100 rounded-full p-3">
                        <i class="fas fa-calendar-plus text-2xl text-blue-600"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-semibold">Note Moyenne</p>
                        <p class="text-3xl font-bold text-purple-600" id="averageRating">0.0</p>
                    </div>
                    <div class="bg-purple-100 rounded-full p-3">
                        <i class="fas fa-star text-2xl text-purple-600"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Next Session Alert -->
        <div id="nextSessionAlert" class="bg-gradient-to-r from-purple-500 to-indigo-500 text-white rounded-xl shadow-lg p-6 mb-8 hidden">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-xl font-bold mb-2">
                        <i class="fas fa-bell mr-2"></i>Prochaine Séance
                    </h3>
                    <p class="text-lg" id="nextSessionInfo">Aucune séance prévue</p>
                </div>
                <i class="fas fa-running text-5xl opacity-50"></i>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Booking Requests -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Pending Requests -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">
                        <i class="fas fa-inbox text-yellow-600 mr-2"></i>Demandes en Attente
                    </h2>
                    <div id="pendingRequestsList" class="space-y-4">
                        <?php foreach($pendingBookings as $boking) {  ?>
                            <div class="border-2 border-yellow-200 bg-yellow-50 rounded-lg p-4">
                                <div class="flex justify-between items-start mb-3">
                                    <div>
                                        <h4 class="font-bold text-gray-800 text-lg">
                                            <i class="fas fa-user text-purple-600 mr-2"></i><?= $boking['first_name'] . ' ' . $boking['last_name']; ?>
                                        </h4>
                                        <p class="text-sm text-gray-600 mt-1">
                                            <i class="fas fa-calendar mr-1"></i><?= $boking['availabilites_date']; ?>
                                        </p>
                                        <p class="text-sm text-gray-600">
                                            <i class="fas fa-clock mr-1"></i><?= $boking['start_time'] . ' - ' . $boking['end_time']; ?>
                                        </p>
                                    </div>
                                    <span class="px-3 py-1 bg-yellow-200 text-yellow-800 rounded-full text-xs font-semibold">
                                        <i class="fas fa-hourglass-half mr-1"></i>En attente
                                    </span>
                                </div>
                                <div class="flex space-x-2 h-12">
                                    <form method="post" class="flex-1 h-full">
                                        <input type="hidden" name="booking_id" value="<?= $boking['booking_id']; ?>">
                                        <button type="submit" name="action" value="accept" class="w-full h-full bg-green-500 hover:bg-green-600 text-white font-semibold rounded-lg transition duration-300 flex items-center justify-center">
                                            <i class="fas fa-check mr-1"></i>Accepter
                                        </button>
                                    </form>
                                    <form method="post" class="flex-1 h-full">
                                        <input type="hidden" name="booking_id" value="<?= $boking['booking_id']; ?>">
                                        <button type="submit" name="action" value="reject" class="w-full h-full bg-red-500 hover:bg-red-600 text-white font-semibold rounded-lg transition duration-300 flex items-center justify-center">
                                            <i class="fas fa-times mr-1"></i>Refuser
                                        </button>
                                    </form>
                                </div>
                            </div>
                        <?php  }?>
                    </div>
                </div>

                <!-- Accepted Sessions -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">
                        <i class="fas fa-check-circle text-green-600 mr-2"></i>Séances Validées
                    </h2>
                    <div id="acceptedSessionsList" class="space-y-4 max-h-96 overflow-y-auto">
                        <!-- Accepted sessions will be loaded here -->
                    </div>
                </div>
            </div>

            <!-- Availability Management -->
            <div class="space-y-6">
                <!-- Add Availability -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">
                        <i class="fas fa-calendar-plus text-purple-600 mr-2"></i>Ajouter Disponibilité
                    </h2>
                    <form id="availabilityForm" method="post">
                        <div class="mb-4">
                            <label for="availDate" class="block text-gray-700 font-semibold mb-2">Date</label>
                            <input type="date" id="availDate" name="date" required class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-purple-600">
                        </div>
                        <div class="mb-4">
                            <label for="startTime" class="block text-gray-700 font-semibold mb-2">Heure début</label>
                            <input type="time" id="startTime" name="start" required class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-purple-600">
                        </div>
                        <div class="mb-4">
                            <label for="endTime" class="block text-gray-700 font-semibold mb-2">Heure fin</label>
                            <input type="time" id="endTime" name="end" required class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-purple-600">
                        </div>
                        <button type="submit" class="w-full bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-bold py-2 rounded-lg hover:from-purple-700 hover:to-indigo-700 transition duration-300">
                            <i class="fas fa-plus mr-2"></i>Ajouter
                        </button>
                    </form>
                </div>

                <!-- My Availabilities -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">
                        <i class="fas fa-clock text-purple-600 mr-2"></i>Mes Disponibilités
                    </h2>
                    <div id="availabilitiesList" class="space-y-3 max-h-96 overflow-y-auto">
                        <div id="availabilitiesList" class="space-y-3 max-h-96 overflow-y-auto">
                            <?php if (empty($availabilities)) {  ?>
                                <p class="text-gray-500 text-center py-8">Aucune disponibilité</p>
                            <?php }else{   ?>
                                <?php foreach ($availabilities as $avail) {  ?>
                                    <div class="border-2 border-gray-200 rounded-lg p-3 mb-2">
                                        <div class="flex justify-between items-start mb-2">
                                            <div class="text-sm">
                                                <p class="font-semibold text-gray-800"><?= htmlspecialchars($avail['availabilites_date']) ?></p>
                                                <p class="text-gray-600"><?= htmlspecialchars($avail['start_time']) ?> - <?= htmlspecialchars($avail['end_time']) ?></p>
                                            </div>
                                            <span class="px-2 py-1 rounded text-xs font-semibold <?= $statusColors[$avail['status']] ?>">
                                                <?= $avail['status'] ?>
                                            </span>
                                        </div>
                                        <?php if ($avail['status'] === 'available') {  ?>
                                            <form method="POST" action="deleteAvailability.php">
                                                <input type="hidden" name="availability_id" value="<?= $avail['availability_id'] ?>">
                                                <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white text-xs py-1 rounded transition duration-300">
                                                    <i class="fas fa-trash mr-1"></i>Supprimer
                                                </button>
                                            </form>
                                        <?php }; ?>
                                    </div>
                                <?php }; ?>
                            <?php }; ?>
                        </div>
                    </div>
                </div>

                <!-- Reviews -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">
                        <i class="fas fa-star text-yellow-500 mr-2"></i>Avis Reçus
                    </h2>
                    <div id="reviewsList" class="space-y-3 max-h-64 overflow-y-auto">
                        <!-- Reviews will be loaded here -->
                    </div>
                </div>
            </div>
        </div>
    </div>

<script type="module" src="../JS/dashbordcoash.js"></script>
</body>
</html>