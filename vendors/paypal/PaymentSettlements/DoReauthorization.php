<?php
require_once('../PPBootStrap.php');
/*
 * Authorize a payment. 
 */

/*
 * (Required) Amount to reauthorize.
 */
$amount = new BasicAmountType($_REQUEST['currencyCode'], $_REQUEST['amt']);

/*
 * (Required) Value of a previously authorized transaction identification number returned by PayPal.
 */
$doReAuthRequest = new DoReauthorizationRequestType($_REQUEST['authID'], $amount);
$doReAuthReq = new DoReauthorizationReq();
$doReAuthReq->DoReauthorizationRequest =$doReAuthRequest;

/*
 * 	 ## Creating service wrapper object
Creating service wrapper object to make API call and loading
configuration file for your credentials and endpoint
*/
$paypalService = new PayPalAPIInterfaceServiceService();
try {
	/* wrap API method calls on the service object with a try catch */
	$doReAuthResponse = $paypalService->DoReauthorization($doReAuthReq);
} catch (Exception $ex) {
	include_once("../Error.php");
	exit;
}
if(isset($doReAuthResponse)) {
	echo "<pre>";
	print_r($doReAuthResponse);
	echo "</pre>";
}
require_once '../Response.php';
