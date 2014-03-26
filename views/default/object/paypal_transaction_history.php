<?php

$history = $vars['entity'];

if ($history->payment_date) {
	$date = $history->payment_date;
}
else {
	$date = date(DATE_ATOM, $history->time_created);
}

$amount = $history->amount ? $history->amount : $history->payment_gross;
if (!$amount) {
	$amount = $history->mc_gross;
}

if ($history->txn_id) {
	echo $date . '&nbsp;&nbsp;&sdot;&nbsp;&nbsp;';
	
	echo '<strong>' . elgg_echo('paypal:history:amount') . ':</strong> ' . $amount;

	echo '&nbsp;&nbsp;&sdot;&nbsp;&nbsp;';

	echo '<strong>' . elgg_echo('paypal:history:transaction_id') . ':</strong> ' . $history->txn_id;

	echo '<br>';
}

echo '<strong>' . elgg_echo('paypal:history:type') . ':</strong> ' . $history->txn_type . '<br>';

if ($history->entity_guid && $entity = get_entity($history->entity_guid)) {
	$link = elgg_view('output/url', array('text' => $entity->getURL(), 'href' => $entity->getURL()));
	echo '<strong>' . elgg_echo('paypal:history:product') . ':</strong> ' . $link;
}