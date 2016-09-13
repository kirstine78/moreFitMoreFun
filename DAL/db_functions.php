<?php
// Kirstine Nielsen 100527988
//Assignment EzyCarHire.

// encapsulate connection stuff in a function. 
// When called this function returns a reference to a PDO Database connection object.
function getConnection()
{
	// Connection details
	$dbhost = "mysql6.000webhost.com"; 
	$dbUser = "a3153920_xyz"; 
	$dbpass = "byethostkbn123"; 
	$dbName = "a3153920_abc";
	$dsn = "mysql:host=$dbhost;dbname=$dbName";
	
	try 
	{
		// Create a PDO Connection Object,set Attributes and return
		// a reference to this connection object.
		$dbConnection = new PDO($dsn, $dbUser, $dbpass); 
		
		$dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
		
		return $dbConnection;
	}
	catch (PDOException $e)
	{ 
		// echo "Error PDO Exception"; 
		echo "An Error occured: ".$e->getMessage();
	}
}


// retrieve Brands
function retrieveBrands() 
{	
	// build sql string
	$sql = "SELECT fldBrand FROM tblCar GROUP BY fldBrand"; 
	
	try
	{ 
		// db related
		$db = getConnection(); 		
		$statement = $db->prepare($sql);		
		$statement->execute(); 	
		$row = $statement->fetchALL(PDO::FETCH_OBJ); 	
		
		// close connection
		$db = null; 
		
		// return the retrieved rows in an array
		// If no rows are retrieved then row is null, and null is returned
		return $row; 
	} 
	catch (PDOException $e) 
	{ 
		if ($db != null) 
		{
			$db = null;		
		} 
		echo $e->getMessage(); 
	} 
}


// retrieve availabe Cars
function retrieveAvailableCars($a_suburb, $a_pickupDate, $a_dropoffDate) 
{
	// build sql string
	// startdate cannot be inside a period already booked.
	// enddate cannot be inside a period already booked.
	// startdate cannot be before any current booking periods if enddate is inside a period already booked or after the period.
	$sql = "SELECT 	C.fldCarId, C.fldLocationId, C.fldRegoNo, C.fldBrand, C.fldSeating, C.fldHirePriceCurrent, L.fldStreetName, L.fldDescription, S.fldSuburb
			FROM 	tblCar C, tblLocation L, tblSuburb S
			WHERE 	C.fldLocationId=L.fldLocationId 
					AND L.fldSuburbId=S.fldSuburbId 
					AND S.fldSuburbId=:suburb_placeholder 
					AND C.fldCarId NOT IN 
						(SELECT fldCarId
						FROM 	tblBooking
						WHERE 	(:pickupDate_placeholder >= fldStartDate AND :pickupDate_placeholder <= fldReturnDate) 
								OR (:dropoffDate_placeholder >= fldStartDate AND :dropoffDate_placeholder <= fldReturnDate) 
								OR (:pickupDate_placeholder < fldStartDate AND :dropoffDate_placeholder >= fldStartDate))";
	
	try
	{ 
		// db related
		$db = getConnection(); 		
		$statement = $db->prepare($sql); 
		
		// bind parameters
		$statement->bindParam("suburb_placeholder", $a_suburb); 
		$statement->bindParam("pickupDate_placeholder", $a_pickupDate); 
		$statement->bindParam("dropoffDate_placeholder", $a_dropoffDate); 
		
		$statement->execute();		
		$row = $statement->fetchALL(PDO::FETCH_OBJ); 	
		
		// close connection
		$db = null; 
		
		// return the retrieved rows in an array
		// If no rows are retrieved then row is null, and null is returned
		return $row; 
	} 
	catch (PDOException $e) 
	{ 
		if ($db != null) 
		{
			$db = null; 			
		}
		echo $e->getMessage(); 
	} 
}


// retrieve Suburbs
function retrieveSuburbs() 
{	
	// build sql string
	$sql = "SELECT * FROM tblSuburb GROUP BY fldSuburb";
	
	try
	{ 
		// db related
		$db = getConnection(); 		
		$statement = $db->prepare($sql);		
		$statement->execute(); 	
		$row = $statement->fetchALL(PDO::FETCH_OBJ); 	
		
		// close connection
		$db = null; 
		
		// return the retrieved rows in an array
		// If no rows are retrieved then row is null, and null is returned
		return $row;  
	} 
	catch (PDOException $e) 
	{ 
		if ($db != null) 
		{
			$db = null; 			
		}
		echo $e->getMessage(); 
	} 
}


// retrieve Customer
function retrieveCustomer($an_email) 
{	
	// build sql string
	$sql = "SELECT * FROM tblCustomer WHERE fldEmail=:email_placeholder"; 
	
	try
	{ 
		// get db connection
		$db = getConnection(); 		
		$statement = $db->prepare($sql);		
		
		// bind parameters
		$statement->bindParam("email_placeholder", $an_email); 
		
		$statement->execute(); 	
		$row = $statement->fetch(PDO::FETCH_OBJ); 	// fetch because zero or one row
		
		// close connection
		$db = null; 
		
		// return the retrieved row
		return $row; 
	} 
	catch (PDOException $e) 
	{ 
		if ($db != null) 
		{
			$db = null; 			
		}
		echo $e->getMessage(); 
	} 	
}


function retrieveCustomerBasedOnEmailAndAuthKey($an_email, $an_authKey)
{	
	// build sql string
	$sql = "SELECT * FROM tblCustomer WHERE fldEmail=:email_placeholder AND fldAuthenticationKey=:authKey_placeholder";
	
	try 
	{
		// get db connection
		$db = getConnection(); 
		
		$statement = $db->prepare($sql);
		
		$statement->bindParam("email_placeholder", $an_email); 
		$statement->bindParam("authKey_placeholder", $an_authKey); 
		
		$statement->execute(); 
		$row = $statement->fetch(PDO::FETCH_OBJ); 
		
		// close connection
		$db = null;  
		
		return $row;
	}
	catch (PDOException $e)
	{ 
		if ($db != null) 
		{
			$db = null; 			
		}
		echo $e->getMessage();
	}			
}


// retrieve ALL Bookings for specific customer; many details
// ordered by fldStartDate desc. So the youngest fldStartDate is first.
function retrieveBookings($a_customerId)
{	
	// build sql string
	$sql = "SELECT 	B.fldBookingNo, B.fldCarId, 
					B.fldStartDate, B.fldReturnDate, 
					B.fldActualReturnDate, B.fldOdometerFinish, 
					B.fldHirePricePerDay, C.fldSeating, 
					C.fldRegoNo, C.fldBrand, 
					S.fldSuburb, L.fldStreetName, L.fldDescription
			FROM 	tblBooking B, tblCar C, tblLocation L, tblSuburb S
			WHERE 	B.fldCarId=C.fldCarId AND 
					C.fldLocationId=L.fldLocationId AND
					L.fldSuburbId=S.fldSuburbId AND
					B.fldCustomerId=:customerId_placeholder
			ORDER BY B.fldStartDate DESC"; 
	
	try
	{ 
		// get db connection
		$db = getConnection(); 		
		$statement = $db->prepare($sql);		
		
		// bind parameters
		$statement->bindParam("customerId_placeholder", $a_customerId); 
		
		$statement->execute(); 	
		$row = $statement->fetchALL(PDO::FETCH_OBJ); 	
		
		// close connection
		$db = null; 
		
		// return the retrieved rows in an array
		// If no rows are retrieved then row is null, and null is returned
		return $row;
	} 
	catch (PDOException $e) 
	{ 
		if ($db != null) 
		{
			$db = null; 			
		}
		echo $e->getMessage(); 
	} 
}


// retrieve Unreturned Bookings for specific customer; many details
// ordered by fldStartDate desc. So the youngest fldStartDate is first.
function retrieveUnreturnedBookings($a_customerId)
{	
	// build sql string				
	$sql = "SELECT 	B.fldBookingNo, B.fldCarId, 
					B.fldStartDate, B.fldReturnDate, 
					B.fldActualReturnDate, B.fldOdometerFinish, 
					B.fldHirePricePerDay, C.fldSeating, 
					C.fldRegoNo, C.fldBrand, 
					S.fldSuburb, L.fldStreetName, L.fldDescription
			FROM 	tblBooking B, tblCar C, tblLocation L, tblSuburb S
			WHERE 	B.fldCarId=C.fldCarId AND 
					C.fldLocationId=L.fldLocationId AND
					L.fldSuburbId=S.fldSuburbId AND
					B.fldCustomerId=:customerId_placeholder AND
					B.fldActualReturnDate is NULL
			ORDER BY B.fldStartDate DESC"; 
	
	try
	{ 
		// get db connection
		$db = getConnection(); 		
		$statement = $db->prepare($sql);		
		
		// bind parameters
		$statement->bindParam("customerId_placeholder", $a_customerId); 
		
		$statement->execute(); 	
		$row = $statement->fetchALL(PDO::FETCH_OBJ); 	
		
		// close connection
		$db = null; 
		
		// return the retrieved rows in an array
		// If no rows are retrieved then row is null, and null is returned
		return $row;
	} 
	catch (PDOException $e) 
	{ 
		if ($db != null) 
		{
			$db = null; 			
		}
		echo $e->getMessage(); 
	} 
}


// retrieve Bookings based on period clause
function retrieveBookingsTimePeriodClause($customerId, $startDate, $returnDate)
{	
	// build sql string
	$sql = "SELECT fldCustomerId
			FROM tblBooking
			WHERE ((:pickupDate_placeholder >= fldStartDate AND :pickupDate_placeholder <= fldReturnDate) 
			OR (:dropoffDate_placeholder >= fldStartDate AND :dropoffDate_placeholder <= fldReturnDate) 
			OR (:pickupDate_placeholder < fldStartDate AND :dropoffDate_placeholder >= fldStartDate))
			AND fldCustomerId=:customerId_placeholder";
	
	try
	{ 
		// get db connection
		$db = getConnection(); 		
		$statement = $db->prepare($sql);		
		
		// bind parameters
		$statement->bindParam("customerId_placeholder", $customerId); 
		$statement->bindParam("pickupDate_placeholder", $startDate); 
		$statement->bindParam("dropoffDate_placeholder", $returnDate); 
		
		$statement->execute(); 	
		$row = $statement->fetchALL(PDO::FETCH_OBJ); 	
		
		// close connection
		$db = null; 
		
		// return the retrieved rows in an array
		// If no rows are retrieved then row is null, and null is returned
		return $row;
	} 
	catch (PDOException $e) 
	{ 
		if ($db != null) 
		{
			$db = null; 			
		}
		echo $e->getMessage(); 
	} 
}


// get specific booking
function getSpecificBooking($aBookingNumber)
{	
	// build sql string
	$sql = "SELECT *
			FROM tblBooking
			WHERE fldBookingNo=:bookingNumber_placeholder";
	
	try
	{ 
		// get db connection
		$db = getConnection(); 		
		$statement = $db->prepare($sql);		
		
		// bind parameters
		$statement->bindParam("bookingNumber_placeholder", $aBookingNumber);
		
		$statement->execute(); 	
		$row = $statement->fetch(PDO::FETCH_OBJ); 	
		
		// close connection
		$db = null; 
		
		return $row;
	} 
	catch (PDOException $e) 
	{ 
		if ($db != null) 
		{
			$db = null; 			
		}
		echo $e->getMessage(); 
	} 	
}


// update a Customer mobile and password
function saveUpdatesCustomer($a_customerId, $a_mobile, $a_salt, $a_hashValue)
{	
	// build sql string
	$sql = "UPDATE tblCustomer SET fldMobile=:mobile_placeholder, fldSalt=:salt_placeholder, fldAuthenticationKey=:hash_placeholder  
			WHERE fldCustomerId=:id_placeholder";
	
	try {
		// get db connection
		$db = getConnection(); 
		
		$statement = $db->prepare($sql); 
		
		$statement->bindParam("id_placeholder", $a_customerId);
		$statement->bindParam("mobile_placeholder", $a_mobile); 
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


// update a Customer just Mobile
function saveUpdatesCustomerMobile($a_customerId, $a_mobile)
{	
	// build sql string
	$sql = "UPDATE tblCustomer SET fldMobile=:mobile_placeholder 
			WHERE fldCustomerId=:id_placeholder";
	
	try {
		// get db connection
		$db = getConnection(); 
		
		$statement = $db->prepare($sql); 
		
		$statement->bindParam("id_placeholder", $a_customerId);
		$statement->bindParam("mobile_placeholder", $a_mobile);
		
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


// create booking
function createBooking($a_carId, $a_customerId, $a_startDate, $a_returnDate, $a_hirePrice)
{
	// build sql string
	$sql = "INSERT INTO tblBooking (fldCarId, fldCustomerId, fldStartDate, fldReturnDate, fldHirePricePerDay) 
			VALUES (:carId_placeholder, :customerId_placeholder, :startDate_placeholder, :returnDate_placeholder, :hirePrice_placeholder)";
		
	try {
		// get db connection
		$db = getConnection(); 
		
		$statement = $db->prepare($sql); 
		
		$statement->bindParam("carId_placeholder", $a_carId);
		$statement->bindParam("customerId_placeholder", $a_customerId); 
		$statement->bindParam("startDate_placeholder", $a_startDate);
		$statement->bindParam("returnDate_placeholder", $a_returnDate);
		$statement->bindParam("hirePrice_placeholder", $a_hirePrice);
		
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


// create a customer
function createCustomer($an_email, $a_salt, $a_hash, $a_firstName, $a_lastName, $a_licenceNo, $a_mobile)
{
	// build sql string
	$sql = "INSERT INTO tblCustomer (fldEmail, fldSalt, fldAuthenticationKey, fldFirstName, fldLastName, fldLicenceNo, fldMobile) 
			VALUES (:email_placeholder, :salt_placeholder, :authKey_placeholder, :firstName_placeholder, :lastName_placeholder, :licenceNo_placeholder, :mobile_placeholder)";
			
	try {
		// get db connection
		$db = getConnection(); 
		
		$statement = $db->prepare($sql); 
		
		$statement->bindParam("email_placeholder", $an_email);
		$statement->bindParam("salt_placeholder", $a_salt); 
		$statement->bindParam("authKey_placeholder", $a_hash);
		$statement->bindParam("firstName_placeholder", $a_firstName);
		$statement->bindParam("lastName_placeholder", $a_lastName);
		$statement->bindParam("licenceNo_placeholder", $a_licenceNo);
		$statement->bindParam("mobile_placeholder", $a_mobile);
		
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


// drop off car, update the record
function dropOffCar($bookingNo, $actualReturnDate, $odometerFinish)
{
	// build sql string
	$sql = "UPDATE tblBooking 
			SET fldActualReturnDate=:actualReturnDate_placeholder, fldOdometerFinish=:odom_placeholder 
			WHERE fldBookingNo=:bookingNo_placeholder";
	
	try {
		// get db connection
		$db = getConnection(); 
		
		$statement = $db->prepare($sql); 
			
		$statement->bindParam("bookingNo_placeholder", $bookingNo);
		$statement->bindParam("actualReturnDate_placeholder", $actualReturnDate);
		$statement->bindParam("odom_placeholder", $odometerFinish);
		
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


?>


