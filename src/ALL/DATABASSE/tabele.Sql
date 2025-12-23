
use coachpro_v2 ; 

CREATE TABLE users ( 
    user_id INT AUTO_INCREMENT PRIMARY KEY ,
    first_name VARCHAR(50) NOT NULL ,
    last_name VARCHAR(50) NOT NULL ,
    email VARCHAR(50) NOT NULL ,
    password VARCHAR(255) NOT NULL ,
    role ENUM("sportif" , "coach") NOT NULL 
);

CREATE TABLE coach_profile (
    coach_id INT PRIMARY KEY  ,
    bio TEXT NOT NULL , 
    experience_year INT NOT NULL ,
    certification VARCHAR(255) NOT NULL,
    photo VARCHAR(255) NOT NULL ,
    FOREIGN KEY (coach_id) REFERENCES users(user_id)
                           ON DELETE CASCADE 
                           ON UPDATE CASCADE   
);

CREATE TABLE sports (
    sport_id INT AUTO_INCREMENT PRIMARY KEY ,
    sport_name VARCHAR(50) NOT NULL
);

CREATE TABLE coach_sport (
    coach_id INT ,
    sport_id INT , 
    PRIMARY KEY (coach_id , sport_id) , 
    FOREIGN KEY (coach_id) REFERENCES users(user_id)
                           ON DELETE CASCADE 
                           ON UPDATE CASCADE ,  
    FOREIGN KEY (sport_id) REFERENCES sports(sport_id)
                           ON DELETE CASCADE
                           ON UPDATE CASCADE 
);

CREATE TABLE availabilites (
    availability_id INT AUTO_INCREMENT PRIMARY key , 
    coach_id INT NOT NULL , 
    availabilites_date DATE NOT NULL ,
    start_time TIME NOT NULL , 
    end_time TIME NOT NULL ,
    status ENUM("available", "booked", "cancelled") NOT NULL DEFAULT "available" ,
    FOREIGN KEY (coach_id) REFERENCES users(user_id) 
                           ON DELETE CASCADE 
                           ON UPDATE CASCADE 
);


CREATE TABLE bookings (
    booking_id INT AUTO_INCREMENT PRIMARY KEY ,
    sportif_id INT NOT NULL ,
    coach_id INT NOT NULL ,
    availability_id INT NOT NULL ,
    status ENUM('pending', 'accepted', 'rejected', 'cancelled') NOT NULL ,
    FOREIGN KEY (sportif_id) REFERENCES users(user_id) 
                           ON DELETE CASCADE 
                           ON UPDATE CASCADE ,
    FOREIGN KEY (coach_id) REFERENCES users(user_id) 
                           ON DELETE CASCADE 
                           ON UPDATE CASCADE ,
    FOREIGN KEY (availability_id) REFERENCES availabilites(availability_id) 
                           ON DELETE CASCADE 
                           ON UPDATE CASCADE 
) ;

CREATE TABLE reviews (
    review_id INT AUTO_INCREMENT PRIMARY KEY , 
    booking_id INT NOT NULL ,
    commentaire TEXT ,
    ratting INT NOT NULL ,
    FOREIGN KEY (booking_id) REFERENCES bookings(booking_id) 
                          ON DELETE CASCADE 
                          ON UPDATE CASCADE  
);

CREATE TABLE sesionses (
    sesion_id VARCHAR(255) PRIMARY KEY ,
    user_id INT NOT NULL ,
    role_user ENUM("sportif" , "coach") NOT NULL ,
    FOREIGN KEY (user_id) REFERENCES users(user_id) 
                        ON DELETE CASCADE
                        ON UPDATE CASCADE 

) ; 

