<div class="wrap">
<h1>Fera.ai</h1>
<h3>API Keys</h3>
<form method="post" action="options.php">
    <?php settings_fields( 'fera-ai-settings-group' ); ?>
    <?php do_settings_sections( 'fera-ai-settings-group' ); ?>
    <table class="form-table">
        <tr valign="top">
        <th scope="row">Public Key</th>
        <td><input type="text" name="fera_ai_publickey" value="<?php echo esc_attr( get_option('fera_ai_publickey') ); ?>" /></td>
        </tr>
         
        <tr valign="top">
        <th scope="row">Secret Key</th>
        <td><input type="text" name="fera_ai_secretkey" value="<?php echo esc_attr( get_option('fera_ai_secretkey') ); ?>" />
        <p class="description" id="tagline-description">
          You can find your API keys at</p>
          <p class="description" id="tagline-description">
          https://app.fera.ai/store/settings?tab=api</p>
          <input type="hidden" name="fera_ai_api_url" value="<?php echo $fera_ai->fera_ai_getApiUrl(); ?>" />
          <input type="hidden" name="fera_ai_js_url" value="<?php echo $fera_ai->fera_ai_getJsUrl(); ?>" />
        </td>
        </tr>
        
    </table>
    
    <?php submit_button(); ?>
</form>
</div>