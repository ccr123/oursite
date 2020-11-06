<?php
/*
Plugin Name: OpenPos Lite Version
Plugin URI: http://openswatch.com
Description: Quick POS system for woocommerce. This is Lite Version
Author: anhvnit@gmail.com
Author URI: http://openswatch.com/
Version: 2.1
WC requires at least: 2.6
WC tested up to: 3.8.1
Text Domain: openpos
License: GPL version 2 or later - http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*/


if(!function_exists('is_plugin_active'))
{
    include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
}

if(!is_plugin_active( 'woocommerce-openpos/woocommerce-openpos.php' ))
{
    define('OPENPOS_DIR',plugin_dir_path(__FILE__));
    define('OPENPOS_URL',plugins_url('openpos'));
    global $OPENPOS_SETTING;
    global $OPENPOS_CORE;
    require(OPENPOS_DIR.'vendor/autoload.php');
    require_once( OPENPOS_DIR.'lib/class-tgm-plugin-activation.php' );
    require_once( OPENPOS_DIR.'includes/admin/Settings.php' );
    require_once( OPENPOS_DIR.'lib/class-op-woo.php' );
    require_once( OPENPOS_DIR.'lib/class-op-woo-cart.php' );
    require_once( OPENPOS_DIR.'lib/class-op-woo-order.php' );
    require_once( OPENPOS_DIR.'lib/class-op-session.php' );
    require_once( OPENPOS_DIR.'lib/class-op-register.php' );
    require_once( OPENPOS_DIR.'lib/class-op-warehouse.php' );
    require_once( OPENPOS_DIR.'lib/class-op-stock.php' );
    require_once( OPENPOS_DIR.'lib/class-op-exchange.php' ); 
    require_once( OPENPOS_DIR.'lib/class-op-report.php' ); 
    require_once( OPENPOS_DIR.'includes/Core.php' );
    require_once( OPENPOS_DIR.'includes/admin/Admin.php' );
    global $barcode_generator;
    global $op_session;
    global $op_warehouse;
    global $op_register;
    global $op_stock;
    global $op_woo;
    global $op_woo_cart;
    global $op_woo_order;
    global $op_exchange;
    global $op_report;
    //check woocommerce active
    if(is_plugin_active( 'woocommerce/woocommerce.php' ))
    {
        $barcode_generator = new \Picqer\Barcode\BarcodeGeneratorPNG();
        $op_session = new OP_Session();
        $op_woo = new OP_Woo();
        $op_woo->init();
        $op_woo_cart = new OP_Woo_Cart();
        $op_woo_order = new OP_Woo_Order();
        $op_warehouse = new OP_Warehouse();
        $op_register = new OP_Register();
        
        $op_stock = new OP_Stock();
        $op_exchange = new OP_Exchange();
        $op_report = new OP_Report();

        if(class_exists('TGM_Plugin_Activation'))
        {
            add_action( 'tgmpa_register', 'openpos_register_required_plugins' );


            function openpos_register_required_plugins() {
                /*
                * Array of plugin arrays. Required keys are name and slug.
                * If the source is NOT from the .org repo, then source is also required.
                */
                $plugins = array(

                    array(
                        'name'      => 'WooCommerce',
                        'slug'      => 'woocommerce',
                        'required'  => true,
                        //'version'            => '3.3.5',
                        'force_activation'   => true,
                        'force_deactivation' => true,
                    )

                );

                $config = array(
                    'id'           => 'openpos',                 // Unique ID for hashing notices for multiple instances of TGMPA.
                    'default_path' => '',                      // Default absolute path to bundled plugins.
                    'menu'         => 'tgmpa-install-plugins', // Menu slug.
                    'parent_slug'  => 'plugins.php',            // Parent menu slug.
                    'capability'   => 'manage_options',    // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
                    'has_notices'  => true,                    // Show admin notices or not.
                    'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
                    'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
                    'is_automatic' => false,                   // Automatically activate plugins after installation or not.
                    'message'      => '',                      // Message to output right before the plugins table.
                );

                tgmpa( $plugins, $config );
            }
        }

        $OPENPOS_SETTING = new Openpos_Settings();
        $OPENPOS_CORE = new Openpos_Core();
        $OPENPOS_CORE->init();
        $tmp = new Openpos_Admin();
        $tmp->init();

        if(!class_exists('Openpos_Front'))
        {

            if(!class_exists('WC_Discounts'))
            {
                require( dirname(OPENPOS_DIR).'/woocommerce/includes/class-wc-discounts.php' );
            }
            require( OPENPOS_DIR.'lib/class-op-discounts.php' );

            require_once( OPENPOS_DIR.'includes/front/Front.php' );
        }
        $tmp = new Openpos_Front();
        $tmp->initScripts();
        //register action on active plugin
        if(!function_exists('openpos_activate'))
        {
            function openpos_activate() {
                update_option('_openpos_product_version_0',time());
                // Activation code here...
            }
        }
        load_plugin_textdomain( 'openpos', null,  'openpos/languages' );
        register_activation_hook( __FILE__, 'openpos_activate' );
    }


}
