<?php

    include "../CLASS/utulusateur.class.php" ;
    include "../CLASS/coash.class.php" ;
    
    $connect = new Connect_class() ->getConnecting() ; 

    $selectsport = $connect -> query("SELECT * FROM sports") ;
    $reponse = $selectsport -> fetchAll() ;
     
    if($_SERVER["REQUEST_METHOD"] === 'POST'){ 
        
        if ($_POST["password"] !== $_POST["confirmPassword"]) {
            die("Password does not match the confirmation password.");
        }
        
        $utulusateur = new Utulusateur_class() ;
        $selectUser =  $utulusateur -> signin($_POST["prenom"] , $_POST["nom"]  , $_POST["email"] , $_POST["password"] , $_POST["role"] ) ;  

        if($_POST["role"] == "coach") {
            $coachInsert = new Coash_class() ;
            
            $insertcoashprepare = $coachInsert -> insertcoassh($selectUser , $_POST["bio"] , $_POST["experienceYears"] , $_POST["certifications"] , $_POST["sports"] ,  $_FILES["photo"] ) ; 

            if($insertcoashprepare > 0) {
                header("Location: login.php") ; 
                exit() ; 
            }else {
                header("Location: signin.php") ;
                exit() ; 
            }
        }

        if($selectUser) {
            header("Location: login.php") ; 
            exit() ; 
        }else {
            header("Location: signin.php") ;
        }
    }
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - CoachPro</title>
    <link rel="stylesheet" href="../CSS/output.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-[#667eea] to-[#764ba2] min-h-screen flex items-center justify-center p-4 py-12">
    <div class="w-full max-w-2xl">
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-white rounded-full mb-4 shadow-lg">
                <i class="fas fa-dumbbell text-3xl text-purple-600"></i>
            </div>
            <h1 class="text-4xl font-bold text-white mb-2">CoachPro</h1>
            <p class="text-purple-200">Créez votre compte et commencez votre aventure sportive</p>
        </div>

        <div class="bg-white rounded-2xl shadow-2xl p-8">
            <form id="signupForm" method="POST" enctype="multipart/form-data">
                <div class="mb-8">
                    <label class="block text-gray-700 font-semibold mb-4 text-center text-lg">
                        <i class="fas fa-user-tag mr-2 text-purple-600"></i>Je suis un...
                    </label>

                    <div class="grid grid-cols-2 gap-4">
                        <label class="relative cursor-pointer">
                            <input type="radio" name="role" value="sportif" class="peer sr-only" checked>
                            <div class="p-6 border-2 border-gray-300 rounded-lg text-center peer-checked:border-purple-600 peer-checked:bg-purple-50 transition duration-300 hover:shadow-lg">
                                <i class="fas fa-running text-4xl text-purple-600 mb-3"></i>
                                <h3 class="font-bold text-gray-800">Sportif</h3>
                                <p class="text-sm text-gray-600 mt-2">Je cherche un coach</p>
                            </div>
                        </label>
                        <label class="relative cursor-pointer">
                            <input type="radio" name="role" value="coach" class="peer sr-only">
                            <div class="p-6 border-2 border-gray-300 rounded-lg text-center peer-checked:border-purple-600 peer-checked:bg-purple-50 transition duration-300 hover:shadow-lg">
                                <i class="fas fa-user-tie text-4xl text-purple-600 mb-3"></i>
                                <h3 class="font-bold text-gray-800">Coach</h3>
                                <p class="text-sm text-gray-600 mt-2">Je propose mes services</p>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="prenom" class="block text-gray-700 font-semibold mb-2">
                            <i class="fas fa-user mr-2 text-purple-600"></i>Prénom
                        </label>
                        <input 
                            type="text" 
                            id="prenom" 
                            name="prenom" 
                            required
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-purple-600 transition duration-300"
                            placeholder="Votre prénom"
                        >
                        <span class="text-red-500 text-sm hidden" id="prenomError">Prénom invalide</span>
                    </div>
                    <div>
                        <label for="nom" class="block text-gray-700 font-semibold mb-2">
                            <i class="fas fa-user mr-2 text-purple-600"></i>Nom
                        </label>
                        <input 
                            type="text" 
                            id="nom" 
                            name="nom" 
                            required
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-purple-600 transition duration-300"
                            placeholder="Votre nom"
                        >
                        <span class="text-red-500 text-sm hidden" id="nomError">Nom invalide</span>
                    </div>
                </div>

                <!-- Email Field -->
                <div class="mb-6">
                    <label for="email" class="block text-gray-700 font-semibold mb-2">
                        <i class="fas fa-envelope mr-2 text-purple-600"></i>Email
                    </label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        required
                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-purple-600 transition duration-300"
                        placeholder="votre@email.com"
                    >
                    <span class="text-red-500 text-sm hidden" id="emailError">Email invalide</span>
                </div>
                
                <!-- Coach-specific fields (hidden by default) -->
                <div id="coachFields" class="hidden">
                    <!-- Bio Field -->
                    <div class="mb-6">
                        <label for="bio" class="block text-gray-700 font-semibold mb-2">
                            <i class="fas fa-file-alt mr-2 text-purple-600"></i>Biographie
                        </label>
                        <textarea 
                            id="bio" 
                            name="bio" 
                            rows="4"
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-purple-600 transition duration-300 resize-none"
                            placeholder="Parlez-nous de votre parcours et de votre approche du coaching..."
                        ></textarea>
                        <span class="text-red-500 text-sm hidden" id="bioError">La biographie est requise pour les coachs</span>
                    </div>

                    <!-- Experience Years Field -->
                    <div class="mb-6">
                        <label for="experienceYears" class="block text-gray-700 font-semibold mb-2">
                            <i class="fas fa-calendar-check mr-2 text-purple-600"></i>Années d'expérience
                        </label>
                        <input 
                            type="number" 
                            id="experienceYears" 
                            name="experienceYears" 
                            min="0"
                            max="50"
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-purple-600 transition duration-300"
                            placeholder="Ex: 5"
                        >
                        <span class="text-red-500 text-sm hidden" id="experienceError">Les années d'expérience sont requises</span>
                    </div>

                    <div class="mb-6">
                        <label class="block text-gray-700 font-semibold mb-3">
                            <i class="fas fa-trophy text-purple-600 mr-2"></i>Disciplines Sportives
                        </label>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4" id="sportselect">
                            <?php foreach($reponse as $sport) {?>
                                <label class="flex items-center p-3 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-purple-600 transition duration-300">
                                    <input type="checkbox" name="sports[]" value="<?= $sport["sport_id"] ?>" class="w-5 h-5 text-purple-600 rounded focus:ring-purple-500" >
                                    <span class="ml-3 text-gray-700"><?= $sport["sport_name"] ?></span>
                                </label>
                            <?php } ?>
                        </div>
                        <span class="text-red-500 text-sm hidden" id="sportsError">Veuillez sélectionner au moins une discipline</span>
                    </div>

                    <!-- Photo Upload Field -->
                    <div class="mb-6">
                        <label for="photo" class="block text-gray-700 font-semibold mb-2">
                            <i class="fas fa-camera mr-2 text-purple-600"></i>Photo de profil
                        </label>
                        <div class="relative">
                            <input 
                                type="file" 
                                id="photo" 
                                name="photo" 
                                accept="image/*"
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-purple-600 transition duration-300 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100"
                            >
                        </div>
                        <p class="text-xs text-gray-500 mt-2">Formats acceptés: JPG, PNG, GIF (Max: 5MB)</p>
                        <span class="text-red-500 text-sm hidden" id="photoError">La photo de profil est requise pour les coachs</span>
                    </div>

                    <!-- Certifications Upload Field -->
                    <div class="mb-6">
                        <label for="certifications" class="block text-gray-700 font-semibold mb-2">
                            <i class="fas fa-certificate mr-2 text-purple-600"></i>Certifications
                        </label>
                        <div class="relative">
                            <input 
                                type="text"
                                id="certifications" 
                                name="certifications"
                                placeholder="Ex: Diplôme d'entraîneur, Certificat FIFA, First Aid"
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-purple-600 transition duration-300"
                            />
                        </div>
                        <span class="text-red-500 text-sm hidden" id="certificationsError">Au moins une certification est requise</span>
                    </div>
                </div>

                <!-- Password Fields -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="password" class="block text-gray-700 font-semibold mb-2">
                            <i class="fas fa-lock mr-2 text-purple-600"></i>Mot de passe
                        </label>
                        <div class="relative">
                            <input 
                                type="password" 
                                id="password" 
                                name="password" 
                                required
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-purple-600 transition duration-300"
                                placeholder="••••••••"
                            >
                            <button 
                                type="button" 
                                id="togglePassword" 
                                class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-purple-600"
                            >
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <span class="text-red-500 text-sm hidden" id="passwordError">Min. 8 caractères, 1 majuscule, 1 chiffre</span>
                    </div>
                    <div>
                        <label for="confirmPassword" class="block text-gray-700 font-semibold mb-2">
                            <i class="fas fa-lock mr-2 text-purple-600"></i>Confirmer
                        </label>
                        <div class="relative">
                            <input 
                                type="password" 
                                id="confirmPassword" 
                                name="confirmPassword" 
                                required
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-purple-600 transition duration-300"
                                placeholder="••••••••"
                            >
                            <button 
                                type="button" 
                                id="toggleConfirmPassword" 
                                class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-purple-600"
                            >
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <span class="text-red-500 text-sm hidden" id="confirmPasswordError">Les mots de passe ne correspondent pas</span>
                    </div>
                </div>

                <!-- Terms and Conditions -->
                <div class="mb-6">
                    <label class="flex items-start">
                        <input type="checkbox" name="terms" id="terms" required class="w-5 h-5 text-purple-600 border-gray-300 rounded focus:ring-purple-500 mt-1">
                        <span class="ml-3 text-sm text-gray-600">
                            J'accepte les <a href="terms.php" class="text-purple-600 hover:text-purple-800 font-medium">conditions d'utilisation</a> 
                            et la <a href="privacy.php" class="text-purple-600 hover:text-purple-800 font-medium">politique de confidentialité</a>
                        </span>
                    </label>
                    <span class="text-red-500 text-sm hidden" id="termsError">Vous devez accepter les conditions</span>
                </div>

                <!-- Submit Button -->
                <button 
                    type="submit" 
                    class="w-full bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-bold py-3 rounded-lg hover:from-purple-700 hover:to-indigo-700 transform hover:scale-105 transition duration-300 shadow-lg"
                >
                    <i class="fas fa-user-plus mr-2"></i>Créer mon compte
                </button>
            </form>

            <!-- Divider -->
            <div class="flex items-center my-6">
                <div class="flex-1 border-t border-gray-300"></div>
                <span class="px-4 text-gray-500 text-sm">ou</span>
                <div class="flex-1 border-t border-gray-300"></div>
            </div>

            <!-- Login Link -->
            <div class="text-center">
                <p class="text-gray-600">
                    Vous avez déjà un compte?
                    <a href="login.html" class="text-purple-600 hover:text-purple-800 font-bold">
                        Connectez-vous
                    </a>
                </p>
            </div>
        </div>

        <div class="text-center mt-6 text-white text-sm">
            <p>&copy; 2025 CoachPro. Tous droits réservés.</p>
        </div>
    </div>

    <script src="../JS/signin.js"></script>
</body>
</html>