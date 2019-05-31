<?php

if (! defined( 'ABSPATH' ) ) {
	exit;
}

function samedaycourier_create_db() {
	global $wpdb;

	$charset_collate = $wpdb->get_charset_collate();

	$awbTable =  $wpdb->prefix . 'sameday_awb';
	$pickup_point = $wpdb->prefix . 'sameday_pickup_point';
	$service = $wpdb->prefix . 'sameday_service';
	$packageTable = $wpdb->prefix . 'sameday_package';
	$lockerTable = $wpdb->prefix . 'sameday_locker';

	$createAwbTable = "CREATE TABLE IF NOT EXISTS $awbTable (
		id INT(11) NOT NULL AUTO_INCREMENT,
        order_id INT(11) NOT NULL,
        awb_number VARCHAR(255),
        parcels TEXT,
        awb_cost DOUBLE(10, 2),
        PRIMARY KEY (id),
		UNIQUE KEY id (id)
	) $charset_collate;";

	$createPickUpPointTable = "CREATE TABLE IF NOT EXISTS $pickup_point (
		id INT(11) NOT NULL AUTO_INCREMENT,
        sameday_id INT(11) NOT NULL,
        sameday_alias VARCHAR(255),
        is_testing TINYINT(1),
        city VARCHAR(255),
        county VARCHAR(255),
        address VARCHAR(255),
        contactPersons TEXT,
        default_pickup_point TINYINT(1),
        PRIMARY KEY (id),
		UNIQUE KEY id (id)
	) $charset_collate;";

	$createServiceTable = "CREATE TABLE IF NOT EXISTS $service (
		id INT(11) NOT NULL AUTO_INCREMENT,
        sameday_id INT(11) NOT NULL,
        sameday_name VARCHAR(255),
        is_testing TINYINT(1),
        name VARCHAR(255),
        price DOUBLE(10, 2),
        price_free DOUBLE(10, 2),
        status INT(11),
        working_days TEXT,
        PRIMARY KEY (id),
		UNIQUE KEY id (id)
	) $charset_collate;";

	$createPackageTable = "CREATE TABLE IF NOT EXISTS $packageTable (
		order_id INT(11) NOT NULL,
        awb_parcel VARCHAR(255),
        summary TEXT,
        history TEXT,
        expedition_status TEXT,
        sync TEXT,
        PRIMARY KEY (order_id, awb_parcel)
	) $charset_collate;";

	$createLockerTable = "CREATE TABLE IF NOT EXISTS $lockerTable (
		id INT(11) NOT NULL AUTO_INCREMENT,
        locker_id INT(11),
        name VARCHAR(255),
        county VARCHAR(255),
        city VARCHAR(255),
        address VARCHAR(255),
        lat VARCHAR(255),
        lng VARCHAR(255),
        postal_code VARCHAR(255),
        boxes TEXT,
        is_testing TINYINT(1),
        PRIMARY KEY (id)
	) $charset_collate;";

	$tablesToCreate = array(
		$createAwbTable, $createPickUpPointTable, $createServiceTable, $createPackageTable, $createLockerTable
	);

	$tablesToAlter = array();

	$servicesRows = $wpdb->get_row("SELECT * FROM $service LIMIT 1");

//	if (! isset($servicesRows->sameday_code)) {
//		$alterServiceTable = "ALTER TABLE $service ADD `sameday_code` VARCHAR(255) AFTER `sameday_id` NOT NULL ;";
//
//		$tablesToAlter[] = $alterServiceTable;
//	}

	foreach ($tablesToCreate as $table) {
		dbDelta( $table );
	}

//	if (! empty($tablesToAlter)) {
//		foreach ($tablesToAlter as $table) {
//			dbDelta( $table );
//		}
//	}
}

//public function ensureSamedayServiceCodeColumn()
//{
//	$query = 'SELECT * FROM ' . DB_PREFIX . "sameday_service LIMIT 1";
//	$row = $this->db->query($query)->row;
//
//	if (array_key_exists('sameday_code', $row)) {
//		return;
//	}
//
//	$this->db->query('alter table '. DB_PREFIX .'sameday_service add sameday_code VARCHAR(255) default \'\' not null');
//}

