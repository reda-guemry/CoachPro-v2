<?php

    require_once __DIR__ . "\connect.class.php" ;

    class Seanse_class {
        private $connect ; 
        private $coach_id ; 
        private $date ; 
        private $startDate ; 
        private $endDate; 
        private $statu;
        private $seanseId ; 
        
        public function __construct($coash_id){
            $this -> connect = new Connect_class() -> getConnecting() ; 

            $this -> coach_id = $coash_id ;  
        }

        public function setSeanceData($date , $startDate , $endDate) {

            $this -> date = $date ; 
            $this -> startDate = $startDate ; 
            $this -> endDate = $endDate ;
            $this -> statu = 'available' ;  
        }
        
        public function getallseanse() {

            $datareponse = $this -> connect -> prepare ("SELECT * FROM availabilites WHERE coach_id = ?") ;
            $datareponse -> execute([$this -> coach_id]) ;
            
            return $datareponse -> fetchAll() ; 
        }
        
        public function saveseanse() {
            $sql = "INSERT INTO availabilites 
                    (coach_id, availabilites_date, start_time, end_time, status) 
                    VALUES (:coachid, :date_avail , :time_start , :time_end , :status)" ;

            $insetinavailibity = $this -> connect ->  prepare($sql);
            $insetinavailibity -> execute([
                ":coachid" => $this -> coach_id , 
                ":date_avail" => $this -> date , 
                ":time_start" => $this -> startDate , 
                ":time_end" => $this -> endDate , 
                ":status" => $this -> statu 
            ]);
        }

        public function supprimerseanse($seanseId) {
            $sql = "DELETE FROM availabilites 
                    WHERE availability_id = :seanseId" ; 
            $deleteseans = $this -> connect -> prepare($sql) ;
            $deleteseans -> execute([":seanseId" => $seanseId]) ; 
            
        }

    }