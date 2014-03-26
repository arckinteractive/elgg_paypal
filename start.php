<?php

elgg_register_event_handler('init', 'system', 'elgg_paypal_init');



function elgg_paypal_init() {
	elgg_register_library('paypal', dirname(__FILE__) . '/vendors/paypal/PPBootStrap.php');

	elgg_register_page_handler('paypal', 'paypal_pagehandler');
	
	elgg_register_plugin_hook_handler('register', 'menu:user_hover', 'paypal_user_hover');
	
	// Register ipn as public in walled gardens
	elgg_register_plugin_hook_handler('public_pages', 'walled_garden', 'paypal_public_pages');
}



function paypal_pagehandler($page) {
	switch ($page[0]) {
		case 'history':
			gatekeeper();
			$username = urldecode($page[1]);
			$user = get_user_by_username($username);
			if (!$user || !$user->canEdit()) {
				forward('', '404');
			}
			
			$title = elgg_echo('paypal:transaction:history');
			
			$content = elgg_view('paypal/transaction_history', array('user' => $user));
			
			$layout = elgg_view_layout('content', array(
				'title' => $title,
				'content' => $content,
				'filter' => false
			));
			
			echo elgg_view_page($title, $layout);
			break;
		case 'ipn':
			paypal_process_ipn();
			break;
	}
	
	return false;
}


/**
 * gets the paypal service handler with local config
 * @return \PayPalAPIInterfaceServiceService
 */
function paypal_get_service() {
	$paypalService = new PayPalAPIInterfaceServiceService();
	
	// API settings
	$sandbox = elgg_get_plugin_setting('sandbox', 'elgg_paypal');
	$paypalService->config['mode'] = $sandbox ? 'sandbox' : 'live';
	$paypalService->config['acct1.UserName'] = elgg_get_plugin_setting('paypal_api_username', 'elgg_paypal');
	$paypalService->config['acct1.Password'] = elgg_get_plugin_setting('paypal_api_password', 'elgg_paypal');
	$paypalService->config['acct1.Signature'] = elgg_get_plugin_setting('paypal_api_signature', 'elgg_paypal');

	return $paypalService;
}



function paypal_get_ipn_url() {
	return elgg_get_site_url() . 'paypal/ipn';
}



function paypal_get_ec_checkout_url($token) {
	$sandbox = elgg_get_plugin_setting('sandbox', 'elgg_paypal');
	
	$url = "https://www.paypal.com/webscr?cmd=_express-checkout&token={$token}";
	if ($sandbox) {
		$url = "https://www.sandbox.paypal.com/webscr?cmd=_express-checkout&token={$token}";
	}
	
	return $url;
}



function paypal_user_hover($hook, $type, $return, $params) {
	if (!$params['entity']->canEdit()) {
		return $return;
	}
	
	$item = new ElggMenuItem('paypal', elgg_echo('paypal:transaction:history'), 'paypal/history/' . urlencode($params['entity']->username));
	$item->setSection('action');
	$return[] = $item;
	
	return $return;
}


function paypal_process_ipn() {
	$sandbox = elgg_get_plugin_setting('sandbox', 'elgg_paypal');
	error_log('IPN triggered: ' . print_r($_POST,1));
	$txn = (object) $_POST;

    // This IPN listener reads IPN msgs sent from PayPal, verifies the msg, and processes
    // the data by posting the IPN message fields and values to the browser screen.

    // read the IPN msg from PayPal and add 'cmd' for your verification request
    $req = 'cmd=_notify-validate';

    // append the IPN msg, in NVP format, to your verification request
    foreach ($_POST as $key => $value) {
        $value = urlencode(stripslashes($value));
        $req .= "&$key=$value";
    }

    // set up the headers for your verification request
    // POST your verification requests to PayPal (here, the Sandbox)
    $header  = "POST /cgi-bin/webscr HTTP/1.0\r\n";
    
    if ($sandbox) {
        $header .= "Host: www.sandbox.paypal.com:443\r\n";
        $fsock = 'ssl://www.sandbox.paypal.com';
    }
    else {
        $header .= "Host: ipnpb.paypal.com:443\r\n";      // endpoint for Live apps
        $fsock = 'ssl://ipnpb.paypal.com';
    }

    $header .= "Content-Type: application/x-www-form-urlencoded\r\n";
    $header .= "Content-Length: " . strlen($req) . "\r\n\r\n";

    // open the socket
    $fp = fsockopen ($fsock, 443, $errno, $errstr, 30);


    if (!$fp) {
        // HTTP error
        error_log("Paypal IPN ERROR:: HTTP socket error! Unable to open URL");
    }
    else {
        // POST verification request
        fputs ($fp, $header . $req);


        while (!feof($fp)) {
            $res = fgets ($fp, 1024);
            if (strcmp ($res, "VERIFIED") == 0) {
					// trigger plugin hook for individual plugins to have an option to save the ipn first
					$result = elgg_trigger_plugin_hook('paypal', 'ipn_log', array('txn' => $txn), false);
					
					if (!$result) {
						// no plugins logged the transaction, log it to the site
						// 
						paypal_log_ipn(elgg_get_site_entity(), $txn);
					}
            }
            else if (strcmp ($res, "INVALID") == 0) {
 
                // invalid - do nothing for now
                // IPN invalid, log for manual investigation
                paypal_log_ipn(elgg_get_site_entity(), $txn);
            }
        }

        fclose ($fp);
    }
    exit;
}


function paypal_log_ipn($owner, $txn) {
	if (!elgg_instanceof($owner)) {
		$owner = elgg_get_site_entity();
	}
	
	$ia = elgg_set_ignore_access(true);
	
	$log = new ElggObject();
	$log->subtype = 'paypal_transaction_history';
	$log->access_id = ACCESS_PRIVATE;
	$log->owner_guid = $owner->guid;
	$log->container_guid = $owner->guid;
	$log->title = '';
	$log->description = serialize($txn);
	$log->save();
	
	// save individual elements as metadata or getting/sorting
	$transaction = get_object_vars($txn);
	
	foreach ($transaction as $key => $val) {
		$log->$key = $val;
	}
	
	elgg_set_ignore_access($ia);
	
	// allow other plugins to modify/save custom info
	$log = elgg_trigger_plugin_hook('paypal', 'ipn_save', array('txn' => $txn, 'owner' => $owner), $log);
	
	return $log;
}



function paypal_public_pages($hook, $type, $return, $params) {
	$pages = array('paypal/ipn');
	return array_merge($pages, $return);
}