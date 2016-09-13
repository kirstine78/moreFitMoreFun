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