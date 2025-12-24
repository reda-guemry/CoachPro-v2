<?php

    class Connect_class {
        private $host = "localhost" ;
        private $dbname = "coashpro_v2" ;
        private $username = "root" ;
        private $password = "root" ;
        
        private $connect ;

        public function __construct()
        {
            try{
                $this -> connect = new PDO("mysql:host={$this -> host};dbname={$this -> dbname};charset=utf8" , $this -> username , $this -> password) ;

                $this -> connect -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this -> connect -> setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
                
            }catch(PDOException $e) {
                die("Connection failed: " . $e -> getMessage()) ;
            }
        }
        public function getConnecting() {
            return $this ->connect ;
        }
    }