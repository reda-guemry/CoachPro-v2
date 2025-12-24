<?php

    require_once __DIR__ . "\connect.class.php" ;

    class Seanse_class {
        private $connect ; 
        private $coach ;
        private $date ; 
        private $startDate ; 
        private $endDate; 
        private $statu;
        
        public function __construct($coash_id){
            $this -> connect = new Connect_class() -> getConnecting() ; 
            $this -> coach = $coash_id ; 
        }
        
        public function addseabse() {
            $datareponse = $this -> connect -> prepare ("SELECT * FROM availabilites WHERE coach_id = ?") ;
            $datareponse -> execute([$this -> coach]) ; 
            $availabilities = $datareponse -> fetchAll() ;
        }

    }