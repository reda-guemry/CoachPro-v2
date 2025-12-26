<?php

    require_once __DIR__ . "\connect.class.php" ;

    
    class Reservations_class {
        private $connect ; 
        private $bookingId ; 

        public function __construct()
        {
            $this -> connect = new Connect_class() -> getConnecting() ; 
        }

        public function addreservation($sportifId , $coach_id , $availabilityId) {
            $status = "pending" ; 
            $modifstatuavail = $this -> connect -> prepare("UPDATE availabilites SET status = 'booked' WHERE availability_id = :seanseId ") ;
            $modifstatuavail -> execute([":seanseId" =>$availabilityId]) ; 


            $insertrese = $this -> connect -> prepare("INSERT INTO bookings (sportif_id , coach_id , availability_id , status) VALUE (:sportifId , :coachID , :seanseID , :status);") ; 
            $insertrese -> execute(params: [
                ":sportifId" => $sportifId  ,
                ":coachID" => $coach_id , 
                ":seanseID" => $availabilityId ,
                ":status" =>$status]) ; 
        }

        public function getallreservation($coahsID)  {
            $sql = "SELECT  b.booking_id, b.status, u.first_name, u.last_name, a.availabilites_date, a.start_time, a.end_time
                    FROM bookings b
                    INNER JOIN users u ON u.user_id = b.sportif_id
                    INNER JOIN availabilites a ON a.availability_id = b.availability_id
                    WHERE b.coach_id = :coashId
                    AND b.status = 'pending'" ; 

            $stmt = $this -> connect->prepare($sql);

            $stmt->execute([":coashId" => $coahsID]);

            return $stmt -> fetchAll() ; 
        }

        public function acceptreservation($bookingId) {
            $accept = $this -> connect->prepare("UPDATE bookings SET status = 'accepted' WHERE booking_id  = :bookingId");
            
            $accept -> execute([":bookingId" => $bookingId]) ; 
        }
        public function refuseresrvation($bookingId) {
            $this -> bookingId = $bookingId ;

            $stmt = $this -> connect->prepare("SELECT availability_id FROM bookings WHERE booking_id = :bokingId");
            $stmt->execute([":bokingId" => $this -> bookingId]);

            $booking = $stmt->fetch();

            $availabilityId = $booking['availability_id'];

            $update = $this -> connect->prepare(query: "UPDATE availabilites SET status = 'available' WHERE availability_id = :availableId");
            $update->execute([":availableId" => $availabilityId]);

            $delete = $this -> connect->prepare("UPDATE bookings SET status = 'cancelled' WHERE booking_id  = :bokingId");
            $delete->execute(params: [":bokingId" => $this -> bookingId]);
        }

        public function cancelreservation($bookingId) {
            $this -> bookingId = $bookingId ;

            $stmt = $this -> connect->prepare("SELECT availability_id FROM bookings WHERE booking_id = :bokingId");
            $stmt->execute([":bokingId" => $this -> bookingId]);

            $booking = $stmt->fetch();

            $availabilityId = $booking['availability_id'];

            $update = $this -> connect->prepare(query: "UPDATE availabilites SET status = 'available' WHERE availability_id = :availableId");
            $update->execute([":availableId" => $availabilityId]);

            $delete = $this -> connect->prepare("DELETE FROM bookings WHERE booking_id  = :bokingId");
            $delete->execute(params: [":bokingId" => $this -> bookingId]);
        }

        public function addreview($coash_id , $reviewBookingId , $rating , $comment) {
            $sql = "INSERT INTO reviews 
                    (booking_id , commentaire , ratting ,coash_id) 
                    VALUE ( :booking_id , :commentaire , :ratting , :coash_id )" ; 

            $insertintoreview = $this -> connect -> prepare($sql) ; 
            $insertintoreview -> execute([
                ":booking_id" => $reviewBookingId ,
                ":commentaire" => $comment ,
                ":ratting" => $rating ,
                ":coash_id" => $coash_id 
                ]) ; 
        }
    }
