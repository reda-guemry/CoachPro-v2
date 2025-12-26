<?php

    require_once __DIR__ . "\connect.class.php" ;

    $connect = new Connect_class() -> getConnecting() ; 

    header("Content-Type: application/json");

    $data = json_decode(file_get_contents("php://input") , true) ; 


    $datereserver = $data["dateselect"] ;
    $coachId = $data["coach_id"] ;
    

    $getallreser = $connect -> prepare("SELECT * FROM availabilites WHERE availabilites_date = ? AND coach_id = ? AND status = 'available'") ;
    $getallreser -> execute([ $datereserver , $coachId ]) ; 
    $reponsedata = $getallreser -> fetchAll() ;


    
    if (count($reponsedata) > 0) {
        echo json_encode([ "status" => "success", "data" => $reponsedata , "date" => $data] );
    } else {
        echo json_encode([ "status" => "empty", "message" => "Aucune disponibilité pour ce jour" ,"date" => $data]);
    }