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
 
-- password ant123, bob123, etc
INSERT INTO tblCustomer VALUES (1, 'Anton',	'anton@mail.com',  	'Q6Q8amXOwG', '3895016ea20bc731c5f539fa4c283f55');
INSERT INTO tblCustomer VALUES (2, 'Bob', 	'bob@mail.com', 	'VunD2Ll7Oc', '399a56c65ba9945fcc90f2a27fb92a2c');
INSERT INTO tblCustomer VALUES (3, 'Cathy',	'cat@mail.com',  	'Osl2FAdYd0', 'af33dc1fe1756f4049302605b77fd955');
INSERT INTO tblCustomer VALUES (4, 'Donna',	null,		  		'N9iC5AIVQL', 'defd4c5d11c35d07932be4e858925177');
INSERT INTO tblCustomer VALUES (5, 'Eve',	'eve@mail.com',  	'xfkGTyl4n8', 'cc564f9bcca9b57c7387d3a1a5efa83f');




Create Table If Not Exists tblRoute
(
 fldRouteId					MEDIUMINT 		Not Null AUTO_INCREMENT,
 fldRouteName				VarChar(20)		Not Null,
 fldRouteDistance			double(4, 2)	Not Null,
 fldRouteCustomerId			MEDIUMINT 		Not Null,
 
 Constraint tblRoute_pk Primary Key (fldRouteId),
 Constraint tblRoute_Customer_fk Foreign Key (fldRouteCustomerId) References tblCustomer (fldCustomerId)
 ) Engine=InnoDB Default Charset=utf8;
 
 INSERT INTO tblRoute VALUES (1, 'Intro + 4 rounds', 	5.5,	1);
 INSERT INTO tblRoute VALUES (2, 'Oval 10 rounds', 		4.0,	2);
 INSERT INTO tblRoute VALUES (3, 'Oval 8 rounds', 		3.0,	2);
 INSERT INTO tblRoute VALUES (4, 'Lake run', 			15.0,	1);
 
  
 
 
Create Table If Not Exists tblRun
(
 fldRunId					MEDIUMINT 		Not Null AUTO_INCREMENT,
 fldDate 					date			Not null,
 fldDistance				double(4, 2),
 fldSeconds					int,
 fldFeeling					VarChar(20),
 fldBlankRouteName			VarChar(20),
 fldRunCustomerId			MEDIUMINT		Not Null,
 fldRunRouteId				MEDIUMINT,
 
 Constraint tblRun_pk Primary Key (fldRunId),
 Constraint tblRun_Customer_fk Foreign Key (fldRunCustomerId) References tblCustomer (fldCustomerId),
 Constraint tblRun_Route_fk Foreign Key (fldRunRouteId) References tblRoute (fldRouteId)
 ) Engine=InnoDB Default Charset=utf8;
 
 INSERT INTO tblRun VALUES (1, '2016-02-15', 5.5, 	1800, 'Tired', 		null,	1,	null);
 INSERT INTO tblRun VALUES (2, '2016-08-31', 5.5, 	1920, 'Fresh legs', null,	2,	null);
 INSERT INTO tblRun VALUES (3, '2016-09-11', null, 	1900, 'Pain knee', 	null,	1,	1);
 
 

 
 
 
 
 
 
 
 
 
 
 
 
 
 