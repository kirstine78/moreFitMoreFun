--  MoreFitMoreFun
--  Kirstine Nielsen

-- Delete database and rebuild new database

Drop Database If Exists MoreFitMoreFun;
Create Database If Not Exists MoreFitMoreFun;
Use MoreFitMoreFun;

-- Build tables and Add Test Data

Create Table If Not Exists tblCustomer
(
 fldCustomerId				MEDIUMINT 		Not Null AUTO_INCREMENT,
 fldName					VarChar(20) 	Not Null,
 fldEmail					VarChar(40),
 fldSalt					char(10),
 fldAuthenticationKey		char(32) 		Not Null,
 
 Constraint tblCustomer_pk Primary Key (fldCustomerId),
 UNIQUE KEY fldName (fldName)
 ) Engine=InnoDB Default Charset=utf8;
 
-- password ant123, bob123 etc
INSERT INTO tblCustomer VALUES (1, 'Anton',	'anton@mail.com',  	'Q6Q8amXOwG', '3895016ea20bc731c5f539fa4c283f55');
INSERT INTO tblCustomer VALUES (2, 'Bob', 	'bob@mail.com', 	'VunD2Ll7Oc', '399a56c65ba9945fcc90f2a27fb92a2c');
INSERT INTO tblCustomer VALUES (3, 'Cathy',	'cat@mail.com',  	'Osl2FAdYd0', 'af33dc1fe1756f4049302605b77fd955');
INSERT INTO tblCustomer VALUES (4, 'Donna',	null,		  		'N9iC5AIVQL', 'defd4c5d11c35d07932be4e858925177');
INSERT INTO tblCustomer VALUES (5, 'Eve',	'eve@mail.com',  	'xfkGTyl4n8', 'cc564f9bcca9b57c7387d3a1a5efa83f');




Create Table If Not Exists tblRun
(
 fldRunId					MEDIUMINT 		Not Null AUTO_INCREMENT,
 fldDate 					date			Not null,
 fldKm						double(4, 2),
 fldSeconds					int,
 fldFeeling					VarChar(20),
 fldCustomerId				MEDIUMINT 		Not Null,
 
 Constraint tblRun_pk Primary Key (fldRunId),
 Constraint tblRun_Customer_fk Foreign Key (fldCustomerId) References tblCustomer (fldCustomerId)
 ) Engine=InnoDB Default Charset=utf8;
 
 INSERT INTO tblRun VALUES (1, '2016-02-15', 5.5, 1800, 'IA', 1);
 INSERT INTO tblRun VALUES (2, '2016-08-31', 5.5, 1920, 'IA', 2);
 INSERT INTO tblRun VALUES (3, '2016-09-11', 5.5, 1900, 'IA', 3);
 
 
 

Create Table If Not Exists tblRoute
(
 fldRouteId					MEDIUMINT 		Not Null AUTO_INCREMENT,
 fldRouteName				VarChar(20)		Not Null,
 fldRouteDistance			double(4, 2)	Not Null,
 fldCustomerId				MEDIUMINT 		Not Null,
 
 Constraint tblRoute_pk Primary Key (fldRouteId),
 Constraint tblRoute_Customer_fk Foreign Key (fldCustomerId) References tblCustomer (fldCustomerId)
 ) Engine=InnoDB Default Charset=utf8;
 
 INSERT INTO tblRoute VALUES (1, 'Intro + 4 rounds', 	5.5,	1);
 INSERT INTO tblRoute VALUES (2, 'Oval 10 rounds', 		4.0,	2);
 INSERT INTO tblRoute VALUES (3, 'Oval 8 rounds', 		3.0,	2);
 
 


Create Table If Not Exists tblRunKnownRoute
(
 fldRunKnownRouteId			MEDIUMINT 		Not Null AUTO_INCREMENT,
 fldDateKnownRoute 			date			Not null,
 fldSecondsKnownRoute		int,
 fldFeelingKnownRoute		VarChar(20),
 fldRouteId					MEDIUMINT 		Not Null,
 
 Constraint tblRunKnownRoute_pk Primary Key (fldRunKnownRouteId),
 Constraint tblRunKnownRoute_Route_fk Foreign Key (fldRouteId) References tblRoute (fldRouteId)
 ) Engine=InnoDB Default Charset=utf8;
 
 INSERT INTO tblRunKnownRoute VALUES (1, '2016-02-15', 1800, 'IA', 3);
 INSERT INTO tblRunKnownRoute VALUES (2, '2016-08-31', 1920, 'IA', 1);
 INSERT INTO tblRunKnownRoute VALUES (3, '2016-09-11', 1900, 'IA', 1);
 
 

 
 
 
 
 
 
 
 
 
 
 
 
 
 