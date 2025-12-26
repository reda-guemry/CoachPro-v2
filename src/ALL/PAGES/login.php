<?php

    include "../CLASS/utulusateur.class.php" ;
    include "../CLASS/coash.class.php" ;

    session_start();
     
    if($_SERVER["REQUEST_METHOD"] === 'POST'){

        $errors = [];

        if (empty($errors)) {
            $autentification = new Utulusateur_class() ; 
            $result = $autentification -> logine($_POST['email'] , $_POST['password']) ; 

            switch ($result) {
                case "coach" : 
                    header("Location: dashbordcoach.php") ; 
                    break ;
                case "sportif" :
                    header("Location: dashbordsportif.php") ; 
                    break ;
                case "ereure" : 
                    header("Location: login.php") ;
                    break ; 
            }

        }else {
            foreach ($errors as $field => $message) {
                echo "<p>$message</p>";
            }
        }
    
    }
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>logine</title>
    <link rel="stylesheet" href="../CSS/output.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">

        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-white rounded-full mb-4 shadow-lg">
                <i class="fas fa-dumbbell text-3xl text-purple-600"></i>
            </div>
            <h1 class="text-4xl font-bold text-white mb-2">CoachPro</h1>
            <p class="text-purple-200">Connectez-vous à votre compte</p>
        </div>

        <div class="bg-white rounded-2xl shadow-2xl p-8">
            <form id="loginForm" method="POST">
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

                <div class="mb-6">
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
                    <span class="text-red-500 text-sm hidden" id="passwordError">Mot de passe requis</span>
                </div>

                <div class="flex items-center justify-between mb-6">
                    <label class="flex items-center">
                        <input type="checkbox" name="remember" class="w-4 h-4 text-purple-600 border-gray-300 rounded focus:ring-purple-500">
                        <span class="ml-2 text-sm text-gray-600">Se souvenir de moi</span>
                    </label>
                    <a href="#" class="text-sm text-purple-600 hover:text-purple-800 font-medium">
                        Mot de passe oublié?
                    </a>
                </div>

                <button 
                    type="submit" 
                    class="w-full bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-bold py-3 rounded-lg hover:from-purple-700 hover:to-indigo-700 transform hover:scale-105 transition duration-300 shadow-lg"
                >
                    <i class="fas fa-sign-in-alt mr-2"></i>Se connecter
                </button>
            </form>

            <div class="flex items-center my-6">
                <div class="flex-1 border-t border-gray-300"></div>
                <span class="px-4 text-gray-500 text-sm">ou</span>
                <div class="flex-1 border-t border-gray-300"></div>
            </div>

            <div class="text-center">
                <p class="text-gray-600">
                    Vous n'avez pas de compte?
                    <a href="signin.php" class="text-purple-600 hover:text-purple-800 font-bold">
                        Inscrivez-vous
                    </a>
                </p>
            </div>
        </div>

        <div class="text-center mt-6 text-white text-sm">
            <p>&copy; 2025 CoachPro. Tous droits réservés.</p>
        </div>
    </div>

    <script type="module" src="../JS/logine.js"></script>
</body>
</html>