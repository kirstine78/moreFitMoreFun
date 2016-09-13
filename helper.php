<?php
// Kirstine Nielsen
// Health web services


require 'Slim/Slim.php';
require_once("DAL/databaseFunctions.php");

$app = new Slim(); 

// GET routes
// $app->get('/authenticate/:anEmail/:anAuthKey', 'authenticateUser'); 
$app->get('/run/', 'getRuns');


// $app->get('/cars/available/:suburbId/:pickupDate/:dropoffDate', 'getAvailableCars');
// $app->get('/suburbs', 'getSuburbs');
// $app->get('/customer/:anEmail/:anAuthKey', 'getCustomer');
// $app->get('/bookings/:aCustomerId/:anEmail/:anAuthKey', 'getBookings');
// $app->get('/unreturnedbookings/:aCustomerId/:anEmail/:anAuthKey', 'getUnreturnedBookings');
// $app->get('/passwordValidation/:aPassword/:anEmail/:anAuthKey', 'verifyPasswordIsCorrect');
// $app->get('/collision/:customerId/:pickupDate/:dropoffDate', 'processAreDatesColliding');


// POST routes
$app->post('/run', 'makeRun'); 
$app->post('/customer', 'registerCustomer'); 


// PUT routes
// $app->put('/customer/:customerId', 'updateCustomer');
// $app->put('/bookings', 'returnCar');



// return Car (update Booking fields fldActualReturnDate and fldOdometerFinish)
function returnCar()
{
		
	global $app;
	
	// use slim to get a reference to the HTTP response object to be able to modify it 
	$response = $app->response();
	$response->header('Content-type', 'application/json');	
	
	// ajax restriction. Ajax by default can't make cross domain requests.
	// only needed for browser, not when run from phone
	// $response->headers->set('Access-Control-Allow-Origin', '*'); 
	$response->header('Access-Control-Allow-Origin', '*'); 
	
	// use slim to get the contents of the HTTP PUT request 
	$request = $app->request();
	
	// the request is in JSON format so we need to decode it 
	$q = json_decode($request->getBody());
	
	// authenticate user
	$authenticateResult = isAuthKeyAndEmailOk($q->email, $q->authenticationKey); 
		
	// echo $authenticateResult["VALID"] . "\n";  // boolean
	
	$updateResult = false;
	
	// if authenticate OK
	if ($authenticateResult["VALID"] == "true")
	{
		// echo "inside auth key ok\n";
		
		// make sure that car hasn't already been returned
		if (isCarReturned($q->bookingNo) == false)
		{
			// echo "car has not yet been returned - so ok to return now\n";	
			// update row
			
			// function in databaseFunctions.php return boolean
			$updateResult = dropOffCar($q->bookingNo, $q->actualReturnDate, $q->odometerFinish);			
		}		  
	}
	
	echo json_encode($updateResult);  // boolean	
}


// check if car has been returned
// return boolean
function isCarReturned($aBookingNumber)
{	
	$row = getSpecificBooking($aBookingNumber);
	
	// check if fldActualReturnDate is null
	if ($row->fldActualReturnDate == null)
	{
		// not yet returned
		return false;
	}
	else  // has already been returned
	{
		return true;		
	}
}


// get Brands
function getBrands() 
{		
		
	global $app;
	
	// use slim to get a reference to the HTTP response object to be able to modify it 
	$response = $app->response();
	$response->header('Content-type', 'application/json');	
	
	// ajax restriction. Ajax by default can't make cross domain requests.
	// only needed for browser, not when run from phone
	// $response->headers->set('Access-Control-Allow-Origin', '*'); 
	$response->header('Access-Control-Allow-Origin', '*'); 
	
	$row = retrieveBrands();  // function in databaseFunctions.php return rows or null
		
	// echo out the Array of all rows represented in json format  [{},{}]
	// If no rows are retrieved then $row == null and an empty array is returned (NB. null is NOT returned!!!)
	echo json_encode($row);
}


// get Available Cars at specific suburb in specific time period (suburb is an ID, date format: '2016-09-26')
// returns array of all rows represented in json format  [{},{}]
function getAvailableCars($a_suburb, $a_pickupDate, $a_dropoffDate) 
{		
		
	global $app;
	
	// use slim to get a reference to the HTTP response object to be able to modify it 
	$response = $app->response();
	$response->header('Content-type', 'application/json');	
	
	// ajax restriction. Ajax by default can't make cross domain requests.
	// only needed for browser, not when run from phone
	// $response->headers->set('Access-Control-Allow-Origin', '*'); 
	$response->header('Access-Control-Allow-Origin', '*'); 
	
	$row = retrieveAvailableCars($a_suburb, $a_pickupDate, $a_dropoffDate);  // function in databaseFunctions.php
		
	// echo out the Array of all rows represented in json format  [{},{}]
	// If no rows are retrieved then row length = zero, but row is NOT null!!!
	echo json_encode($row);
}


// get Suburbs
// returns array of all rows represented in json format  [{},{}]
function getSuburbs() 
{		
		
	global $app;
	
	// use slim to get a reference to the HTTP response object to be able to modify it 
	$response = $app->response();
	$response->header('Content-type', 'application/json');	
	
	// ajax restriction. Ajax by default can't make cross domain requests.
	// only needed for browser, not when run from phone
	// $response->headers->set('Access-Control-Allow-Origin', '*'); 
	$response->header('Access-Control-Allow-Origin', '*'); 
	
	$row = retrieveSuburbs();  // function in databaseFunctions.php
		
	// echo out the Array of all rows represented in json format  [{},{}]
	// If no rows are retrieved then row length = zero, but row is NOT null!!!
	echo json_encode($row);
}


// GET Customer
function getCustomer($an_email, $an_authKey) 
{		
		
	global $app;
	
	// use slim to get a reference to the HTTP response object to be able to modify it 
	$response = $app->response();
	$response->header('Content-type', 'application/json');	
	
	// ajax restriction. Ajax by default can't make cross domain requests.
	// only needed for browser, not when run from phone
	// $response->headers->set('Access-Control-Allow-Origin', '*'); 
	$response->header('Access-Control-Allow-Origin', '*'); 
	
	$row = null;
	
	// authenticate user
	$authenticateResult = isAuthKeyAndEmailOk($an_email, $an_authKey); 
	
	
	// if authenticate OK
	if ($authenticateResult["VALID"] == "true")
	{
		// echo "inside auth key ok";
		// update row
		$row = retrieveCustomer($an_email);  // function in databaseFunctions.php
	}
	
	// echo out row represented in json format {  }
	echo json_encode($row);
}


// GET ALL Bookings for a specific customer
// returns array of all rows represented in json format  [{},{}]
function getBookings($a_customerId, $an_email, $an_authKey)
{		
		
	global $app;
	
	// use slim to get a reference to the HTTP response object to be able to modify it 
	$response = $app->response();
	$response->header('Content-type', 'application/json');	
	
	// ajax restriction. Ajax by default can't make cross domain requests.
	// only needed for browser, not when run from phone
	// $response->headers->set('Access-Control-Allow-Origin', '*'); 
	$response->header('Access-Control-Allow-Origin', '*'); 
	
	// authenticate user
	$authenticateResult = isAuthKeyAndEmailOk($an_email, $an_authKey); 
	// echo $authenticateResult["VALID"] . "\n";  // boolean	
	
	$row = null;
	
	// if authenticate OK
	if ($authenticateResult["VALID"] == "true")
	{
		// echo "inside auth key ok";
		// get bookings
		// function in databaseFunctions.php
		$row = retrieveBookings($a_customerId);  // function in databaseFunctions.php
	}	
		
	// echo out the Array of all rows represented in json format  [{},{}]
	// If no rows are retrieved then row length = zero, but row is NOT null!!!
	echo json_encode($row);
}


// GET all Unreturned Bookings for a specific customer
// returns array of all rows represented in json format  [{},{}]
function getUnreturnedBookings($a_customerId, $an_email, $an_authKey)
{		
		
	global $app;
	
	// use slim to get a reference to the HTTP response object to be able to modify it 
	$response = $app->response();
	$response->header('Content-type', 'application/json');	
	
	// ajax restriction. Ajax by default can't make cross domain requests.
	// only needed for browser, not when run from phone
	// $response->headers->set('Access-Control-Allow-Origin', '*'); 
	$response->header('Access-Control-Allow-Origin', '*'); 
	
	// authenticate user
	$authenticateResult = isAuthKeyAndEmailOk($an_email, $an_authKey); 
	// echo $authenticateResult["VALID"] . "\n";  // boolean	
	
	$row = null;
	
	// if authenticate OK
	if ($authenticateResult["VALID"] == "true")
	{
		// echo "inside auth key ok";
		// get bookings
		// function in databaseFunctions.php
		$row = retrieveUnreturnedBookings($a_customerId);  // function in databaseFunctions.php
	}	
		
	// echo out the Array of all rows represented in json format  [{},{}]
	// If no rows are retrieved then row length = zero, but row is NOT null!!!
	echo json_encode($row);
}


// POST make booking, return boolean whether booking was successful or not.
function makeBooking()
{ 
	global $app;
	
	// use slim to get a reference to the HTTP response object to be able to modify it 
	$response = $app->response();
	$response->header('Content-type', 'application/json');	
	
	// ajax restriction. Ajax by default can't make cross domain requests.
	// only needed for browser, not when run from phone
	// $response->headers->set('Access-Control-Allow-Origin', '*'); 
	$response->header('Access-Control-Allow-Origin', '*'); 
	
	// use slim to get the contents of the HTTP PUT request 
	$request = $app->request();
	
	// the request is in JSON format so we need to decode it 
	$q = json_decode($request->getBody());	
	
	// authenticate user
	$authenticateResult = isAuthKeyAndEmailOk($q->email, $q->authenticationKey); 
	// echo $authenticateResult["VALID"] . "\n";  // boolean
	
	$insertResult = false;
	
	// if authenticate OK
	if ($authenticateResult["VALID"] == "true")
	{
		// echo "inside auth key ok\n";
		
		// check again that dates don't collide with other dates for this Customer
		if (areDatesColliding($q->customerId, $q->startDate, $q->returnDate) == false)
		{
			// No collision, OK to insert row
			// function in databaseFunctions.php return boolean
			$insertResult = createBooking($q->carId, $q->customerId, $q->startDate, $q->returnDate, $q->hirePricePay);			
		}		
	}
	
	echo json_encode($insertResult);  // boolean	
} // end function


// check if booking dates collide with any other bookings for this Customer
// echo boolean
function processAreDatesColliding($customerId, $startDate, $returnDate)
{	
		
	global $app;
	
	// use slim to get a reference to the HTTP response object to be able to modify it 
	$response = $app->response();
	$response->header('Content-type', 'application/json');	
	
	// ajax restriction. Ajax by default can't make cross domain requests.
	// only needed for browser, not when run from phone
	// $response->headers->set('Access-Control-Allow-Origin', '*'); 
	$response->header('Access-Control-Allow-Origin', '*'); 
	
	$isColliding = areDatesColliding($customerId, $startDate, $returnDate);
	
	echo json_encode($isColliding);	
}


// check if booking dates collide with any other bookings for this Customer
// return boolean
function areDatesColliding($customerId, $startDate, $returnDate)
{
	$isColliding = false;	
	
	// function in databaseFunctions.php return rows [{},{}]
	// If no rows are retrieved then $row == null
	$row = retrieveBookingsTimePeriodClause($customerId, $startDate, $returnDate);
	
	if ($row == null)
	{
		// echo "\nDates OK - row is null\n";
		// ok to book
		$isColliding = false;
	}
	else  // row exists
	{
		// echo "\nDate COLLISION - row not null\n";
		// cannot book on this time then
		$isColliding = true;
	}
	return $isColliding;	
}


// POST create a customer
function registerCustomer()
{
	global $app;
	
	// use slim to get a reference to the HTTP response object to be able to modify it 
	$response = $app->response();
	$response->header('Content-type', 'application/json');	
	
	// ajax restriction. Ajax by default can't make cross domain requests.
	// only needed for browser, not when run from phone
	// $response->headers->set('Access-Control-Allow-Origin', '*'); 
	$response->header('Access-Control-Allow-Origin', '*'); 
	
	// use slim to get the contents of the HTTP PUT request 
	$request = $app->request();
	
	// the request is in JSON format so we need to decode it 
	$q = json_decode($request->getBody());
	
	$insertResult = false;
	$row = null;	
	
	// check if email (username) is unique
	$isEmailUnique = isTheEmailUnique($q->email);
		
	// only if email is unique then generate salt and create hash value from (password + salt)
	if ($isEmailUnique)
	{	
		// echo "email unique\n";
		$password = $q->password;
		// echo  "password: ".$password . "\n";
		$hashArray = doHashing($password);
				
		$salt = $hashArray[0];
		$hashValue = $hashArray[1];
		
		// function in databaseFunctions.php return boolean
		$insertResult = createCustomer($q->email, $salt, $hashValue, $q->firstName, $q->lastName, $q->licenceNo, $q->mobile); 		
		
		if ($insertResult) // true comes back. successful insert
		{
			// get the row so auth key, customer id, (and email) can be saved on local phone	
			$row = retrieveCustomer($q->email);			
		}
	}
	
	// echo out row represented in json format {  }
	echo json_encode($row);
}


// check if email is unique and return boolean 
function isTheEmailUnique($anEmail)
{
	$isEmailUnique = false;
	
	// function in databaseFunctions.php return rows (zero or one)
	$row = retrieveCustomer($anEmail);  
	
	// check if any row was returned
	if ($row == null)
	{
		// email is unique
		$isEmailUnique = true;		
	}
	return $isEmailUnique;
}


// do hashing on a password, return array with salt and hash value
// if salt is passed as argument then do NOT genereate salt
function doHashing($aPassword, $salt = null)
{
	if ($salt == null)
	{
		// generate salt 
		$lengthSalt = 10;
		$salt = generateRandomString($lengthSalt);
		// echo "salt generated: " . $salt . "\n";		
	}
	
	// generate hash value
	$hashValue = hash('md5', $aPassword . $salt);
	// echo "hashValue generated: " . $hashValue . "\n";	
	
	$hashingArray = array($salt, $hashValue);
	
	return $hashingArray;	
}


// generate random string for Salt
function generateRandomString($length) 
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) 
	{
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}


// PUT - update a Customer return record after update (null if error)
function updateCustomer($a_customerId)
{ 
	global $app;
	
	// use slim to get a reference to the HTTP response object to be able to modify it 
	$response = $app->response();
	$response->header('Content-type', 'application/json');	
	
	// ajax restriction. Ajax by default can't make cross domain requests.
	// only needed for browser, not when run from phone
	// $response->headers->set('Access-Control-Allow-Origin', '*'); 
	$response->header('Access-Control-Allow-Origin', '*'); 
	
	// use slim to get the contents of the HTTP PUT request 
	$request = $app->request();
	
	// the request is in JSON format so we need to decode it 
	$q = json_decode($request->getBody());
	
	// authenticate user
	$authenticateResult = isAuthKeyAndEmailOk($q->email, $q->authenticationKey); 
		
	// echo $authenticateResult["VALID"] . "\n";  // boolean
	
	$saveResult = false;
	$row = null;
	
	// if authenticate OK
	if ($authenticateResult["VALID"] == "true")
	{
		// echo "inside auth key ok\n";
		
		// update row process	
		// if password == '' no password changes should happen
		if ($q->password == '')
		{			
			// echo "password is empty\n";
			// function in databaseFunctions.php return boolean
			$saveResult = saveUpdatesCustomerMobile($a_customerId, $q->mobile); 			
		}
		else  // password change should happen
		{
			// new password means new auth key. 
			$password = $q->password;
			// echo  "password: ".$password . "\n";
			$hashArray = doHashing($password);
					
			$salt = $hashArray[0];
			$hashValue = $hashArray[1];
			
			// function in databaseFunctions.php return boolean
			$saveResult = saveUpdatesCustomer($a_customerId, $q->mobile, $salt, $hashValue); 
		}		 
	}
	
	// get record for customer
	if ($saveResult)
	{
		$row = retrieveCustomer($q->email);
	}
	
	// echo out row represented in json format {  }
	echo json_encode($row);
} // end function


// authenticate if email and auth key are correct
function authenticateUser($an_email, $an_authKey)
{ 			
		
	global $app;
	
	// use slim to get a reference to the HTTP response object to be able to modify it 
	$response = $app->response();
	$response->header('Content-type', 'application/json');	
	
	// ajax restriction. Ajax by default can't make cross domain requests.
	// only needed for browser, not when run from phone
	// $response->headers->set('Access-Control-Allow-Origin', '*'); 
	$response->header('Access-Control-Allow-Origin', '*'); 
		
	$resultArr = isAuthKeyAndEmailOk($an_email, $an_authKey);
	echo json_encode($resultArr);
} // end function


function isAuthKeyAndEmailOk($an_email, $an_authKey)
{
	$row = retrieveCustomerBasedOnEmailAndAuthKey($an_email, $an_authKey);	
		
	// set $resultArray
	$resultArray = array("VALID"=>"false");	
	
	// check if a row was returned
	if ($row != null)
	{
		// echo "row returned\n";
		
		// check if auth key matches and email matches (case sensitive)
		if ( (strcmp($row->fldAuthenticationKey, $an_authKey) == 0) && (strcmp($row->fldEmail, $an_email) == 0) )
		{
			// auth key and email from db match auth key  and email from local storage on phone
			// update $resultArray
			$resultArray = array("VALID"=>"true");			
		}
	}
	return $resultArray; 	
}


function verifyPasswordIsCorrect($a_password, $an_email, $an_authKey)
{		
		
	global $app;
	
	// use slim to get a reference to the HTTP response object to be able to modify it 
	$response = $app->response();
	$response->header('Content-type', 'application/json');	
	
	// ajax restriction. Ajax by default can't make cross domain requests.
	// only needed for browser, not when run from phone
	// $response->headers->set('Access-Control-Allow-Origin', '*'); 
	$response->header('Access-Control-Allow-Origin', '*'); 
	
	// authenticate user
	$authenticateResult = isAuthKeyAndEmailOk($an_email, $an_authKey); 
	// echo $authenticateResult["VALID"] . "\n";  // boolean	
	
	// set $resultArray
	$resultArray = array("VALID"=>"false");
	
	// if authenticate OK
	if ($authenticateResult["VALID"] == "true")
	{
		// echo "inside auth key ok";
		// get customer
		// function in databaseFunctions.php
		$row = retrieveCustomer($an_email);  // function in databaseFunctions.php
		
		// check if hash(password + salt) will be == authenticationKey in row
		$hashArray = doHashing($a_password, $row->fldSalt);
		
		// $salt = $hashArray[0];
		$hashValue = $hashArray[1];
		
		if ($hashValue == $row->fldAuthenticationKey)
		{
			// echo "password is correct";
			// update $resultArray
			$resultArray = array("VALID"=>"true");	
		}
	}	
	echo json_encode($resultArray);	
}

$app->run();
exit();
?>