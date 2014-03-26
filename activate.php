<?php

if (!get_subtype_id('object', 'paypal_transaction_history')) {
	add_subtype('object', 'paypal_transaction_history');
}

// set default settings
$currency = elgg_get_plugin_setting('currency', 'elgg_paypal');

if (!$currency) {
	elgg_set_plugin_setting('currency', 'USD', 'elgg_paypal');
}