<?php
// Kirstine Nielsen
// MoreFitMoreFun web services


require 'Slim/Slim.php';
require_once("DAL/databaseFunctions.php");

$app = new Slim(); 

// GET routes
$app->get('/run/:aCustomerId/:aName/:anAuthKey/', 'getRuns');  // fixed
$app->get('/authenticate/:aName/:anAuthKey/', 'authenticateUser');  // fixed 
$app->get('/customer/:aName/:anAuthKey/', 'getCustomer');
$app->get('/passwordValidation/:aPassword/:aName/:anAuthKey/', 'verifyPasswordIsCorrect');


// POST routes
$app->post('/run/', 'makeRun'); 
$app->post('/customer/', 'registerCustomer');  // fixed 


// PUT routes
$app->put('/run/', 'updateRun');
$app->put('/customer/:customerId/', 'updateCustomer');


// DELETE routes
$app->delete('/run/', 'deleteRun'); 


function getRuns($customerId, $name, $authKey)
{		
	global $app;
	
	// TODO check credentials first be sure to check for case sensitiveness
	
	// use slim to get a reference to the HTTP response object to be able to modify it 
	$response = $app->response();
	$response->header('Content-type', 'application/json');	
	
	// ajax restriction. Ajax by default can't make cross domain requests.
	// only needed for browser, not when run from phone
	// $response->headers->set('Access-Control-Allow-Origin', '*'); 
	$response->header('Access-Control-Allow-Origin', '*'); 
	
	$row = retrieveRuns($customerId);  // function in databaseFunctions.php return rows or null
		
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
	// $authenticateResult = isAuthKeyAndNameOk($requestBody->email, $requestBody->authenticationKey); 
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
	
	// $authenticateResult = isAuthKeyAndNameOk($requestBody->email, $requestBody->authenticationKey); 
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
	
	// $authenticateResult = isAuthKeyAndNameOk($requestBody->email, $requestBody->authenticationKey); 
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



// GET Customer
function getCustomer($a_name, $an_authKey) 
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
	$authenticateResult = isAuthKeyAndNameOk($a_name, $an_authKey);	
	
	// if authenticate OK
	if ($authenticateResult["VALID"] == "true")
	{
		// get the customer
		$row = retrieveCustomer($a_name);  // function in databaseFunctions.php
	}
	
	// echo out row represented in json format {  }
	echo json_encode($row);
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
	$requestBody = json_decode($request->getBody());
	
	// authenticate user
	$authenticateResult = isAuthKeyAndNameOk($requestBody->name, $requestBody->authenticationKey); 
		
	// echo $authenticateResult["VALID"] . "\n";  // boolean
	
	$saveResult = false;
	$row = null;
	
	// if authenticate OK
	if ($authenticateResult["VALID"] == "true")
	{
		// echo "inside auth key ok\n";
		
		// update row process	
		// if password == '' password changes should NOT happen
		if ($requestBody->password == '')
		{			
			// echo "password is empty\n";
			// function in databaseFunctions.php return boolean
			$saveResult = saveUpdatesCustomerEmail($a_customerId, $requestBody->email); 			
		}
		else  // password change should happen
		{
			// new password means new auth key. 
			$password = $requestBody->password;
			// echo  "password: ".$password . "\n";
			$hashArray = doHashing($password);
					
			$salt = $hashArray[0];
			$hashValue = $hashArray[1];
			
			// function in databaseFunctions.php return boolean
			$saveResult = saveUpdatesCustomer($a_customerId, $requestBody->email, $salt, $hashValue); 
		}		 
	}
	
	// get record for customer
	if ($saveResult)
	{
		$row = retrieveCustomer($requestBody->name);
	}
	
	// echo out row represented in json format {  }
	echo json_encode($row);
} // end function


// authenticate if name and auth key are correct
function authenticateUser($a_name, $an_authKey)
{ 			
	global $app;
	
	// use slim to get a reference to the HTTP response object to be able to modify it 
	$response = $app->response();
	$response->header('Content-type', 'application/json');	
	
	// ajax restriction. Ajax by default can't make cross domain requests.
	// only needed for browser, not when run from phone
	// $response->headers->set('Access-Control-Allow-Origin', '*'); 
	$response->header('Access-Control-Allow-Origin', '*'); 
		
	$resultArr = isAuthKeyAndNameOk($a_name, $an_authKey);
	echo json_encode($resultArr);
} // end function



function isAuthKeyAndNameOk($a_name, $an_authKey)
{
	$row = retrieveCustomerBasedOnNameAndAuthKey($a_name, $an_authKey);	
		
	// set $resultArray
	$resultArray = array("VALID"=>"false");	
	
	// check if a row was returned
	if ($row != null)
	{
		// echo "row returned\n";
		
		// check if auth key matches and name matches (case sensitive)
		if ( (strcmp($row->fldAuthenticationKey, $an_authKey) == 0) && (strcmp($row->fldName, $a_name) == 0) )
		{
			// auth key and name from db match auth key and name from local storage on phone
			// update $resultArray
			$resultArray = array("VALID"=>"true");			
		}
	}
	return $resultArray; 	
}



// POST create a customer
// name must be unique. 
// does not even accept 'anton' if 'ANTON' is in database
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
	
	// check if Name (username) is unique
	$isNameUnique = isTheNameUnique($requestBody->name);
		
	// only if Name is unique then generate salt and create hash value from (password + salt)
	if ($isNameUnique)
	{	
		// echo "Name unique\n";
		$password = $requestBody->password;
		// echo  "password: ".$password . "\n";
		$hashArray = doHashing($password);
				
		$salt = $hashArray[0];
		$hashValue = $hashArray[1];
		
		// function in databaseFunctions.php return boolean
		$insertResult = createCustomer($requestBody->name, $requestBody->email, $salt, $hashValue); 		
		
		if ($insertResult) // true comes back. successful insertion
		{
			// get the row so auth key, customer id, (and name) can be saved on local phone	
			$row = retrieveCustomer($requestBody->name);			
		}
	}
	
	// echo out row represented in json format {  }
	echo json_encode($row);
}


// check if Name is unique and return boolean 
function isTheNameUnique($aName)
{
	$isNameUnique = false;
	
	// function in databaseFunctions.php return rows (zero or one)
	$row = retrieveCustomer($aName);  
	
	// check if any row was returned
	if ($row == null)
	{		
		// Name is unique
		$isNameUnique = true;		
	}
	return $isNameUnique;
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



function verifyPasswordIsCorrect($a_password, $a_name, $an_authKey)
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
	$authenticateResult = isAuthKeyAndNameOk($a_name, $an_authKey); 
	// echo $authenticateResult["VALID"] . "\n";  // boolean	
	
	// set $resultArray
	$resultArray = array("VALID"=>"false");
	
	// if authenticate OK
	if ($authenticateResult["VALID"] == "true")
	{
		// echo "inside auth key ok";
		// get customer
		// function in databaseFunctions.php
		$row = retrieveCustomer($a_name);  // function in databaseFunctions.php
		
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