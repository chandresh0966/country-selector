<?php
	// if uninstall.php is not called by WordPress, die
	if (!defined('WP_UNINSTALL_PLUGIN')) {
	    die;
	}

	// Delete a custom options
	delete_option('cs_db_version');

	// drop a custom database table
	global $wpdb;
	$table_cs_countries = $wpdb->prefix . 'cs_countries';
	$table_cs_country_redirect = $wpdb->prefix . 'cs_country_redirect';

	$wpdb->query("DROP TABLE IF EXISTS {$table_cs_countries}");
	$wpdb->query("DROP TABLE IF EXISTS {$table_cs_country_redirect}");