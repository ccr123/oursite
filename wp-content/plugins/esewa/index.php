<?php
/*
* Plugin Name: eSewa - Nepal's First Payment Gateway
* Description: eSewa official plugin for WooCommerce
* Version: 0.1
* Text Domain: esewa-woocommerce
*/

if( ! defined( 'ABSPATH' )) exit;

/* 
*
* Plugin File Path. 
*
*/
if ( ! defined( 'ESEWA_WC_PLUGIN_FILE' ))
	define( 'ESEWA_WC_PLUGIN_FILE', __FILE__ );

/*
*
* Include Esewa_WooCommerce_Init class. 
*
*/
if ( ! class_exists( 'Esewa_WooCommerce_Init' ))
	include_once dirname( __FILE__ ) . '/method/esewa-woocommerce-init.php';

add_action( 'plugins_loaded', array( 'Esewa_WooCommerce_Init', 'get_instance' ) );

/* 
*
* Include file displaying order-details on different payment status. 
*
*/
include_once dirname( __FILE__ ) . '/method/inc/order/order-detail.php';

/*
*
* Update Thankyou page with a message. 
*
*/
add_filter('woocommerce_thankyou_order_received_text', 'esewa_woocommerce_change_order_received_text', 10, 2 );
function esewa_woocommerce_change_order_received_text( $txnID, $order ) {
   	$txnID = '';
	if( ! empty( $order->get_transaction_id() ) ) {
	   $txnID = '<b> Transaction ID: '.$order->get_transaction_id().'<b/>';
	}
   return $txnID;
}
