<?php

$user = $vars['user'];

echo elgg_view_entity($user, array('full_view' => true));


// display transaction list
$title = elgg_echo('paypal:transaction:history');

$options = array(
	'type' => 'object',
	'subtype' => 'paypal_transaction_history',
	'owner_guid' => $user->guid,
	'count' => true
);

$count = elgg_get_entities($options);

if (!$count) {
	$body = elgg_echo('paypal:transaction:history:none');
}
else {
	unset($options['count']);
	$body = elgg_list_entities($options);
}

echo elgg_view_module('info', $title, $body);
