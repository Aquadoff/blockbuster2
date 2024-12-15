CREATE USER 'blockclinet'@'%' IDENTIFIED BY 'cryhavokandletslipthedogsofwar';
CREATE USER 'watcher'@'%' IDENTIFIED BY 'alaspooryorick!iknewhimhoratio';
CREATE USER 'uploader'@'%' IDENTIFIED BY 'akingdomforastage';
-- SET PASSWORD FOR 'root'@'localhost' = PASSWORD('frog787');
-- FLUSH PRIVILEGES;

create database movie;

GRANT ALL PRIVILEGES ON movie.* TO 'blockclinet'@'%';
GRANT ALL PRIVILEGES ON movie.* TO 'uploader'@'%';
GRANT ALL PRIVILEGES ON movie.* TO 'watcher'@'%';
use movie;

create table VIDEO (
    VidID int NOT NULL AUTO_INCREMENT,
    Title varchar(255)  NOT NULL,
    Publisher varchar(255),
    Producer varchar(255),
    Director varchar(255),
    Genre varchar(255),
    AgeRating varchar(255),
    VidURL varchar(255),
    ThumbURL varchar(255),
    UserRating float,
    NumRatings int,
    PRIMARY KEY (VidID)
);

CREATE TABLE USERS (
    UserID int NOT NULL AUTO_INCREMENT,
    UserName varchar(255) NOT NULL,
    AuthID varchar(255) NOT NULL,
    creator boolean,
    PRIMARY KEY (UserID)
);

create table COMMENT (
    ComID int NOT NULL AUTO_INCREMENT,
    VidID int,
    UserID varchar(255),
    UserName varchar(255),
    Body varchar(10000),
    PRIMARY KEY (ComID),
    FOREIGN KEY (VidID) REFERENCES VIDEO(VidID)
);

