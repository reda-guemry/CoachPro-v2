<?php

    require_once __DIR__ . "\connect.class.php" ;

    class Coash_class {
        private $connect ; 

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
        
    }