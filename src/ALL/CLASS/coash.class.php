<?php

    require_once __DIR__ . "\connect.class.php" ;
    require_once __DIR__ . "\utulusateur.class.php" ;

    class Coash_class extends Utulusateur_class {
        private $connect ; 

        private $coahsId ; 

        public function __construct(){
            $this -> connect = new Connect_class() ->getConnecting() ; 
        }

        private function movephoto($file) {
            
            $nameProfilePhoto =  $file["name"] ; 
            $uploadDirectionProfile = "../IMG/PROFILESPHOTO/" . $nameProfilePhoto ;
            move_uploaded_file( $file["tmp_name"] , $uploadDirectionProfile) ;
            return "../IMG/PROFILESPHOTO/" . $nameProfilePhoto ;
        }

        public function insertcoassh($coash_id , $BioCoach , $experiencecoach , $certificate , $cpecialiter , $profilePhotoFile ) {

            $profilephtopath = $this -> movephoto($profilePhotoFile) ; 
            
            $insertcoashprepare = $this -> connect -> prepare('INSERT INTO coach_profile (coach_id , bio , experience_year , certification , photo) 
                                                                      VALUE ( :coash_id , :BioCoach , :experiencecoach , :certificate , :profilePhotoPath )') ; 
            $insertcoashprepare -> execute([
                ":coash_id" =>$coash_id ,
                ":BioCoach" => $BioCoach ,
                ":experiencecoach" => $experiencecoach ,
                ":certificate" => $certificate ,
                ":profilePhotoPath" => $profilephtopath]) ;

            foreach($cpecialiter as $speciait){
                $insertintosportcoash = $this->connect->prepare("INSERT INTO coach_sport (sport_id, coach_id) VALUES (?, ?)");
                $insertintosportcoash->execute([$speciait, $coash_id]);
            }
            
            return $insertcoashprepare -> rowCount() ; 
        }

        public function getallreviews($coahsId) {
            $this -> coahsId = $coahsId ;

            $sql = "SELECT r.* , u.first_name , u.last_name 
            FROM reviews r 
            INNER JOIN bookings b ON r.booking_id = b.booking_id
            INNER JOIN users u ON b.sportif_id = u.user_id
            WHERE r.coash_id = :coash_id" 
            ;
            $reponse = $this -> connect -> prepare($sql) ; 
            $reponse -> execute([
                ":coash_id" => $this -> coahsId
            ]) ; 
            return $reponse -> fetchAll() ; 
        }

        public function getallacceptseanse($coahsId) {
            $this -> coahsId = $coahsId ; 

            $sql = "SELECT  b.booking_id, b.status, u.first_name, u.last_name, a.availabilites_date, a.start_time, a.end_time
                    FROM bookings b
                    INNER JOIN users u ON u.user_id = b.sportif_id
                    INNER JOIN availabilites a ON a.availability_id = b.availability_id
                    WHERE b.coach_id = :coachid
                    AND b.status = 'accepted'" ;
            $reponse = $this -> connect -> prepare($sql) ; 
            $reponse -> execute([
                ":coachid" => $this -> coahsId
            ]) ; 
            return $reponse -> fetchAll() ;

        }

        public function getallcoachinfo($coahsId){
            $this -> coahsId = $coahsId ; 

            $selectcoash = $this -> connect->prepare("SELECT u.* , c.* FROM users u 
                                      INNER JOIN coach_profile c ON c.coach_id = u.user_id
                                      WHERE c.coach_id = :coashId");
            $selectcoash->execute([":coashId" => $this -> coahsId]);
            
            $datacoach = $selectcoash->fetch();

            $getsports = $this -> connect->prepare("SELECT cs.sport_id 
                                                FROM coach_profile cp
                                                INNER JOIN coach_sport c ON  c.coach_id = cp.coach_id
                                                INNER JOIN sports cs ON cs.sport_id = c.sport_id 
                                                WHERE cp.coach_id = :coashId ");
            $getsports->execute([":coashId" => $this -> coahsId]);
            $datacoach["sports"] = $getsports->fetchAll();

            return $datacoach ; 
        }

        public function getallsport() {
            $selectsport = $this -> connect -> query("SELECT * FROM sports") ;
            return $selectsport -> fetchAll() ; 
        }

        public function modifierprofilecoach(
            $coahsId , 
            $firstName , 
            $lastName , 
            $email ,  
            $bio , 
            $experienceYears , 
            $certifications , 
            $sports , 
            $file,
            $newPassword , 
            $currentPassword            
        ) {
            $this -> coahsId = $coahsId ;
            $profilePhotoPath = null;

            $insertuserprepar = $this -> connect -> prepare('UPDATE users 
                                                            SET first_name = ?, last_name = ?, email = ? 
                                                            WHERE user_id = ?') ;
            $insertuserprepar -> execute([$firstName , $lastName , $email , $this -> coahsId]) ;

            $deleteSports = $this -> connect->prepare('DELETE FROM coach_sport WHERE coach_id = ?');
            $deleteSports->execute([$this -> coahsId]);

            $insertSport = $this -> connect->prepare('INSERT INTO coach_sport (coach_id, sport_id) VALUES (?, ?)');

            foreach ($sports as $sportId) {
                $insertSport->execute([$this -> coahsId, $sportId]);
            }


            if($file && $file['tmp_name'] != ''){
                $profilePhotoPath = $this -> movephoto($file) ;
            }

            if($profilePhotoPath){
                $insertcoashprepare = $this -> connect->prepare('UPDATE coach_profile 
                                                        SET bio = ?, experience_year = ?, certification = ?, photo = ?
                                                        WHERE coach_id = ?');  
                $insertcoashprepare->execute([$bio, $experienceYears, $certifications, $profilePhotoPath, $this -> coahsId]);
            } else {
                $insertcoashprepare = $this -> connect->prepare('UPDATE coach_profile 
                                                        SET bio = ?, experience_year = ?, certification = ?
                                                        WHERE coach_id = ?');  
                $insertcoashprepare->execute([$bio, $experienceYears, $certifications, $this -> coahsId]);
            }

            if(!empty($currentPassword) && !empty($newPassword)) {

                $checkpassword = $this -> connect->prepare(query: "SELECT password FROM users WHERE user_id = ?"); 
                $checkpassword->execute([$this -> coahsId]); 
                $hashpassword = $checkpassword->fetch();

                if($hashpassword && password_verify($currentPassword, $hashpassword["password"])) {
                    
                    $newPassword = password_hash($newPassword, PASSWORD_DEFAULT); 
                    $updatepass = $this -> connect->prepare("UPDATE users SET password = ? WHERE user_id = ?");
                    $updatepass->execute([$newPassword, $this -> coahsId]);

                } 
            }
        }
        
    }