<?php
/**
 * Configure API on first install from app admin installation
 * 
 */
class Fera_Ai_Installation extends Fera_Ai {
    public function __construct() 
    {
        add_action( 'init', array($this,'autoconfigureAction') );
    }

    /**
    * If api keys are empty in plugin settings auto integrate app keys   
     */
    public function autoconfigureAction()
    {
        try {
            $params = $_GET;
            $response = "";

            if(!$this->fera_check_wc_plugin()) {
                $response .= "✘ Woocommerce is not activated so you need to activate woocommerce before using fera app. \\n";
            }
            
            if ($params['latest_ver'] != $this->fera_ai_get_version()) {
                $response .= "✘ You may be running an out-dated version of Feracommerce extension. ".
                             "Please download the latest files and try again. ".
                             "It looks like you're running v ". $this->fera_ai_get_version() .
                             " and the latest is v". $params['latest_ver'] .".\\n";
            } else {
                $response .= "✓ You appear to be running the latest version of the extension (v". $params['latest_ver'] .")\\n";
            }
            if (get_option('fera_ai_publickey') != "") {
                $response .= "* This account has already been auto-configured and cannot be auto-configured again. ".
                             "If you want to update the API credentials please go to the System settings and enter ".
                             "in the API credentials manually.\\n";
            } else {
               update_option( 'fera_ai_secretkey', $params['sk'] );
               update_option( 'fera_ai_publickey', $params['pk'] );
                if ($params['api_url']) {
                    update_option( 'fera_ai_api_url', $params['api_url'] );
                }
                if ($params['js_url']) {
                  update_option( 'fera_ai_js_url', $params['js_url'] );
                }
                $response .= "✓ Automatic configuration was successful. You may now close this window.\\n";
            }
            $response .= "";
            
        } catch (Exception $response ) {
            $response = get_error_message($response);
        } 
          //$this->fera_ai_log($response);
        ?>
        <script>
          alert("<?php echo $response; ?> "); 
          window.close();
        </script>
        <?php
      return;
    }
}        
    
$fera_ai_installation = new Fera_Ai_Installation;
