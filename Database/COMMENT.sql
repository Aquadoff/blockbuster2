create table COMMENT (
    ComID int NOT NULL AUTO_INCREMENT,
    VidID int,
    Body varchar(10000),
    PRIMARY KEY (ComID),
    FOREIGN KEY (VidID) REFRENCES VIDEO(VidID)
);
