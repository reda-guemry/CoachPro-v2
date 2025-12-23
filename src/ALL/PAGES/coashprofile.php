<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Coach - CoachPro</title>
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
                    <a href="dashbordcoach.html" class="text-gray-700 hover:text-purple-600 font-medium transition duration-300">
                        <i class="fas fa-arrow-left mr-1"></i>Retour au Dashboard
                    </a>
                    <button onclick="logout()" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition duration-300">
                        <i class="fas fa-sign-out-alt mr-2"></i>Déconnexion
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Profile Header -->
        <div class="bg-white rounded-xl shadow-lg p-8 mb-6">
            <div class="flex items-center space-x-6">
                <div class="relative">
                    <img id="profilePhoto" src="https://via.placeholder.com/150" alt="Photo de profil" class="w-32 h-32 rounded-full object-cover border-4 border-purple-600">
                    <button onclick="document.getElementById('photoInput').click()" class="absolute bottom-0 right-0 bg-purple-600 hover:bg-purple-700 text-white rounded-full p-2 transition duration-300">
                        <i class="fas fa-camera"></i>
                    </button>
                    <input type="file" id="photoInput" accept="image/*" class="hidden" onchange="previewPhoto(event)">
                </div>
                <div class="flex-1">
                    <h1 class="text-3xl font-bold text-gray-800" id="displayName">Nom du Coach</h1>
                    <p class="text-gray-600" id="displayEmail">email@example.com</p>
                    <div class="flex items-center mt-2">
                        <span class="text-yellow-500 mr-1"><i class="fas fa-star"></i></span>
                        <span class="text-lg font-semibold" id="displayRating">4.8</span>
                        <span class="text-gray-500 ml-2">(<span id="displayReviews">24</span> avis)</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Profile Form -->
        <div class="bg-white rounded-xl shadow-lg p-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">
                <i class="fas fa-user-edit text-purple-600 mr-2"></i>Informations du Profil
            </h2>

            <form id="profileForm">
                <!-- Personal Information -->
                <div class="mb-8">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4 border-b-2 border-purple-600 pb-2">
                        <i class="fas fa-user mr-2"></i>Informations Personnelles
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="firstName" class="block text-gray-700 font-semibold mb-2">Prénom</label>
                            <input type="text" id="firstName" name="firstName" value="Ahmed" required class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-purple-600">
                        </div>
                        <div>
                            <label for="lastName" class="block text-gray-700 font-semibold mb-2">Nom</label>
                            <input type="text" id="lastName" name="lastName" value="Benali" required class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-purple-600">
                        </div>
                        <div>
                            <label for="email" class="block text-gray-700 font-semibold mb-2">Email</label>
                            <input type="email" id="email" name="email" value="ahmed.benali@example.com" required class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-purple-600">
                        </div>
                        <div>
                            <label for="phone" class="block text-gray-700 font-semibold mb-2">Téléphone</label>
                            <input type="tel" id="phone" name="phone" value="06 12 34 56 78" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-purple-600">
                        </div>
                    </div>
                </div>

                <!-- Professional Information -->
                <div class="mb-8">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4 border-b-2 border-purple-600 pb-2">
                        <i class="fas fa-briefcase mr-2"></i>Informations Professionnelles
                    </h3>
                    
                    <!-- Biography -->
                    <div class="mb-6">
                        <label for="bio" class="block text-gray-700 font-semibold mb-2">
                            <i class="fas fa-align-left text-purple-600 mr-2"></i>Biographie
                        </label>
                        <textarea id="bio" name="bio" rows="6" required class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-purple-600" placeholder="Présentez-vous, parlez de votre parcours...">Coach professionnel avec 10 ans d'expérience dans le football. Passionné par le développement des jeunes talents et l'amélioration des performances sportives.</textarea>
                        <p class="text-sm text-gray-500 mt-1">
                            <span id="bioCount">0</span>/500 caractères
                        </p>
                    </div>

                    <!-- Experience Years -->
                    <div class="mb-6">
                        <label for="experienceYears" class="block text-gray-700 font-semibold mb-2">
                            <i class="fas fa-calendar-alt text-purple-600 mr-2"></i>Années d'Expérience
                        </label>
                        <input type="number" id="experienceYears" name="experienceYears" value="10" min="0" max="50" required class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-purple-600">
                    </div>

                    <!-- Certifications -->
                    <div class="mb-6">
                        <label for="certifications" class="block text-gray-700 font-semibold mb-2">
                            <i class="fas fa-certificate text-purple-600 mr-2"></i>Certifications
                        </label>
                        <input type="text" id="certifications" name="certifications" value="UEFA B, CAF, BPJEPS" required class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-purple-600" placeholder="Ex: UEFA B, BPJEPS, etc.">
                        <p class="text-sm text-gray-500 mt-1">Séparez les certifications par des virgules</p>
                    </div>

                    <!-- Sports Specialties -->
                    <div class="mb-6">
                        <label class="block text-gray-700 font-semibold mb-3">
                            <i class="fas fa-trophy text-purple-600 mr-2"></i>Disciplines Sportives
                        </label>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4" id="sportselect">
                            
                        </div>
                        <span class="text-red-500 text-sm hidden" id="sportsError">Veuillez sélectionner au moins une discipline</span>
                    </div>
                </div>

                <!-- Change Password Section -->
                <div class="mb-8">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4 border-b-2 border-purple-600 pb-2">
                        <i class="fas fa-lock mr-2"></i>Changer le Mot de Passe
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="currentPassword" class="block text-gray-700 font-semibold mb-2">Mot de passe actuel</label>
                            <input type="password" id="currentPassword" name="currentPassword" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-purple-600" placeholder="••••••••">
                        </div>
                        <div></div>
                        <div>
                            <label for="newPassword" class="block text-gray-700 font-semibold mb-2">Nouveau mot de passe</label>
                            <input type="password" id="newPassword" name="newPassword" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-purple-600" placeholder="••••••••">
                            <span class="text-red-500 text-sm hidden" id="passwordError">Min. 8 caractères, 1 majuscule, 1 chiffre</span>
                        </div>
                        <div>
                            <label for="confirmNewPassword" class="block text-gray-700 font-semibold mb-2">Confirmer le mot de passe</label>
                            <input type="password" id="confirmNewPassword" name="confirmNewPassword" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-purple-600" placeholder="••••••••">
                            <span class="text-red-500 text-sm hidden" id="confirmPasswordError">Les mots de passe ne correspondent pas</span>
                        </div>
                    </div>
                    <p class="text-sm text-gray-500 mt-2">
                        <i class="fas fa-info-circle mr-1"></i>Laissez vide si vous ne souhaitez pas changer le mot de passe
                    </p>
                </div>

                <!-- Action Buttons -->
                <div class="flex space-x-4">
                    <button type="submit" class="flex-1 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-bold py-3 rounded-lg hover:from-purple-700 hover:to-indigo-700 transform hover:scale-105 transition duration-300">
                        <i class="fas fa-save mr-2"></i>Enregistrer les Modifications
                    </button>
                    <button type="button" onclick="resetForm()" class="flex-1 bg-gray-500 hover:bg-gray-600 text-white font-bold py-3 rounded-lg transition duration-300">
                        <i class="fas fa-undo mr-2"></i>Réinitialiser
                    </button>
                </div>
            </form>
        </div>

        <!-- Statistics Section -->
        <div class="bg-white rounded-xl shadow-lg p-8 mt-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">
                <i class="fas fa-chart-line text-purple-600 mr-2"></i>Mes Statistiques
            </h2>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
                
                <div class="text-center p-4 bg-green-50 rounded-lg">
                    <i class="fas fa-calendar-check text-3xl text-green-600 mb-2"></i>
                    <p class="text-2xl font-bold text-gray-800" id="seansereserver">0</p>
                    <p class="text-sm text-gray-600">Séances</p>
                </div>
                <div class="text-center p-4 bg-yellow-50 rounded-lg">
                    <i class="fas fa-star text-3xl text-yellow-600 mb-2"></i>
                    <p class="text-2xl font-bold text-gray-800" id="ratmoyenne">0.0</p>
                    <p class="text-sm text-gray-600">Note moyenne</p>
                </div>
                <div class="text-center p-4 bg-blue-50 rounded-lg">
                    <i class="fas fa-trophy text-3xl text-blue-600 mb-2"></i>
                    <p class="text-2xl font-bold text-gray-800" id="annerdexperience">10</p>
                    <p class="text-sm text-gray-600">Ans d'exp.</p>
                </div>
            </div>
        </div>
    </div>

    <script type="module" src="../JS/coashprofile.js"></script>
</body>
</html>