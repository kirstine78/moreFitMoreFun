<?php
// Kirstine Nielsen
// MoreFitMoreFun web services


require 'Slim/Slim.php';
require_once("DAL/databaseFunctions.php");

$app = new Slim(); 

// GET routes
$app->get('/run/:aCustomerId/:anEmail/:anAuthKey/', 'getRuns');
$app->get('/authenticate/:anEmail/:anAuthKey', 'authenticateUser'); 


// POST routes
$app->post('/run/', 'makeRun'); 
$app->post('/customer/', 'registerCustomer'); 


// PUT routes
$app->put('/run/', 'updateRun');


// DELETE routes
$app->delete('/run/', 'deleteRun'); 


function getRuns($customerId, $email, $authKey)
{		
	global $app;
	
	// TODO check credentials first
	
	// use slim to get a reference to the HTTP response object to be able to modify it 
	$response = $app->response();
	$response->header('Content-type', 'application/json');	
	
	// ajax restriction. Ajax by default can't make cross domain requests.
	// only needed for browser, not when run from phone
	// $response->headers->set('Access-Control-Allow-Origin', '*'); 
	$response->header('Access-Control-Allow-Origin', '*'); 
	
	$row = retrieveRuns($customerId, $email, $authKey);  // function in databaseFunctions.php return rows or null
		
	// echo out the Array of all rows represented in json format  [{},{}]
	// If no rows are retrieved then $row == null and an empty array is returned (NB. null is NOT returned!!!)
	echo json_encode($row);
}



function makeRun()
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
	$requestBody = json_decode($request->getBody());	
	
	// TODO authenticate user
	// $authenticateResult = isAuthKeyAndEmailOk($requestBody->email, $requestBody->authenticationKey); 
	// echo $authenticateResult["VALID"] . "\n";  // boolean
	
	$insertResult = false;
	
	// function in databaseFunctions.php return boolean
	$insertResult = createRun(	$requestBody->date, $requestBody->routeName, 
								$requestBody->km, $requestBody->seconds, 
								$requestBody->feeling, $requestBody->customerId );	
	
	// TODO check credentials first
	// if authenticate OK
	// if ($authenticateResult["VALID"] == "true")
	// {
		// // echo "inside auth key ok\n";
		
		// // check again that dates don't collide with other dates for this Customer
		// if (areDatesColliding($requestBody->customerId, $requestBody->startDate, $requestBody->returnDate) == false)
		// {
			// // No collision, OK to insert row
			// // function in databaseFunctions.php return boolean
			// $insertResult = createBooking($requestBody->carId, $requestBody->customerId, $requestBody->startDate, $requestBody->returnDate, $requestBody->hirePricePay);			
		// }		
	// }
	
	echo json_encode($insertResult);  // boolean
}



function updateRun()
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
	$requestBody = json_decode($request->getBody());	
	
	// TODO authenticate user
	
	// $authenticateResult = isAuthKeyAndEmailOk($requestBody->email, $requestBody->authenticationKey); 
	// echo $authenticateResult["VALID"] . "\n";  // boolean
	
	$updateResult = false;
	
	// function in databaseFunctions.php return boolean
	$updateResult = editRun($requestBody->runId, $requestBody->date, 
							$requestBody->routeName, $requestBody->km, 
							$requestBody->seconds, $requestBody->feeling);	
	
	// TODO check credentials first
	// if authenticate OK
	// if ($authenticateResult["VALID"] == "true")
	// {
		// // echo "inside auth key ok\n";
		
		// // check again that dates don't collide with other dates for this Customer
		// if (areDatesColliding($requestBody->customerId, $requestBody->startDate, $requestBody->returnDate) == false)
		// {
			// // No collision, OK to insert row
			// // function in databaseFunctions.php return boolean
			// $updateResult = createBooking($requestBody->carId, $requestBody->customerId, $requestBody->startDate, $requestBody->returnDate, $requestBody->hirePricePay);			
		// }		
	// }
	
	echo json_encode($updateResult);  // boolean	
}



function deleteRun()
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
	$requestBody = json_decode($request->getBody());	
	
	// TODO authenticate user
	
	// $authenticateResult = isAuthKeyAndEmailOk($requestBody->email, $requestBody->authenticationKey); 
	// echo $authenticateResult["VALID"] . "\n";  // boolean
	
	$deleteResult = false;
	
	// function in databaseFunctions.php return boolean
	$deleteResult = eraseRun($requestBody->runId);	
	
	// TODO check credentials first
	// if authenticate OK
	// if ($authenticateResult["VALID"] == "true")
	// {
		// // echo "inside auth key ok\n";
		
		// // check again that dates don't collide with other dates for this Customer
		// if (areDatesColliding($requestBody->customerId, $requestBody->startDate, $requestBody->returnDate) == false)
		// {
			// // No collision, OK to insert row
			// // function in databaseFunctions.php return boolean
			// $deleteResult = createBooking($requestBody->carId, $requestBody->customerId, $requestBody->startDate, $requestBody->returnDate, $requestBody->hirePricePay);			
		// }		
	// }
	
	echo json_encode($deleteResult);  // boolean	
}


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
	$requestBody = json_decode($request->getBody());
	
	$insertResult = false;
	$row = null;	
	
	// check if email (username) is unique
	$isEmailUnique = isTheEmailUnique($requestBody->email);
		
	// only if email is unique then generate salt and create hash value from (password + salt)
	if ($isEmailUnique)
	{	
		// echo "email unique\n";
		$password = $requestBody->password;
		// echo  "password: ".$password . "\n";
		$hashArray = doHashing($password);
				
		$salt = $hashArray[0];
		$hashValue = $hashArray[1];
		
		// function in databaseFunctions.php return boolean
		$insertResult = createCustomer($requestBody->email, $requestBody->name, $salt, $hashValue); 		
		
		if ($insertResult) // true comes back. successful insert
		{
			// get the row so auth key, customer id, (and email) can be saved on local phone	
			$row = retrieveCustomer($requestBody->email);			
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


$app->run();
exit();
?>