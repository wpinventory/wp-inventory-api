<?php

/*
 * @help: https://developer.wordpress.org/rest-api/extending-the-rest-api/adding-custom-endpoints/
 * @help: https://developer.wordpress.org/reference/functions/get_rest_url/
 */

// No direct access allowed.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WPInventoryAPI extends WPIMDB {
	private static $route = 'wp-inventory-manager/v1';
	public static $endpoints = [
		'create_item',
		'read_item',
		'update_item',
		'delete_item'
	];
	public static $item_id;

	public static function start() {
		add_action( 'rest_api_init', [ __CLASS__, 'wpim_api' ] );
		self::$endpoints;
		self::$item_id = WPIMDB::request( 'id' );
	}

	public static function wpim_api() {
		foreach ( self::$endpoints as $endpoint ) {
			$method = 'GET'; // Can be edited based on endpoint if necessary
			register_rest_route( self::$route, $endpoint, [
				'methods'  => $method,
				'callback' => [ __CLASS__, $endpoint ]
			] );
		}
	}

	public static function create_item() {

		//TODO:  Can't use 'update' function because it requires a user.  Will have to figure something else out. - action or filter on wpinventory.item.class.php line 465?

		// Only two set for now till we know this is the best architecture - I believe it is since we are dealing with URLs
		$data = [
			'inventory_name'   => WPIMDB::request( 'inventory_name' ),
			'inventory_number' => WPIMDB::request( 'inventory_number' )
		];


		if ( empty( $data['inventory_name'] ) && empty( $data['inventory_number'] ) ) {
			return self::_e( 'You must supply either the \'inventory_name\' or \'inventory_number\' field in order to create an item.' );
		}

		$new_item = new WPIMItem();
		$new_item->save( $data );

		//TODO:  Need to check if this is successfull or nsot and provide a good message

		return "Item has been added.";
	}

	public static function read_item() {
		return wpinventory_get_item( self::$item_id );
	}

	public static function update_item() {
		if ( ! (int) self::$item_id ) {
			return self::_e( 'No item id was supplied' );
		}

		//TODO:  I'm not quite sure how users are going to get data across without an extremely long URL but that may just be the case

		// Dummy data for now
		$data = [
			'inventory_number'      => 1234,
			'inventory_name'        => 'Some Really Good Name',
			'inventory_description' => 'Describe me, please.',
			'inventory_quantity'    => 150000
//			inventory_size = %s,
//			inventory_manufacturer = %s,
//			inventory_make = %s,
//			inventory_model = %s,
//			inventory_year = %s,
//			inventory_serial = %s,
//			inventory_fob = %s,
//			inventory_quantity_reserved = %d,
//			inventory_price = %f,
//			inventory_status = %d,
//			inventory_slug = %s,
//			inventory_sort_order = %d,
//			category_id = %d,
//			inventory_date_updated = %s,
		];

		$new_item = new WPIMItem();
		$new_item->update( self::$item_id, $data );

		//TODO:  Need to check if this is successfull or not and provide a good message

		return "Item has been updated.";
	}

	public static function delete_item() {
		if ( ! (int) self::$item_id ) {
			return self::_e( 'No item id was supplied' );
		}

		$delete_item = new WPIMItem();
		$delete_item->delete( self::$item_id );

		//TODO:  Again, check there was success and send the response

		return "Item was deleted";

	}

	public static function wpim_test_the_api( $endpoint = NULL ) {

		if ( ! $endpoint ) {
			return self::_e( 'You must provide a valid endpoint for the function parameter in order to get a returned value.' );
		}

		if ( ! in_array( $endpoint, self::$endpoints ) ) {
			return self::_e( 'The endpoint supplied is not a valid.  This means it was not found / does not exist.' );
		}

		$ch = curl_init( get_rest_url() . 'wp-inventory-manager/v1/' . $endpoint );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, TRUE );

		// Store the data:
		$json = curl_exec( $ch );
		curl_close( $ch );

		// Decode JSON response:
		$api_result = json_decode( $json, TRUE );

		echo '<pre>';
		var_dump( $api_result );

	}
}

WPInventoryAPI::start();