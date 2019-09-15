<?php
/**
 * Fera_ai data - shopper, product, order 
 * 
 */

class Fera_Ai_Data {
    
    /**
     * @return json - shopper info as a json string.
     */
    
    function fera_ai_getShopperJson() 
    {
        if(get_current_user_id()) {
            $current_user = wp_get_current_user();
            $shopperData = [
            'customer_id' => get_current_user_id(),
            'email' => $current_user->user_email,       
            ];
        }
        return json_encode($shopperData);   
    }

    /**
     * @return json - The contents of the product as a json string.
     */

    function fera_ai_getProductJson() 
    {
        $productId = wc_get_product()->get_id();
        $product = wc_get_product( $productId );

        $image_id  = $product->get_image_id();
        $image_url = wp_get_attachment_image_url( $image_id, 'full' );

        $stock_status = $product->get_stock_status();
        if($stock_status == "instock") {
            $in_stock = true;
        } else {
            $in_stock = false;
        }

        $productData = [
            "id" =>          $productId, // String
            "name" =>        $product->get_name(), // String
            "price" =>       $product->get_price(), // Float
            "status" =>      $product->get_status(), // (Optional) String
        
            "created_at" =>  (new DateTime($product->get_date_created()))->format(DateTime::ATOM), // (Optional) String (ISO 8601 format DateTime) 
            "modified_at" => (new DateTime($product->get_date_modified()))->format(DateTime::ATOM), // (Optional) String (ISO 8601 format DateTime) 
        
            "stock" =>       $product->get_stock_quantity(), // (Optional) Integer, If null assumed to be infinite.
            "in_stock" =>    $in_stock, // (Optional) Boolean
            "manages_stock" =>    $product->get_manage_stock(), // (Optional) Boolean
            "url" =>        get_permalink( $productId ), // String
            "thumbnail_url" => $image_url, // String
            
            "needs_shipping" =>   $product->get_shipping_class_id() != 'virtual', // (Optional) Boolean
            "hidden" =>           $product->is_visible() == '1', // (Optional) Boolean
            "tags" =>         [],
            "variants" =>     [], // (Optional) Array<Variant>: Variants that are applicable to this product.
        
            "platform_data" => [
                "sku" => $product->get_sku(), 
                "type" => $product->get_type(),
                "regular_price" => $product->get_regular_price(),
            ]       
        ];

        //get product tag name
        $producttag = get_the_terms( $productId, 'product_tag' );
        if($producttag) {
            foreach($producttag as $tag) {
                $productData['tags'][] = $tag->name;
            }
        }    
    
        //product variants
        if($product->is_type( 'variable' )) {
            $productData['variants'] = $this->fera_ai_getProductVaraition($productId);
        }

       return json_encode($productData);
    }

    /**
     * @return array - Product variation for variable product types as a json string.
     */

    function fera_ai_getProductVaraition($productId)
    {
        global $fera_ai;

        $product = wc_get_product( $productId );
        $available_variations = $product->get_available_variations();

        foreach ($available_variations as $variation) {
        $product_variation_id =  wc_get_product($variation['variation_id']); 
        
        $image_id  = $product_variation_id->get_image_id();
        $image_url = wp_get_attachment_image_url( $image_id, 'full' );

        $stock_status = $product_variation_id->get_stock_status();
        if($stock_status == "instock") {
            $in_stock = true;
        } else {
            $in_stock = false;
        }

            $variant = [
                "id" =>          $product_variation_id->get_id(),
                "name" =>        $product_variation_id->get_name(),
                "status" =>      $product_variation_id->get_status(),
                "created_at" =>  (new DateTime($product_variation_id->get_date_created()))->format(DateTime::ATOM),
                "modified_at" => (new DateTime($product_variation_id->get_date_modified()))->format(DateTime::ATOM),
                "stock" =>       $product_variation_id->get_stock_quantity(),
                "in_stock" =>    $in_stock,
                "manages_stock" =>    $product_variation_id->get_manage_stock(), // (Optional) Boolean
                "price" =>       $product_variation_id->get_price(),
                "url" =>         get_permalink( $product_variation_id->get_id() ), // String
                "thumbnail_url" => $image_url, // String
                     "platform_data" => [ 
                     "sku" => $product_variation_id->get_sku()
                    ]   
            ]; 

            foreach ($product_variation_id->get_variation_attributes() as $attr_name => $attr_value) {
            $variant[ $attr_name ] = $attr_value;
            }

            $productData['variants'][] = $variant;
        }
    
        return $productData['variants'];
    }
    
    /**
     * @return json - get order info, JSONify it and return it
     */

    function fera_ai_get_order_info($order_id) 
    {
        global $fera_ai;
        $order = wc_get_order( $order_id );
        $customerId = $order->get_customer_id();

        $orderData = array(
            'id' => $order_id,
            //'number' => $order->getIncrementId(),
            'total' => $order->get_total(),
            'total_usd' => $order->get_total(),
            'created_at' => (new DateTime($order->get_date_created()))->format(DateTime::ATOM),
            'modified_at'   => (new DateTime($order->get_date_modified()))->format(DateTime::ATOM),
            'source_name'   => 'web',
            'line_items'   => [],
            'customer_id'   => $customerId,
            'shopper'       => $order->get_address(),
        );

        $items = $order->get_items();
            foreach ( $items as $item_id => $item_data ) {

                $productitem = $item_data->get_product();
                
                // get main product id for variable product types
                if($productitem->get_type() == "variation") {
                    $variation_id = wc_get_product($productitem->get_id());
                    $productId = $variation_id->get_parent_id();
                    
                } 
                else {
                    // simple and other products
                    $productId = $productitem->get_id();
                }

                $product = wc_get_product( $productId );   
                
                $pr_line_items = [
                'name'  => $product->get_name(),
                'quantity' => $item_data->get_quantity(),
                'total' => $item_data->get_total(),
                'total_usd'=> $item_data->get_total(),
                'product_id'=>  $productId, 
            ];
        $orderData['line_items'][] = $pr_line_items;  
        }
    
        return json_encode($orderData);
    }

    /**
     * @return json - The contents of the cart as a json string.
     */

    function fera_ai_cartJson() 
    {
        $cartData = array(
            'total' => WC()->cart->subtotal,
            'currency' => 'USD',
            'total_discount'=> WC()->cart->total_discount,
            'items' => [],
        );

        $cart = WC()->cart->get_cart();

        foreach( $cart as $cart_item ) {
            $product = wc_get_product( $cart_item['product_id'] );
 
            // Now you have access to (see above)...
            $pr_line_items = [
                'name' => $cart_item['data']->get_title(),
                'price'=>  $cart_item['data']->get_price(),
                'total' =>  $cart_item['line_total'],
                'quantity' =>  $cart_item['quantity'],
                'product_id' => $cart_item['data']->get_id(),
            ];
        $cartData['items'][] = $pr_line_items;
        }
        return json_encode($cartData);
    }

}
