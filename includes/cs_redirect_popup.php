<form id="country-selector-popup" class="mfp-hide white-popup-block">
    <h1><?php echo esc_html(get_option('cs_popup_title', 'Country Selector')); ?></h1>
    <p><?php echo esc_html(stripslashes(get_option('cs_popup_content', 'It looks like you\'re browsing from a different location. Please select your current country/region to continue.'))); ?></p>
    <div class="form-fields">
        <div class="form-field cs-country-wrapper">
            <select name="cs_country" class="cs_country">
                <option value="">-- <?php esc_html_e( 'Select Country', 'country-selector' ) ?> --</option>
                <?php foreach ($countries as $key => $country) { ?>
                    <option value="<?php echo esc_html($country->shortname); ?>" data-redirect-url="<?php echo esc_url($country->redirect_url); ?>"><?php echo esc_html($country->country_name); ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="form-field cs-button-wrapper">
            <button type="submit" class="cs-button"><?php esc_html_e( 'Save', 'country-selector' ) ?></button>
        </div>
    </div>
    <div class="cs-loader" style="display: none;">
        <img src="<?php echo esc_url(CNTSEL_PLUGIN_DIR_URL.'assets/images/loader.gif'); ?>">
        <span class="loader-text"><?php esc_html_e( 'Redirecting, Please wait...', 'country-selector' ) ?></span>
    </div>
</form>