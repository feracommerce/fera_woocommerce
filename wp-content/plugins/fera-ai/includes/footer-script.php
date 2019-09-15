<!--// BEGIN Fera.ai Integration Code //-->
<script>
    window.feraStandaloneMode = true;
    window.fera = window.fera || [];
    
    window.fera.push('configure', {
            store_pk: "<?php echo $this->fera_ai_getPublicKey(); ?>", 
            api_url: "<?php echo $this->fera_ai_getApiUrl(); ?>"
    });
    window.fera.push('loadPlatformAdapter', "woocommerce");
<?php if (get_current_user_id()): ?>
    window.fera.push('setShopper', <?php echo $this->fera_ai_data()->fera_ai_getShopperJson(); ?>);
<?php endif; ?>
    window.fera.push("setCart", <?php echo $this->fera_ai_data()->fera_ai_cartJson(); ?>);

<?php if (is_product()): ?>
    window.fera.push("setProduct", <?php echo $this->fera_ai_data()->fera_ai_getProductJson(); ?>);
<?php endif; ?>
</script>
<script async type="application/javascript" src="<?php echo $this->fera_ai_getJsUrl(); ?>"></script>
<!--// END Fera.ai Integration Code //-->
