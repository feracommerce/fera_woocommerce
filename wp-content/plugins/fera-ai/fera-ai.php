<?php
/**
* Plugin Name: Fera Commerce
* Plugin URI: https://www.fera.ai/
* Description: Fera.ai - Real Time Personalization for Ecommerce. Boost conversions by changing what shoppers see in real-time
* Version: 1.0
* Author: Fera 
* Author URI: https://www.fera.ai/
*
**/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

define( 'Fera_Ai_PATH', dirname( __FILE__ ) );
define( 'Fera_Ai_Base_FILE', plugin_basename( __FILE__ ) );

// Include the main Fera_Ai class.
if ( ! class_exists( 'Fera_Ai' ) ) {
    include_once dirname( __FILE__ ) . '/includes/class-fera-ai.php';
}
