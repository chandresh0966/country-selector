<?php
    // Get messages from session for various type such as error, success
    function countrySelectorGetMessages($type = 'success') {
        $messages = array();
        if(isset($_SESSION[$type])) {
            $messages = array_map('sanitize_text_field', $_SESSION[$type]);
            unset($_SESSION[$type]);
        }
        return $messages;
    }
    function countrySelectorGetCountryList() {
    	global $wpdb;
    	return $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}cs_countries", OBJECT );
    }
    function countrySelectorGetAllCountryRedirects() {
        global $wpdb;
        return $wpdb->get_results( "SELECT c.id AS country_id, c.shortname, c.name AS country_name, cr.redirect_url FROM `{$wpdb->prefix}cs_countries` AS c LEFT JOIN `{$wpdb->prefix}cs_country_redirect` AS cr ON c.id=cr.country_id ORDER BY c.name", OBJECT );
    }
    function countrySelectorGetExistingRedirects() {
	    global $wpdb;
	    return $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}cs_country_redirect", OBJECT );		
    }

    function countrySelectorGetExistingRedirectCountries() {
        global $wpdb;
        return $wpdb->get_results( "SELECT country_id FROM {$wpdb->prefix}cs_country_redirect", OBJECT );
    }

    add_action( 'admin_post_save_cs_settings', 'save_country_selector_settings' );
    function save_country_selector_settings() {
        global $wpdb;

        $errors = array();
        if( isset($_POST['cs_settings']) && !wp_verify_nonce($_REQUEST['cs_settings'], 'cs-settings-save')){
        	$_SESSION['errors'] = array(__("Unauthorized request.", 'cs-settings-save'));
        	wp_redirect(admin_url('admin.php?page=cs-config'));
        	die();
        }
        $popup_enable = isset($_POST['cs_popup_enable']) ? sanitize_text_field($_POST['cs_popup_enable']) : '';
        $popup_title = isset($_POST['cs_popup_title']) ? sanitize_text_field($_POST['cs_popup_title']) : '';
        $popup_content = isset($_POST['cs_popup_content']) ? sanitize_text_field($_POST['cs_popup_content']) : '';
        $edit_country_redirect_urls = isset($_POST['edit_country_redirect_urls']) ? map_deep( $_POST['edit_country_redirect_urls'], 'sanitize_text_field' ) : '';
        $country_redirect_urls = isset($_POST['country_redirect_urls']) ? map_deep($_POST['country_redirect_urls'], 'sanitize_text_field' ) : '';

        if(empty($popup_title)) {
        	$errors[] = __("Popup title is required.", 'cs-settings-save');
        }
        if(!empty($errors)) {
        	$_SESSION['errors'] = $errors;
        	wp_redirect(admin_url('admin.php?page=cs-config'));
        	die();
        }

        update_option( 'cs_popup_enable', $popup_enable);
        update_option( 'cs_popup_title', $popup_title);
        update_option( 'cs_popup_content', $popup_content);

        if(!empty($edit_country_redirect_urls)) {
        	foreach ($edit_country_redirect_urls as $edit_country_redirect_url) {
                if(empty($edit_country_redirect_url['cs_popup_country_code']) || empty($edit_country_redirect_url['cs_popup_redirect_url'])) continue;
        		$wpdb->update( $wpdb->prefix . 'cs_country_redirect',
        			array( 'country_id' => $edit_country_redirect_url['cs_popup_country_code'], 'redirect_url' => esc_url($edit_country_redirect_url['cs_popup_redirect_url'])),
        			array( 'id' => $edit_country_redirect_url['redirect_id']),
        			array( '%d','%s' ),
        			array('%d')
        		);
        	}
        }

        if(!empty($country_redirect_urls)) {
        	foreach ($country_redirect_urls as $country_redirect_url) {
                if(empty($country_redirect_url['cs_popup_country_code']) || empty($country_redirect_url['cs_popup_redirect_url'])) continue;
        		$wpdb->insert(
        		    $wpdb->prefix . 'cs_country_redirect',
        		    array( 'country_id' => $country_redirect_url['cs_popup_country_code'], 'redirect_url' => esc_url($country_redirect_url['cs_popup_redirect_url'])),
        		    array( '%d','%s' ),
        		);
        	}
        }

        $_SESSION['success'] = array(__("Settings updated successfully.", 'cs-settings-save'));
        $message = __("Settings updated successfully.", 'cs-settings-save');
        add_settings_error(
                'cntsel_notice',
                esc_attr( 'settings_updated' ),
                $message,
                'success'
        );
        wp_redirect(admin_url('admin.php?page=country-selector-config'));
        die();
    }

    function country_selector_delete_country_redirect($cr_id) {
        global $wpdb;

        return $wpdb->delete($wpdb->prefix.'cs_country_redirect', array('id' => $cr_id), array('%d'));
    }

    function country_selector_get_country_redirect($cr_id) {
        global $wpdb;

        return $wpdb->get_row( $wpdb->prepare( "SELECT * FROM ".$wpdb->prefix."cs_country_redirect WHERE id = ".$cr_id ) );
    }

    add_action('wp_footer', 'show_cs_redirect_popup'); 
    function show_cs_redirect_popup() {
        $popup_enable = get_option('cs_popup_enable', '');

        if($popup_enable == 'yes') {
            $countries = countrySelectorGetAllCountryRedirects();
            include_once('cs_redirect_popup.php');
        }
    }

    function sess_start() {
        if (!session_id())
            session_start();
    }
    add_action('init','sess_start');