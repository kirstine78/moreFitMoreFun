<?php

/**
* get all runs for specific user
*/
function retrieveRuns($customerId)
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



// retrieve Routes for specific customer
function retrieveRoutes($customerId)
{
	// get database connection
	$databaseConnection = getConnection(); 	
	
	// build sql string
	$sql = "SELECT * FROM tblRoute WHERE fldCustomerId = :id_placeholder"; 
	
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



// retrieve Customer
function retrieveCustomer($aName) 
{	
	// build sql string
	$sql = "SELECT * FROM tblCustomer WHERE fldName = :name_placeholder"; 
	
	// get db connection
	$databaseConnection = getConnection(); 	
		
	try
	{ 	
		$statement = $databaseConnection->prepare($sql);		
		
		// bind parameters
		$statement->bindParam("name_placeholder", $aName); 
		
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


function retrieveCustomerBasedOnNameAndAuthKey($a_name, $an_authKey)
{	
	// get db connection
	$databaseConnection = getConnection(); 

	// build sql string
	$sql = "SELECT * FROM tblCustomer WHERE fldName=:name_placeholder AND fldAuthenticationKey=:authKey_placeholder";
	
	try 
	{
		
		$statement = $databaseConnection->prepare($sql);
		
		$statement->bindParam("name_placeholder", $a_name); 
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
function createCustomer($a_name, $an_email, $a_salt, $a_hash)
{
	// build sql string
	$sql = "INSERT INTO tblCustomer (fldName, fldEmail, fldSalt, fldAuthenticationKey) 
			VALUES (:name_placeholder, :email_placeholder, :salt_placeholder, :authKey_placeholder)";
			
	try {
		// get db connection
		$db = getConnection(); 
		
		$statement = $db->prepare($sql); 
		
		$statement->bindParam("name_placeholder", $a_name);
		$statement->bindParam("email_placeholder", $an_email);
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



// update a Customer just Email
function saveUpdatesCustomerEmail($a_customerId, $an_email)
{	
	// build sql string
	$sql = "UPDATE tblCustomer SET fldEmail = :email_placeholder 
			WHERE fldCustomerId=:id_placeholder";
	
	try {
		// get db connection
		$db = getConnection(); 
		
		$statement = $db->prepare($sql); 
		
		$statement->bindParam("id_placeholder", $a_customerId);
		$statement->bindParam("email_placeholder", $an_email);
		
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
} // end function



// update a Customer email and password
function saveUpdatesCustomer($a_customerId, $an_email, $a_salt, $a_hashValue)
{	
	// build sql string
	$sql = "UPDATE tblCustomer SET fldEmail = :email_placeholder, fldSalt=:salt_placeholder, fldAuthenticationKey=:hash_placeholder  
			WHERE fldCustomerId=:id_placeholder";
	
	try {
		// get db connection
		$db = getConnection(); 
		
		$statement = $db->prepare($sql); 
		
		$statement->bindParam("id_placeholder", $a_customerId);
		$statement->bindParam("email_placeholder", $an_email); 
		$statement->bindParam("salt_placeholder", $a_salt);
		$statement->bindParam("hash_placeholder", $a_hashValue);
		
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
} // end function



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