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
 fldEmail					VarChar(40) 	Not Null,
 fldSalt					char(10),
 fldAuthenticationKey		char(32) 		Not Null,
 fldName					VarChar(20) 	Not Null,
 
 Constraint tblCustomer_pk Primary Key (fldCustomerId),
 UNIQUE KEY fldEmail (fldEmail)
 ) Engine=InnoDB Default Charset=utf8;
 
INSERT INTO tblCustomer VALUES (1, 'anton@mail.com', 	'Q6Q8amXOwG', '3895016ea20bc731c5f539fa4c283f55', 'Anton');
INSERT INTO tblCustomer VALUES (2, 'bob@mail.com', 	'Xb0fIgdOxu', '590a7813ec4d8efed2c228ca62cb5948', 'Bob');
INSERT INTO tblCustomer VALUES (3, 'cat@mail.com', 	'fziPHfkLE4', 'c8202b82a60c83cd7b1d47ded73a6910', 'Cathy');
INSERT INTO tblCustomer VALUES (4, 'donna@mail.com', 	'tgHK4NWuYk', 'd96f3c18be16f75925bfc67243a0ffbc', 'Donna');
INSERT INTO tblCustomer VALUES (5, 'eve@mail.com', 	'Y2wkSBzKgT', '54c8249405cd7623ba8a7a6cdcd18f18', 'Eve');



Create Table If Not Exists tblRun
(
 fldRunId					MEDIUMINT 		Not Null AUTO_INCREMENT,
 fldDate 					date			Not null,
 fldRouteName				VarChar(20),
 fldKm						double(4, 2),
 fldSeconds					int,
 fldFeeling					VarChar(20),
 fldCustomerId				MEDIUMINT 		Not Null,
 
 Constraint tblRun_pk Primary Key (fldRunId),
 Constraint tblRun_Customer_fk Foreign Key (fldCustomerId) References tblCustomer (fldCustomerId)
 ) Engine=InnoDB Default Charset=utf8;
 
 INSERT INTO tblRun VALUES (1, '2016-02-15', 'Intro + 4 rounds', 5.5, 1800, 'IA', 1);
 INSERT INTO tblRun VALUES (2, '2016-08-31', 'Intro + 4 rounds', 5.5, 1920, 'IA', 2);
 INSERT INTO tblRun VALUES (3, '2016-09-11', 'Intro + 4 rounds', 5.5, 1900, 'IA', 3);
 

 
 
 
 
 
 
 
 
 
 
 
 
 
 