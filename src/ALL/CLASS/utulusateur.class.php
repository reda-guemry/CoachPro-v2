<?php
    require_once __DIR__ . "\connect.class.php" ;
    class Utulusateur_class {
        private $connect ; 

        public function __construct()
        {
            $this -> connect = new Connect_class() -> getConnecting() ; 
        }

        public function signin($prenom , $name , $email , $password , $role ) {
            $password = password_hash($password  , PASSWORD_DEFAULT);

            $insertuserprepar = $this -> connect -> prepare('INSERT INTO users (first_name , last_name , email , password , role) VALUE ( :nom , :prenom , :email , :password , :role)') ;
            
            $insertuserprepar -> execute([
                ":prenom" => $prenom ,
                ":nom" => $name ,
                ":email" => $email , 
                ":password" => $password , 
                ":role" => $role
            ]); 

            return $this -> connect -> lastInsertId() ; 
        }

        public function logine($email , $password) {

            $selectuser = $this -> connect -> prepare('SELECT * FROM users WHERE email = :email ') ; 
            $selectuser -> execute( [":email" => $email]) ; 

            $data = $selectuser -> fetch() ; 
            
            if($data) {
                if(password_verify($password , $data["password"])){

                    session_regenerate_id(true);

                    $sesionid = session_id() ; 
                    $userId = $data["user_id"] ; 
                    $rolelogine = $data["role"] ; 

                    $insertintosesion = $this -> connect -> prepare("INSERT INTO sesionses (sesion_id , user_id , role_user) VALUE (:sesionid , :userId , :rolelogine)") ; 
                    $insertintosesion -> execute([
                        ":sesionid" => $sesionid ,
                        ":userId" => $userId ,
                        ":rolelogine" => $rolelogine]) ; 

                    $_SESSION["sesion_id"] = $sesionid ; 
                    $_SESSION["usermpgine"] = $userId ; 
                    $_SESSION["rolelogine"] = $rolelogine ; 

                    return $_SESSION["rolelogine"] ;
                }else {
                    return "ereure" ; 
                }
            }else {
                return "ereure" ; 
            }
        }
        
    }