<?php
/**
 * This class is used to integrate footer code
 * 
 */
class Fera_Ai_Integration extends Fera_Ai {
    
    public function __construct() 
    {
        if ( !is_admin() && $this->fera_check_wc_plugin() ) 
        {
            add_action( 'wp_footer', array($this,'fera_ai_footer_script'), 20 );

            add_action( 'woocommerce_before_add_to_cart_quantity', array($this,'fera_ai_pushVariation') );
            
            add_action( 'woocommerce_thankyou', array($this,'fera_ai_pushOrder'), 30, 1);
        } 
    }

    /**
     *  
     * 
     */
    function fera_ai_data() {
    	$fera_ai_data = new Fera_Ai_Data();
        return $fera_ai_data;
    }

    /**
     * include js footer code in footer
     * 
     */
    function fera_ai_footer_script() 
    {
        include_once dirname( __FILE__ ) . '/footer-script.php';
        return;
    }

    /**
     * push order in thank you page
     * 
     */
    public function fera_ai_pushOrder($order_id) 
    {
        echo '<script>
        window.fera = window.fera || [];
        window.fera.push("configure", {
            store_pk: "'. $this->fera_ai_getPublicKey().'", 
            api_url: "'. $this->fera_ai_getApiUrl().'"
        });
        window.fera.push("order",  '. $this->fera_ai_data()->fera_ai_get_order_info($order_id).' );
        </script>';
        
        return;
    }

    /**
     * push variantid in product page
     * 
     */
    function fera_ai_pushVariation() {
        global $product;

        if ( $product->is_type('variable') ) {
            ?>
                <script>
                    jQuery(document).ready(function($) {
                        $('input.variation_id').change( function() {
                            if( '' != $('input.variation_id').val() ) {
                                var variantId = $('input.variation_id').val();
                                //console.log(variantId);
                                window.fera = window.fera || [];
                                window.fera.push('setVariantId',variantId);
                                window.fera.refreshContent();
                            }
                        });
                    });
                </script>
            <?php
        }    
    }    

}
$fera_ai_integration = new Fera_Ai_Integration;
