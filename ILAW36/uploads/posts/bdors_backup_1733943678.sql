

CREATE TABLE `admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Username` varchar(50) NOT NULL,
  `Password` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO admin VALUES("1","admin1","12345");
INSERT INTO admin VALUES("2","admin2","admin2");
INSERT INTO admin VALUES("3","admin3","admin3");



CREATE TABLE `request` (
  `Username` varchar(50) NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Fullname` varchar(50) NOT NULL,
  `Birthdate` varchar(20) NOT NULL,
  `Age` int(2) NOT NULL,
  `Gender` varchar(50) NOT NULL,
  `HomeAddress` varchar(50) NOT NULL,
  `Contact` varchar(11) NOT NULL,
  `DateofRequest` varchar(11) NOT NULL,
  `Purposes` varchar(50) NOT NULL,
  `Documents` varchar(50) NOT NULL,
  `Fee` varchar(50) NOT NULL,
  `ValidID` varchar(50) NOT NULL,
  `FrontID` longblob NOT NULL,
  `BackID` longblob NOT NULL,
  `Payment` varchar(50) NOT NULL,
  `Service` varchar(50) NOT NULL,
  `Status` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO request VALUES("user1","14","Melanie Martinez","03/25/1998","22","Female","QC","09123456789","02/18/2021","Try","Barangay Certificate","100 Pesos","Driver's Licence","","","Paid","Received","Completed");
INSERT INTO request VALUES("user1","17","Melanie Martinez","03/25/1998","22","Male","QC","09123456789","02/18/2021","Try","Community Tax Return","20 Pesos","Barangay ID","","","Paid","Received","Completed");
INSERT INTO request VALUES("user1","18","Melanie Martinez","03/25/1997","23","Female","QC","09123456789","02/18/2021","Try","Certificate of Indigency","100 Pesos","Voter's ID","","","COD","For Delivery","Approved");
INSERT INTO request VALUES("user1","19","Melanie Martinez","02/15/1998","24","Male","QC","09123456789","02/18/2021","Try","Barangay ID","50 Pesos","Voter's ID","","","Denied","Denied","Denied");
INSERT INTO request VALUES("user2","20","Harry Styles","02/01/1997","25","Male","QC","09123456789","02/18/2021","Try","Community Tax Return","20 Pesos","Driver's Licence","","","Denied","Denied","Denied");
INSERT INTO request VALUES("user1","21","Jonna Deocariza","05/01/2000","20","Female","QC","09123456789","02/18/2021","Try","Certificate of Indigency","100 Pesos","School ID","","","Canceled","Canceled","Canceled");
INSERT INTO request VALUES("user1","24","Jonna Deocariza","05/01/2000","21","Female","West Fairview, Quezon City","09123456789","07/14/2021","School Requirement","Certificate of Indigency","100 Pesos","School ID","","","COD","For Delivery","Pending for Approval");



CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `FirstName` varchar(64) NOT NULL,
  `LastName` varchar(64) NOT NULL,
  `Email` varchar(64) NOT NULL,
  `Username` varchar(32) NOT NULL,
  `Password` varchar(32) NOT NULL,
  `Status` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO user VALUES("1","user1","user1","user1@gmail.com","user1","user1","Active");
INSERT INTO user VALUES("2","user2","user2","user2@gmail.com","user2","user2","Inactive");
INSERT INTO user VALUES("7","user3","Cute","user3@gmail.com","user3","user3","Active");

