<?php
// Kirstine Nielsen
// MoreFitMoreFun web services


require 'Slim/Slim.php';
require_once("DAL/databaseFunctions.php");

$app = new Slim(); 

// GET routes
$app->get('/run/:aCustomerId/:anEmail/:anAuthKey/', 'getRuns');


// POST routes
$app->post('/run/', 'makeRun'); 



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
	// $authenticateResult = isAuthKeyAndEmailOk($q->email, $q->authenticationKey); 
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
		// if (areDatesColliding($q->customerId, $q->startDate, $q->returnDate) == false)
		// {
			// // No collision, OK to insert row
			// // function in databaseFunctions.php return boolean
			// $insertResult = createBooking($q->carId, $q->customerId, $q->startDate, $q->returnDate, $q->hirePricePay);			
		// }		
	// }
	
	echo json_encode($insertResult);  // boolean
}


$app->run();
exit();
?>