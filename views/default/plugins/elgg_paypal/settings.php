<?php

/**
 * Live/Sandbox
 */
echo '<label>' . elgg_echo('paypal:settings:sandbox') . '</label><br>';
echo elgg_view('input/dropdown', array(
	'name' => 'params[sandbox]',
	'value' => ((int)$vars['entity']->sandbox === 0) ? 0 : 1,
	'options_values' => array(
		0 => elgg_echo('paypal:settings:sandbox:0'),
		1 => elgg_echo('paypal:settings:sandbox:1')
	)
));
echo elgg_view('output/longtext', array(
	'value' => elgg_echo('paypal:settings:sandbox:help'),
	'class' => 'elgg-subtext'
));
echo '<br>';


/**
 *  Paypal Username
 */
echo '<label>' . elgg_echo('paypal:settings:paypal_api_username') . '</label>';
echo elgg_view('input/text', array(
	'name' => 'params[paypal_api_username]',
	'value' => $vars['entity']->paypal_api_username
));
echo elgg_view('output/longtext', array(
	'value' => elgg_echo('paypal:settings:paypal_api_username:help'),
	'class' => 'elgg-subtext'
));
echo '<br>';


/**
 *  Paypal Password
 */
echo '<label>' . elgg_echo('paypal:settings:paypal_api_password') . '</label>';
echo elgg_view('input/text', array(
	'name' => 'params[paypal_api_password]',
	'value' => $vars['entity']->paypal_api_password
));
echo elgg_view('output/longtext', array(
	'value' => elgg_echo('paypal:settings:paypal_api_password:help'),
	'class' => 'elgg-subtext'
));
echo '<br>';


/**
 *  Paypal Signature
 */
echo '<label>' . elgg_echo('paypal:settings:paypal_api_signature') . '</label>';
echo elgg_view('input/text', array(
	'name' => 'params[paypal_api_signature]',
	'value' => $vars['entity']->paypal_api_signature
));
echo elgg_view('output/longtext', array(
	'value' => elgg_echo('paypal:settings:paypal_api_signature:help'),
	'class' => 'elgg-subtext'
));
echo '<br>';



/**
 *  Currency Type
 */
echo '<label>' . elgg_echo('paypal:settings:currency') . '</label><br>';
echo elgg_view('input/dropdown', array(
	'name' => 'params[currency]',
	'value' => $vars['entity']->currency ? $vars['entity']->currency : 'USD',
	'options_values' => array(
		'AUD' => 'AUD',
		'BRL' => 'BRL',
		'CAD' => 'CAD',
		'CHF' => 'CHF',
		'CZK' => 'CZK',
		'DKK' => 'DKK',
		'EUR' => 'EUR',
		'GBP' => 'GBP',
		'HKD' => 'HKD',
		'HUF' => 'HUF',
		'ILS' => 'ILS',
		'JPY' => 'JPY',
		'MYR' => 'MYR',
		'MXN' => 'MXN',
		'NOK' => 'NOK',
		'NZD' => 'NZD',
		'PHP' => 'PHP',
		'PLN' => 'PLN',
		'SEK' => 'SEK',
		'SGD' => 'SGD',
		'THB' => 'THB',
		'TRY' => 'TRY',
		'TWD' => 'TWD',
		'USD' => 'USD'
	)
));
echo elgg_view('output/longtext', array(
	'value' => elgg_echo('paypal:settings:currency:help'),
	'class' => 'elgg-subtext'
));
echo '<br>';
