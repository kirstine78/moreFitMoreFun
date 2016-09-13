<?php

/**
* get all runs for specific user
*/
function retrieveRuns($customerId, $email, $authKey)
{	
	// get database connection
	$databaseConnection = getConnection(); 	
	
	// build sql string
	$sql = "SELECT * FROM tblRun WHERE fldCustomerId = :id_placeholder"; 
	
	try
	{ 		
		$statement = $databaseConnection->prepare($sql); 
		
		// bind parameters
		$statement->bindParam("id_placeholder", $customerId);
		
		$statement->execute();	
		
		$row = $statement->fetchALL(PDO::FETCH_OBJ); 	
		
		// close connection
		$databaseConnection = null; 
		
		// return the retrieved rows in an array
		// If no rows are retrieved then row is null, and null is returned
		return $row; 
	} 
	catch (PDOException $e) 
	{ 
		if ($databaseConnection != null) 
		{
			$databaseConnection = null; 			
		}
		echo $e->getMessage(); 
	} 
}



function createRun($date, $routeName, $km, $seconds, $feeling, $customerId)
{
	// get database connection
	$databaseConnection = getConnection(); 	
	
	// build sql string
	$sql = "INSERT INTO tblRun (fldDate, fldRouteName, fldKm, fldSeconds, fldFeeling, fldCustomerId) 
			VALUES (:date_placeholder, :routeName_placeholder, :km_placeholder, :seconds_placeholder, :feeling_placeholder, :customerId_placeholder)";
	
	try
	{ 		
		$statement = $databaseConnection->prepare($sql); 
		
		// bind parameters
		$statement->bindParam("date_placeholder", $date);
		$statement->bindParam("routeName_placeholder", $routeName);
		$statement->bindParam("km_placeholder", $km);
		$statement->bindParam("seconds_placeholder", $seconds);
		$statement->bindParam("feeling_placeholder", $feeling);
		$statement->bindParam("customerId_placeholder", $customerId);
		
		$statement->execute();	
		
		// close connection
		$databaseConnection = null; 
		
		return true;
	} 
	catch (PDOException $e) 
	{ 
		if ($databaseConnection != null) 
		{
			$databaseConnection = null; 			
		}
		echo $e->getMessage(); 
		
		return false;
	}	
}



function editRun($runId, $date, $routeName, $km, $seconds, $feeling)
{
	// get database connection
	$databaseConnection = getConnection(); 	
	
	// build sql string
	$sql = "UPDATE tblRun 
			SET fldDate=:date_placeholder, 
				fldRouteName = :routeName_placeholder, 
				fldKm = :km_placeholder, 
				fldSeconds = :seconds_placeholder, 
				fldFeeling = :feeling_placeholder
			WHERE fldRunId = :runId_placeholder";
	
	try
	{ 		
		$statement = $databaseConnection->prepare($sql); 
		
		// bind parameters
		$statement->bindParam("date_placeholder", $date);
		$statement->bindParam("routeName_placeholder", $routeName);
		$statement->bindParam("km_placeholder", $km);
		$statement->bindParam("seconds_placeholder", $seconds);
		$statement->bindParam("feeling_placeholder", $feeling);
		$statement->bindParam("runId_placeholder", $runId);
		
		$statement->execute();	
		
		// close connection
		$databaseConnection = null; 
		
		return true;
	} 
	catch (PDOException $e) 
	{ 
		if ($databaseConnection != null) 
		{
			$databaseConnection = null; 			
		}
		echo $e->getMessage(); 
		
		return false;
	}
}



function eraseRun($runId)
{	
	// get database connection
	$databaseConnection = getConnection(); 	
	
	// build sql string
	$sql = "DELETE FROM tblRun WHERE fldRunId = :runId_placeholder";
	
	try
	{ 		
		$statement = $databaseConnection->prepare($sql); 
		
		// bind parameters
		$statement->bindParam("runId_placeholder", $runId);
		
		$statement->execute();	
		
		// close connection
		$databaseConnection = null; 
		
		return true;
	} 
	catch (PDOException $e) 
	{ 
		if ($databaseConnection != null) 
		{
			$databaseConnection = null; 			
		}
		echo $e->getMessage(); 
		
		return false;
	}
}



// retrieve Customer
function retrieveCustomer($an_email) 
{	
	// build sql string
	$sql = "SELECT * FROM tblCustomer WHERE fldEmail=:email_placeholder"; 
	
	// get db connection
	$databaseConnection = getConnection(); 	
		
	try
	{ 	
		$statement = $databaseConnection->prepare($sql);		
		
		// bind parameters
		$statement->bindParam("email_placeholder", $an_email); 
		
		$statement->execute(); 	
		$row = $statement->fetch(PDO::FETCH_OBJ); 	// fetch because zero or one row
		
		// close connection
		$databaseConnection = null; 
		
		// return the retrieved row
		return $row; 
	} 
	catch (PDOException $e) 
	{ 
		if ($databaseConnection != null) 
		{
			$databaseConnection = null; 			
		}
		echo $e->getMessage(); 
	} 	
}


function retrieveCustomerBasedOnEmailAndAuthKey($an_email, $an_authKey)
{	
	// get db connection
	$databaseConnection = getConnection(); 

	// build sql string
	$sql = "SELECT * FROM tblCustomer WHERE fldEmail=:email_placeholder AND fldAuthenticationKey=:authKey_placeholder";
	
	try 
	{
		
		$statement = $databaseConnection->prepare($sql);
		
		$statement->bindParam("email_placeholder", $an_email); 
		$statement->bindParam("authKey_placeholder", $an_authKey); 
		
		$statement->execute(); 
		$row = $statement->fetch(PDO::FETCH_OBJ); 
		
		// close connection
		$databaseConnection = null;  
		
		return $row;
	}
	catch (PDOException $e)
	{ 
		if ($databaseConnection != null) 
		{
			$databaseConnection = null; 			
		}
		echo $e->getMessage();
	}			
}


// create a customer
function createCustomer($an_email, $a_name, $a_salt, $a_hash)
{
	// build sql string
	$sql = "INSERT INTO tblCustomer (fldEmail, fldName, fldSalt, fldAuthenticationKey) 
			VALUES (:email_placeholder, :name_placeholder, :salt_placeholder, :authKey_placeholder)";
			
	try {
		// get db connection
		$db = getConnection(); 
		
		$statement = $db->prepare($sql); 
		
		$statement->bindParam("email_placeholder", $an_email);
		$statement->bindParam("name_placeholder", $a_name);
		$statement->bindParam("salt_placeholder", $a_salt); 
		$statement->bindParam("authKey_placeholder", $a_hash);
		
		$statement->execute(); 
		
		// close db connection
		$db = null;
		
		return true;
	}
	catch (PDOException $e)
	{ 
		if ($db != null) 
		{
			$db = null; 			
		}
		echo $e->getMessage();
		return false;
	}
}


// Returns a reference to a PDO Database connection object.
function getConnection()
{	
	// Connection details
	$dbhost = "127.0.0.1"; 
	$dbUser = "root"; 
	$dbpass = "root"; 
	$dbName = "MoreFitMoreFun";
	$dsn = "mysql:host=$dbhost;dbname=$dbName";
	
	try 
	{
		// Create a PDO Connection Object, set Attributes and 
		// return a reference to this connection object.
		$dbConnection = new PDO($dsn, $dbUser, $dbpass); 
		
		$dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
		
		return $dbConnection;
	}
	catch (PDOException $e)
	{ 
		echo "Error PDO Exception: " . $e->getMessage(); 
	}
}
?>