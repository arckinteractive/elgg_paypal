<?php

$english = array(
	'paypal:settings:sandbox' => "Paypal mode:",
	'paypal:settings:sandbox:0' => "Live",
	'paypal:settings:sandbox:1' => "Sandbox",
	'paypal:settings:sandbox:help' => "If set to Sandbox mode no real transactions can occur, this is a testing mode for use with fake accounts set up at https://developer.paypal.com",
	'paypal:settings:paypal_email' => "Paypal Email Address",
	'paypal:settings:paypal_email:help' => "This email corresponds with the account receiving membership funds",
	'paypal:settings:paypal_api_username' => "Paypal API Username",
	'paypal:settings:paypal_api_username:help' => "The username supplied by paypal on the API credentials screen",
	'paypal:settings:paypal_api_password' => "Paypal API Password",
	'paypal:settings:paypal_api_password:help' => "The password supplied by paypal on the API credentials screen",
	'paypal:settings:paypal_api_signature' => "Paypal API Signature",
	'paypal:settings:paypal_api_signature:help' => "The signature supplied by paypal on the API credentials screen",
	'paypal:settings:currency' => "Currency",
	'paypal:settings:currency:help' => "The type of currency you wish to receive.  Note that some currencies can only be used with accounts set up in those currencies",
	'paypal:transaction:history' => "Transaction History",
	'paypal:transaction:history:none' => "There is no history to display",
	'paypal:invalid:permissions' => "Invaid Permissions",
	'paypal:error:response' => "An error has occurred...
Type: %s,
Message: %s,
Detail: %s
",
	
	'paypal:history:amount' => "Amount",
	'paypal:history:transaction_id' => "Transaction ID",
	'paypal:history:type' => "Type",
	'paypal:history:product' => "Product",
);
		
add_translation("en", $english);