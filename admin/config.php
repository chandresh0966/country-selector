<?php
	add_action( 'admin_enqueue_scripts', 'country_selector_enqueue_admin_script' );
	function country_selector_enqueue_admin_script( $hook ) {
	    wp_enqueue_style( 'cntsel-admin', CNTSEL_PLUGIN_DIR_URL . 'admin/styles/cs-admin.css', array(), CNTSEL_VERSION );
	    wp_enqueue_style( 'cntsel-chosen', CNTSEL_PLUGIN_DIR_URL . 'common/styles/chosen.min.css', array(), CNTSEL_VERSION );

	    wp_enqueue_script( 'cntsel-repeater-script', CNTSEL_PLUGIN_DIR_URL . 'admin/scripts/jquery.repeater.min.js', array(), CNTSEL_VERSION );
	    wp_enqueue_script( 'cntsel-script', CNTSEL_PLUGIN_DIR_URL . 'admin/scripts/cs-admin.js', array('jquery'), CNTSEL_VERSION );
	    wp_enqueue_script( 'cntsel-chosen', CNTSEL_PLUGIN_DIR_URL . 'common/scripts/chosen.jquery.min.js', array(), CNTSEL_VERSION );

	    $custom_vars = array('ajax_url' => admin_url( 'admin-ajax.php' ));
	    wp_localize_script( 'cntsel-script', 'custom_vars', $custom_vars );
	}

	add_action( 'admin_menu', 'country_selector_add_settings_page' );
	function country_selector_add_settings_page() {
		add_menu_page( 'Country Selector', 'Country Selector', 'manage_options', 'country-selector-config', 'country_selector_config', CNTSEL_PLUGIN_DIR_URL.'admin/assets/images/country-selector.png' );
	}

	function country_selector_config() {
		$countries = countrySelectorGetCountryList();
		$excludeCountries = countrySelectorGetExistingRedirectCountries();
		if(!empty($excludeCountries)) {
			$excludeCountries = array_column($excludeCountries, 'country_id');
		}
	?>
	    <div class="wrap">
	        <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
	        <?php if($success_messages = countrySelectorGetMessages('success')) {
	        	foreach ($success_messages as $success_message) { ?>
	        		<div class="notice notice-success is-dismissible">
	        			<p><?php echo esc_html($success_message); ?></p>
        			</div>
        	<?php } } ?>
	        <?php if($error_messages = countrySelectorGetMessages('errors')) {
	        	foreach ($error_messages as $error_message) { ?>
	        		<div class="notice notice-error is-dismissible">
	        			<p><?php echo esc_html($error_message); ?></p>
        			</div>
        	<?php } } ?>
        	<form method="post" action="<?php echo esc_html( admin_url( 'admin-post.php' ) ); ?>" class="repeater">
        		<table class="form-table" role="presentation">
        			<tbody>
        				<tr>
		        			<th scope="row">
		        				<label for="cs_popup_enable"><?php esc_html_e( 'Enable Popup?', 'country-selector' ); ?></label>
		        			</th>
		        			<td>
		        				<label class="switch">
		        				  	<input type="checkbox" name="cs_popup_enable" id="cs_popup_enable" value="yes" <?php if(get_option('cs_popup_enable', '') == 'yes') echo 'checked'; ?>>
		        				  	<span class="slider round"></span>
		        				</label>
		        			</td>
        				</tr>
        				<tr>
		        			<th scope="row">
		        				<label for="cs_popup_title"><?php esc_html_e( 'Popup Title', 'country-selector' ); ?><span class="required">*</span></label>
		        			</th>
		        			<td>
		        				<input name="cs_popup_title" type="text" id="cs_popup_title" value="<?php echo get_option('cs_popup_title', ''); ?>" class="regular-text" required="">
		        			</td>
        				</tr>
        				<tr>
		        			<th scope="row">
		        				<label for="cs_popup_content"><?php esc_html_e( 'Popup Content', 'country-selector' ); ?></label>
		        			</th>
		        			<td>
		        				<textarea name="cs_popup_content" id="cs_popup_content" cols="50" rows="5"><?php echo stripcslashes(get_option('cs_popup_content', '')); ?></textarea>
		        			</td>
        				</tr>
        				<tr>
		        			<th scope="row">
		        				<label for="cs_popup_button_text"><?php esc_html_e( 'Popup Button Text', 'country-selector' ); ?></label>
		        			</th>
		        			<td>
		        				<input name="cs_popup_button_text" type="text" id="cs_popup_button_text" value="<?php esc_html_e( 'Save', 'country-selector' ) ?>" class="regular-text" disabled="">
		        				<p class="information pro-only-features"><?php esc_html_e( 'Available in PRO version only', 'country-selector' ); ?></p>
		        			</td>
        				</tr>
	    				<tr>
		        			<th scope="row">
		        				<label for="cs_popup_cookie_lifetime"><?php esc_html_e( 'Popup Cookie Lifetime (Seconds)', 'country-selector' ); ?></label>
		        			</th>
		        			<td>
		        				<input name="cs_popup_cookie_lifetime" type="number" id="cs_popup_cookie_lifetime" value="86400" class="regular-text" disabled="">
		        				<p class="information pro-only-features"><?php esc_html_e( 'Available in PRO version only', 'country-selector' ); ?></p>
		        			</td>
	    				</tr>
        			</tbody>
        		</table>
        		<table class="cs-redirect-tables headers">
        			<thead>
        				<tr>
        					<th class="width45"><?php esc_html_e( 'Country Code', 'country-selector' ) ?></th>
        					<th class="width45"><?php esc_html_e( 'URL to redirect', 'country-selector' ) ?></th>
        					<th class="width10"><?php esc_html_e( 'Action', 'country-selector' ) ?></th>
        				</tr>
        			</thead>
        		</table>
        		<?php if($existingRedirects = countrySelectorGetExistingRedirects()) { ?>
	        		<div class="edit-repeater">
						<table data-repeater-list="edit_country_redirect_urls" class="add-country-redirect-urls cs-redirect-tables">
							<tbody>
        						<?php foreach ($existingRedirects as $existingRedirect) { ?>
									<tr data-repeater-item="<?php echo esc_html($existingRedirect->id); ?>">
					        			<td class="width45">
					        				<select name="cs_popup_country_code" class="cs_popup_country_code" style="width: 100%;">
					        					<option value="">-- <?php esc_html_e( 'Select Country', 'country-selector' ) ?> --</option>
					        					<?php foreach ($countries as $key => $country) { ?>
					        						<option value="<?php echo esc_html($country->id); ?>" <?php echo ($existingRedirect->country_id == $country->id) ? esc_html('selected') : esc_html(''); ?>><?php echo esc_html($country->name); ?></option>
					        					<?php } ?>
					        				</select>
					        			</td>
					        			<td class="width45">
					        				<input name="cs_popup_redirect_url" type="text" class="cs_popup_redirect_url regular-text" value="<?php echo esc_url($existingRedirect->redirect_url); ?>" placeholder="<?php esc_html_e( 'Enter URL', 'country-selector' ) ?>">
					        			</td>
					        			<td class="width10">
					        				<input type="hidden" name="redirect_id" value="<?php echo $existingRedirect->id; ?>">
					        				<input data-repeater-delete="<?php echo esc_html($existingRedirect->id); ?>" type="button" value="<?php esc_html_e( 'Delete', 'country-selector' ) ?>" class="button button-primary delete-cr" />
					        			</td>
									</tr>
								<?php } ?>
							</tbody>
						</table>
	        		</div>
    			<?php } ?>
        		<div class="add-repeater">
					<table data-repeater-list="country_redirect_urls" class="country-redirect-urls cs-redirect-tables">
						<tbody>
							<tr data-repeater-item>
			        			<td class="width45">
			        				<select name="cs_popup_country_code" class="cs_popup_country_code" style="width: 100%;">
			        					<option value="">-- <?php esc_html_e( 'Select Country', 'country-selector' ) ?> --</option>
			        					<?php foreach ($countries as $key => $country) { ?>
			        						<option value="<?php echo esc_html($country->id); ?>" <?php if(in_array($country->id, $excludeCountries)) echo esc_html("disabled"); ?>><?php echo esc_html($country->name); ?></option>
			        					<?php } ?>
			        				</select>
			        			</td>
			        			<td class="width45">
			        				<input name="cs_popup_redirect_url" type="text" class="cs_popup_redirect_url regular-text" value="" placeholder="<?php esc_html_e( 'Enter URL', 'country-selector' ) ?>">
			        			</td>
			        			<td class="width10">
			        				<input data-repeater-delete type="button" value="<?php esc_html_e( 'Delete', 'country-selector' ) ?>" class="button button-primary" />
			        			</td>
							</tr>	    							
						</tbody>
					</table>
					<p class="cs-add-more">
						<input data-repeater-create type="button" value="<?php esc_html_e( 'Add more', 'country-selector' ) ?>" class="button button-primary" />
					</p>
        		</div>
				<input type="hidden" name="action" value="save_cs_settings">
    			<?php
    				wp_nonce_field( 'cs-settings-save', 'cs_settings' );
    				submit_button();
				?>
	        </form>
	    </div><!-- .wrap -->
    <?php }

    function remove_country_redirect_url() {
    	$cr_id = isset($_POST['cr_id']) ? sanitize_text_field($_POST['cr_id']) : '';

    	$response = array();
    	if(empty($cr_id)) {
    		$response['success'] = false;
    		$response['message'] = esc_html__('Invalid request.', 'country-selector');
    		wp_send_json($response);
    	}
    	$cr_info = country_selector_get_country_redirect($cr_id);
    	if(empty($cr_info)) {
    		$response['success'] = false;
    		$response['message'] = esc_html__('Invalid country redirect id passed.', 'country-selector');
    		wp_send_json($response);
    	}

    	$deleted = country_selector_delete_country_redirect($cr_id);
    	if($deleted) {
    		$response['success'] = true;
    		$response['country_id'] = $cr_info->country_id;
    		$response['message'] = esc_html__('Record deleted successfully.', 'country-selector');
    	} else {
    		$response['success'] = false;
    		$response['message'] = esc_html__('Unable to delete record. Please try again.', 'country-selector');
    	}
		wp_send_json($response);
    }
    add_action('wp_ajax_delete_country_redirect_url', 'remove_country_redirect_url');