<?php

class Fera_Ai_Admin {
   
    public function __construct() 
    {
        add_action('admin_menu', array ($this, 'fera_ai_create_menu' ));
        add_action('admin_init', array ($this, 'fera_ai_register_settings' ));
    }

    public function fera_ai_create_menu() {
        //create new top-level menu
        add_menu_page('Fera ai Settings', 'Fera ai', 'administrator', 'fera-ai', array ($this, 'fera_ai_settings_page' ) , plugins_url('/images/fera_ai_icon.png', __FILE__), 30 );
    }

    public function fera_ai_register_settings() {
        //register our settings
        register_setting( 'fera-ai-settings-group', 'fera_ai_publickey' );
        register_setting( 'fera-ai-settings-group', 'fera_ai_secretkey' );
        register_setting( 'fera-ai-settings-group', 'fera_ai_version' );
        register_setting( 'fera-ai-settings-group', 'fera_ai_api_url' );
        register_setting( 'fera-ai-settings-group', 'fera_ai_js_url' );
    }

    public function fera_ai_settings_page() {
        global $fera_ai;
        include_once dirname( __FILE__ ) . '/admin-setting-page.php';
    }
}

$fera_ai_admin = new Fera_Ai_Admin();
	