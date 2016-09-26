<?php
// Kirstine Nielsen
// MoreFitMoreFun web services


require 'Slim/Slim.php';
require_once("DAL/databaseFunctions.php");

$app = new Slim(); 

///////////////////////////////////////////// Start routes /////////////////////////////////////////////


// GET routes
$app->get('/run/:aCustomerId/:aName/:anAuthKey/', 'getRuns');
$app->get('/route/:aCustomerId/:aName/:anAuthKey/', 'getRoutes');
$app->get('/authenticate/:aName/:anAuthKey/', 'authenticateUser');
$app->get('/customer/:aName/:anAuthKey/', 'getCustomer');
$app->get('/passwordValidation/:aPassword/:aName/:anAuthKey/', 'verifyPasswordIsCorrect');
$app->get('/login/:aName/:aPassword/', 'loginCustomer');


// POST routes
$app->post('/run/', 'makeRun');
$app->post('/customer/', 'registerCustomer');
$app->post('/route/', 'makeRoute'); 


// PUT routes
$app->put('/run/', 'updateRun');
$app->put('/customer/:customerId/', 'updateCustomer');
$app->put('/route/', 'updateRoute');


// DELETE routes
$app->delete('/run/', 'deleteRun'); 
$app->delete('/route/', 'deleteRoute'); 


///////////////////////////////////////////// End routes /////////////////////////////////////////////


function getRuns($customerId, $name, $authKey)
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
	$resultArr = isAuthKeyAndNameOk($name, $authKey);
	
	if ($resultArr["VALID"] == "true")
	{
		$row = retrieveRuns($customerId);  // function in databaseFunctions.php return rows or null
	}
	
	// echo out the Array of all rows represented in json format  [{},{}] (empty array if no rows were found)
	// null is returned if authentication is false
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
	
	$insertResult = null;	
	
	// authenticate user
	$resultArr = isAuthKeyAndNameOk($requestBody->name, $requestBody->authenticationKey);	
	
	if ($resultArr["VALID"] == "true")
	{
		// function in databaseFunctions.php return boolean
		$insertResult = createRun(	$requestBody->date, 
									$requestBody->distance, 
									$requestBody->seconds, 
									$requestBody->feeling, 
									null, 
									$requestBody->runCustomerId,
									$requestBody->runRouteId );	
	}
		
	echo json_encode($insertResult);  // boolean or null if authentication is not ok
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
	$updateResult = editRun($requestBody->runId, 
							$requestBody->date, 
							$requestBody->distance, 
							$requestBody->seconds, 
							$requestBody->feeling, 
							null,
							$requestBody->runCustomerId,
							$requestBody->runRouteId );	
	
	
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
	$deleteResult = eraseRun($requestBody->runId, $requestBody->runCustomerId);	
	
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




/**
retrieve all routes for specific customer
*/
function getRoutes($customerId, $name, $authKey)
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
	
	$row = retrieveRoutes($customerId);  // function in databaseFunctions.php return rows or null
		
	// echo out the Array of all rows represented in json format  [{},{}]
	// If no rows are retrieved then $row == null and an empty array is returned (NB. null is NOT returned!!!)
	echo json_encode($row);
}



function makeRoute()
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
	
	// TODO check that routeKm is not empty
	
	
	// function in databaseFunctions.php return boolean
	$insertResult = createRoute($requestBody->routeName, $requestBody->routeDistance, $requestBody->customerId);	
	
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



// PUT - update a Route
function updateRoute()
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
	$updateResult = editRoute($requestBody->routeId, $requestBody->routeName, $requestBody->routeDistance);	
	
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
} // end function



function deleteRoute()
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
	
	$editRunResult = false;
	$deleteResult = false;
	
	// find all runs that are attached to the routeId (return array)
	$rowsRuns = retrieveRunsWithAttachedRoute($requestBody->customerId, $requestBody->routeId);  // function in databaseFunctions.php return rows or null
	
	$amountRunRecords = count($rowsRuns);
	// echo "length of rowsRuns: " . $amountRunRecords . "\n";
	
	// check if array contains any runs to be updated
	if ($amountRunRecords > 0)
	{
		// get the route record so you can get the route distance
		$rowRoute = retrieveRouteByRouteId($requestBody->routeId);
		// echo "length of rowRoute: " . count($rowRoute) . "\n";
				
		// update all these runs so fldDistance is set to the route distance and fldRunRouteId is set to null
		for ($i = 0; $i < $amountRunRecords; $i++)
		{
			// echo "loop\n";
			$editRunResult = editRun(	$rowsRuns[$i]->fldRunId, 
										$rowsRuns[$i]->fldDate, 
										$rowRoute[0]->fldRouteDistance,
										$rowsRuns[$i]->fldSeconds, 
										$rowsRuns[$i]->fldFeeling, 
										null, 
										$rowsRuns[$i]->fldRunCustomerId, 
										null);	
			
			if ($editRunResult == false)
			{
				break;
			}
		}
	}
	else
	{
		$editRunResult = true;
	}
	
	// if updating runs that was attached to route is successful then proceed with deleting the route
	if ($editRunResult == true)
	{
		// function in databaseFunctions.php return boolean
		$deleteResult = eraseRoute($requestBody->routeId, $requestBody->customerId);		
	}		
	
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



function loginCustomer($name, $password)
{		
	global $app;
		
	// use slim to get a reference to the HTTP response object to be able to modify it 
	$response = $app->response();
	$response->header('Content-type', 'application/json');	
	
	// ajax restriction. Ajax by default can't make cross domain requests.
	// only needed for browser, not when run from phone
	// $response->headers->set('Access-Control-Allow-Origin', '*'); 
	$response->header('Access-Control-Allow-Origin', '*'); 
		
	// boolean flag
	$passwordMatchOk = false;
	
	// try to fetch a record with the name from db 
	$row = retrieveCustomer($name) ;  // function in databaseFunctions.php return row or false...?
	
	// check if record exists
	if ($row != false && $row != null)
	{
		// case sensitive match
		if ( (strcmp($row->fldName, $name) == 0) )  // case sensitive match ok
		{			
			// check if hash(password entered + salt) matches authKey for the record
			$passwordMatchOk = doPasswordsMatch($password, $name, $row);
			
			if ($passwordMatchOk == false)
			{
				// wrong password
				$row = null;
			}			
		}
		else  // name does not match when case sensitive
		{			
			$row = null;
		}		
	}	
	else  // no record exist that matches with name
	{
		$row = null;
	}
	
	// echo out the Array of all rows represented in json format  [{},{}]
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
		// get customer
		// function in databaseFunctions.php
		$row = retrieveCustomer($a_name);  // function in databaseFunctions.php
		
		$arePasswordsMatching = doPasswordsMatch($a_password, $a_name, $row);
		
		if ($arePasswordsMatching)
		{
			$resultArray = array("VALID"=>"true");
		}			
	}	
	echo json_encode($resultArray);	
}



// return boolean
function doPasswordsMatch($a_password, $a_name, $a_row)
{
	// return boolean value
	$passwordMatch = false;
	
	// check if hash(password + salt) will be == authenticationKey in row
	$hashArray = doHashing($a_password, $a_row->fldSalt);
	
	// $salt = $hashArray[0];
	$hashValue = $hashArray[1];
	
	if ($hashValue == $a_row->fldAuthenticationKey)
	{
		// echo "password is correct";
		// update boolean
		$passwordMatch = true;	
	}
	
	return $passwordMatch;
}


$app->run();
exit();
?>