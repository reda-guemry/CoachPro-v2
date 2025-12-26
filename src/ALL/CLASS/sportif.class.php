<?php
    require_once __DIR__ . "\connect.class.php" ; 

    class Sportif_class {
        private $connect ; 
        public function __construct()
        {
            $this -> connect = new Connect_class() ->getConnecting() ; 
        }

        public function getallcoach() {
            $allcoach = [] ;

            $check = $this -> connect -> query ("SELECT u.* , c.* from users u  inner join coach_profile c on u.user_id = c.coach_id"); 
            $result = $check -> fetchAll() ; 

            foreach($result as $evrycoash ){
                $idcoash = $evrycoash["coach_id"] ;
                $check = $this -> connect -> query ("SELECT s.sport_name 
                                                    FROM coach_profile c
                                                    INNER JOIN coach_sport cs ON cs.coach_id = c.coach_id 
                                                    INNER JOIN sports s ON cs.sport_id = s.sport_id 
                                                    WHERE c.coach_id = $idcoash"); 
                $sports = $check -> fetchAll() ;
                $evrycoash["sports"] = $sports ; 
                $allcoach[] = $evrycoash ;
            }

            return $allcoach ; 
        }

        public function getallreservetion($sportifId){

            $sql = "SELECT u.user_id , u.first_name , u.last_name , b.booking_id , b.status , a.availabilites_date , a.start_time , a.end_time
                    FROM users u 
                    INNER JOIN bookings b ON u.user_id = b.coach_id 
                    INNER JOIN availabilites a ON a.availability_id = b.availability_id
                    WHERE b.sportif_id = :sprtif_id"; 

            $selectallbock = $this -> connect -> prepare ($sql) ;

            $selectallbock -> execute([":sprtif_id" => $sportifId]) ;
            return $selectallbock -> fetchAll() ;
        } 


    }