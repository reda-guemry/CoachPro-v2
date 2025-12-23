<?php 

    include("../PHP/connectdatabass.php") ; 
     
    session_start() ; 

    $statusColors = [ 
        "pending" => 'bg-yellow-100 text-yellow-800',
        "accepted" => 'bg-green-100 text-green-800',
        "rejected" =>'bg-red-100 text-red-800',
        "cancelled" => 'bg-gray-100 text-gray-800'
    ];

    $check = $connect -> query ("SELECT u.* , c.* from users u  inner join coach_profile c on u.user_id = c.coach_id"); 
    $result = $check -> fetchAll() ; 

    $allcoach = [] ;

    foreach($result as $evrycoash ){
        $idcoash = $evrycoash["coach_id"] ; 
        $check = $connect -> query ("SELECT s.sport_name 
                                            FROM coach_profile c
                                            INNER JOIN coach_sport cs ON cs.coach_id = c.coach_id 
                                            INNER JOIN sports s ON cs.sport_id = s.sport_id 
                                            WHERE c.coach_id = $idcoash"); 
        $sports = $check -> fetchAll() ;
        $evrycoash["sports"] = $sports ; 
        $allcoach[] = $evrycoash ;
    }

    $sportif_id = $_SESSION["usermpgine"] ;


    $selectallbock = $connect -> prepare ("SELECT u.user_id , u.first_name , u.last_name , b.booking_id , b.status , a.availabilites_date , a.start_time , a.end_time
                                                FROM users u 
                                                INNER JOIN bookings b ON u.user_id = b.coach_id 
                                                INNER JOIN availabilites a ON a.availability_id = b.availability_id
                                                WHERE b.sportif_id = ?") ;

    $selectallbock -> execute(["$sportif_id"]) ;
    $result = $selectallbock -> fetchAll() ; 

    if($_SERVER["REQUEST_METHOD"] === 'POST'){
        if(isset($_POST['date'])) {
            $userID = $_SESSION["usermpgine"] ;
            $coachId = $_POST['coach_id'] ;
            $date = $_POST['date'] ;
            $time = $_POST['time'] ;
            $status = 'pending' ;

            $modifstatuavail = $connect -> prepare("UPDATE availabilites SET status = 'booked' WHERE availability_id = ? ") ;
            $modifstatuavail -> execute([$time]) ; 

            $insertrese = $connect -> prepare("INSERT INTO bookings (sportif_id , coach_id , availability_id , status) VALUE (? , ? , ? , ?);") ; 
            $insertrese -> execute([$userID , $coachId , $time , $status]) ; 

            
            $idinitial = $connect -> lastInsertId() ; 
            $datareponse = $connect -> query ("SELECT * FROM bookings WHERE booking_id ='$idinitial'") ;
            header("Location: dashbordsportif.php");
            exit;
        }else if(isset($_POST['bookingId'])) {

            $bookingId = $_POST['bookingId'] ;
            $stmt = $connect->prepare("SELECT availability_id FROM bookings WHERE booking_id = ?");
            $stmt->execute([$bookingId]);
            $booking = $stmt->fetch();

            $availabilityId = $booking['availability_id'];

            $update = $connect->prepare("UPDATE availabilites SET status = 'available' WHERE availability_id = ?");
            $update->execute([$availabilityId]);

            $delete = $connect->prepare("DELETE FROM bookings WHERE booking_id = ?");
            $delete->execute([$bookingId]);
            header("Location: dashbordsportif.php");
            exit;
        }
    }


    
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Sportif - CoachPro</title>
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
    <!-- Navbar -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <i class="fas fa-dumbbell text-3xl text-purple-600 mr-3"></i>
                    <span class="text-2xl font-bold text-gray-800">CoachPro</span>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-gray-700 font-medium">Bonjour, <span id="userName">Sportif</span></span>
                    <button onclick="logout()" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition duration-300">
                        <i class="fas fa-sign-out-alt mr-2"></i>Déconnexion
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-semibold">Réservations</p>
                        <p class="text-3xl font-bold text-purple-600" id="totalBookings">0</p>
                    </div>
                    <div class="bg-purple-100 rounded-full p-3">
                        <i class="fas fa-calendar-check text-2xl text-purple-600"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-semibold">En Attente</p>
                        <p class="text-3xl font-bold text-yellow-600" id="pendingBookings">0</p>
                    </div>
                    <div class="bg-yellow-100 rounded-full p-3">
                        <i class="fas fa-clock text-2xl text-yellow-600"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-semibold">Acceptées</p>
                        <p class="text-3xl font-bold text-green-600" id="acceptedBookings">0</p>
                    </div>
                    <div class="bg-green-100 rounded-full p-3">
                        <i class="fas fa-check-circle text-2xl text-green-600"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-semibold">Coachs Disponibles</p>
                        <p class="text-3xl font-bold text-blue-600" id="availableCoaches">0</p>
                    </div>
                    <div class="bg-blue-100 rounded-full p-3">
                        <i class="fas fa-users text-2xl text-blue-600"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Coachs List -->
            <div class="lg:col-span-2 bg-white rounded-xl shadow-lg p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">
                        <i class="fas fa-user-tie text-purple-600 mr-2"></i>Nos Coachs
                    </h2>
                    <div class="relative">
                        <input type="text" id="searchCoach" placeholder="Rechercher..." class="pl-10 pr-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-purple-600">
                        <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                    </div>
                </div>

                <!-- Filter by Sport -->
                <div class="mb-4">
                    <select id="sportFilter" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-purple-600">
                        <option value="">Tous les sports</option>
                    </select>
                </div>

                <div id="coachsList" class="space-y-4 max-h-96 overflow-y-auto">
                    <?php foreach($allcoach as $coach) {?>
                        <div class="border-2 border-gray-200 rounded-lg p-4 hover:border-purple-600 transition duration-300">
                            <div class="flex items-start space-x-4">
                                <img src="<?= $coach['photo'] ?>"  alt="<?= $coach['first_name'] . $coach['last_name'] ?>" class="w-20 h-20 rounded-full object-cover">
                                <div class="flex-1">
                                    <h3 class="text-lg font-bold text-gray-800"><?= $coach['first_name'] . $coach['last_name'] ?></h3>
                                    <p class="text-sm text-gray-600">
                                        <i class="fas fa-trophy text-purple-600 mr-1"></i>
                                        <?php foreach($coach["sports"] as $sport) {
                                            echo $sport["sport_name"] ; 
                                        } ?>
                                    </p>
                                    <p class="text-sm text-gray-600"><i class="fas fa-certificate text-purple-600 mr-1"></i><?= $coach['certification'] ?> </p>
                                    <div class="flex items-center mt-2">
                                        <span class="text-yellow-500 mr-1"><i class="fas fa-star"></i></span>
                                        <span class="text-sm font-semibold">N/A</span>
                                        <span class="text-sm text-gray-500 ml-2"><?= $coach['experience_year'] ?>  ans d'exp.</span>
                                    </div>
                                </div>
                                <button onclick="openBookingModal(<?= $coach['coach_id'] ?> , '<?= $coach['first_name'] . $coach['last_name'] ?>')" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg text-sm transition duration-300">
                                    <i class="fas fa-calendar-plus mr-1"></i>Réserver
                                </button>
                            </div>
                        </div>
                    <?php }?>
                </div>
            </div>

            <!-- My Bookings -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">
                    <i class="fas fa-calendar-alt text-purple-600 mr-2"></i>Mes Réservations
                </h2>
                <div id="bookingsList" class="space-y-4 max-h-96 overflow-y-auto">
                    <?php foreach($result as $booking) { ?>
                        <div class="border-2 border-gray-200 rounded-lg p-4">
                            <div class="flex justify-between items-start mb-2">
                                <h4 class="font-bold text-gray-800"><?= $booking["first_name"] . $booking["last_name"] ?></h4>

                                <span class="px-2 py-1 rounded text-xs font-semibold <?= $statusColors[$status] ?? '' ?>">
                                    <?= htmlspecialchars($booking["status"]) ?>
                                </span>
                            </div>

                            <p class="text-sm text-gray-600">
                                <i class="fas fa-calendar mr-1"></i><?= $booking["availabilites_date"] ?>
                            </p>

                            <p class="text-sm text-gray-600">
                                <i class="fas fa-clock mr-1"></i><?= $booking["start_time"] . ' - ' . $booking["end_time"] ?>
                            </p>

                            <div class="flex space-x-2 mt-3">

                                <?php if ($booking["status"] === 'accepted'): ?>
                                    <button
                                        onclick="openReviewModal(<?= (int)$booking['booking_id'] ?>, <?= (int)$booking['user_id'] ?>)"
                                        class="flex-1 bg-purple-600 hover:bg-purple-700 text-white px-3 py-1 rounded text-xs transition duration-300">
                                        <i class="fas fa-star mr-1"></i>Avis
                                    </button>
                                <?php endif; ?>

                                <?php if ($booking["status"] === 'pending' || $booking["status"] === 'accepted'): ?>
                                    <form method="post" >
                                        <input type="hidden" name="bookingId" value="<?= (int)$booking['booking_id'] ?>">
                                        <button type="submit"
                                            class="flex-1 bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs transition duration-300">
                                            <i class="fas fa-times mr-1"></i>Annuler
                                        </button>
                                    </form>
                                    
                                <?php endif; ?>

                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Booking Modal -->
    <div id="bookingModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-8">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold text-gray-800">Réserver une séance</h3>
                <button onclick="closeBookingModal()" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>
            <form id="bookingForm" method="post" >
                <input type="hidden" id="selectedCoachId" name="coach_id">
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Coach</label>
                    <p id="selectedCoachName" class="text-gray-600 font-medium"></p>
                </div>
                <div class="mb-4">
                    <label for="bookingDate" class="block text-gray-700 font-semibold mb-2">
                        <i class="fas fa-calendar mr-2 text-purple-600"></i>Date
                    </label>
                    <input type="date" name="date" id="bookingDate" required class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-purple-600">
                </div>
                <div class="mb-6">
                    <label for="availabilitySelect" class="block text-gray-700 font-semibold mb-2">
                        <i class="fas fa-clock mr-2 text-purple-600"></i>Créneau disponible
                    </label>
                    <select id="availabilitySelect" name="time" required class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-purple-600">
                        <option value="">Sélectionnez d'abord une date</option>
                    </select>
                </div>
                <button type="submit" class="w-full bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-bold py-3 rounded-lg hover:from-purple-700 hover:to-indigo-700 transition duration-300">
                    <i class="fas fa-check mr-2"></i>Confirmer la réservation
                </button>
            </form>
        </div>
    </div>

    <!-- Review Modal -->
    <div id="reviewModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-8">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold text-gray-800">Laisser un avis</h3>
                <button onclick="closeReviewModal()" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>
            <form id="reviewForm">
                <input type="hidden" id="reviewBookingId">
                <input type="hidden" id="coash_id">
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">
                        <i class="fas fa-star mr-2 text-yellow-500"></i>Note
                    </label>
                    <div class="flex space-x-2" id="ratingStars">
                        <i class="fas fa-star text-3xl text-gray-300 cursor-pointer hover:text-yellow-500" data-rating="1"></i>
                        <i class="fas fa-star text-3xl text-gray-300 cursor-pointer hover:text-yellow-500" data-rating="2"></i>
                        <i class="fas fa-star text-3xl text-gray-300 cursor-pointer hover:text-yellow-500" data-rating="3"></i>
                        <i class="fas fa-star text-3xl text-gray-300 cursor-pointer hover:text-yellow-500" data-rating="4"></i>
                        <i class="fas fa-star text-3xl text-gray-300 cursor-pointer hover:text-yellow-500" data-rating="5"></i>
                    </div>
                    <input type="hidden" id="ratingValue" required>
                </div>
                <div class="mb-6">
                    <label for="reviewComment" class="block text-gray-700 font-semibold mb-2">
                        <i class="fas fa-comment mr-2 text-purple-600"></i>Commentaire
                    </label>
                    <textarea id="reviewComment" rows="4" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-purple-600" placeholder="Partagez votre expérience..."></textarea>
                </div>
                <button type="submit" class="w-full bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-bold py-3 rounded-lg hover:from-purple-700 hover:to-indigo-700 transition duration-300">
                    <i class="fas fa-paper-plane mr-2"></i>Envoyer l'avis
                </button>
            </form>
        </div>
    </div>

    <script type="module" src="../JS/dashbordsportif.js"></script>
</body>
</html>