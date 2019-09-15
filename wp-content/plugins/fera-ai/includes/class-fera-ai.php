<?php
/**
 * Fera_ai setup 
 * 
 */

class Fera_Ai {
    
    public function __construct() {
        $this->fera_ai_includes();
    }
    /**
     * Include all required files
     * 
     */
    public function fera_ai_includes()
    {
        // to get shoppers, products, cart and order info as a json string 
        include_once Fera_Ai_PATH . '/includes/class-fera-ai-data.php';
        if ( is_admin() ) 
        {
            // admin settings
            include_once Fera_Ai_PATH . '/includes/class-fera-ai-admin.php';
        }
        else 
        {
            // integration footer code
            include_once Fera_Ai_PATH . '/includes/class-fera-ai-integration.php';
            
            if(strpos($_SERVER['REQUEST_URI'], 'fera_ai/installation/autoconfigure') !== false)
            {   
                // auto configure installation used first time app is installed.
                include_once dirname( __FILE__ ) . '/class-fera-ai-installation.php';
            }
        }
    }

    /**
     * check if woocommerce is active
     * 
     */ 
    
    public function fera_check_wc_plugin()
    {
        if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) 
        {
            return true;
        }

        return false;
    }
    
    /**
     * Fera Ai public key either from admin settings or the environment files
     * @return string
     */
    public function fera_ai_getPublicKey()
    {
        if (isset($_SERVER['FERA_AI_PUBLIC_KEY'])) {
            return $_SERVER['FERA_AI_PUBLIC_KEY'];
        }
        return get_option('fera_ai_publickey');
    }

    /**
     * Fera Ai secret (private) key, either from the environment fiels or the admin settings
     * @return string
     */
    public function fera_ai_getSecretKey()
    {
        if (isset($_SERVER['FERA_AI_SECRET_KEY'])) {
            return $_SERVER['FERA_AI_SECRET_KEY'];
        }
        return get_option('fera_ai_secretkey');
    }

    /**
     * The URL path to the API (https). For example: https://api.fera.ai/api/v1
     * @return string
     */
    
    public function fera_ai_getApiUrl()
    {
        if (isset($_SERVER['FERA_AI_API_URL'])) {
            return $_SERVER['FERA_AI_API_URL'];
        }
        $urlFromConfig = get_option('fera_ai_api_url');
        if ($urlFromConfig) {
            return $urlFromConfig;
        }
        return "https://app.fera.ai/api/v2";
    }

    /**
     * True if the current Fera Ai configuration is setup to work properly
     * @return boolean false if it is not ready for use
     */
    public function fera_ai_isConfigured()
    {
        $publicKey = $this->fera_ai_getPublicKey();
        $secretKey = $this->fera_ai_getSecretKey();
        return !empty($publicKey) && !empty($secretKey);
    }

    /**
     * get plugin version 
     * @return numbers - 1.0
     */

    public function fera_ai_get_version() {
        if( !function_exists('get_plugin_data') ){
            require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
        }
        $plugin_data = get_plugin_data( Fera_Ai_PATH."/fera-ai.php" );
        return $plugin_data["Version"];
    }

    /**
     * The URL to the javascript file on the Fera CDN. For example: https://cdn.fera.ai/js/fera.js
     * @return string
     */
    public function fera_ai_getJsUrl()
    {
        if (isset($_SERVER['FERA_AI_JS_URL'])) {
            return $_SERVER['FERA_AI_JS_URL'];
        }

        $urlFromConfig = get_option('fera_ai_js_url');
        if ($urlFromConfig) {
            return $urlFromConfig;
        }

        return "https://cdn.fera.ai/js/fera.js";
    }
   
    /**
     * logs
     */
    public function fera_ai_log($msg)
    {
        $pluginlog = Fera_Ai_PATH.'/debug.log';
        error_log($msg, 3, $pluginlog);

        return;
    }
}

$fera_ai = new Fera_Ai();
