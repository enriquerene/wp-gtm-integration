<?php
/*
Plugin Name: GTM Integration
Description: Adds the required GTM tags when provided GTM ID.
Version: 1.0
Author: Enrique René Beauxis Reyes
Author URI: https://enriquerene.com.br
License: GPL3
Tags: google-tag-manager,gtm,tag-manager,google-tag,google
*/

// Add menu item in the WordPress admin dashboard
function gtm_integration_menu() {
    add_menu_page(
        'Custom GTM Settings',
        'Custom GTM',
        'manage_options',
        'custom-gtm-settings',
        'custom_gtm_settings_page'
    );
}
add_action('admin_menu', 'gtm_integration_menu');

// Display the settings page content
function custom_gtm_settings_page() {
    if (!current_user_can('manage_options')) {
        return;
    }
    
    if (isset($_POST['gtm_id'])) {
        update_option('custom_gtm_id', sanitize_text_field($_POST['gtm_id']));
        echo '<div class="notice notice-success"><p>GTM ID saved successfully!</p></div>';
    }

    $gtm_id = get_option('custom_gtm_id');
    ?>
    <div class="wrap">
        <h1>Custom GTM Settings</h1>
        <form method="post" action="">
            <label for="gtm_id">Enter GTM ID:</label><br>
            <input type="text" id="gtm_id" name="gtm_id" value="<?php echo esc_attr($gtm_id); ?>"><br><br>
            <input type="submit" class="button button-primary" value="Save">
        </form>
    </div>
    <?php
}

// Add GTM meta tag to the site's head section
function add_gtm_integration_tag() {
    $gtm_id = get_option('custom_gtm_id');
    if (!empty($gtm_id)) {
        echo "<!-- Google Tag Manager --><script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);})(window,document,'script','dataLayer','".esc_attr($gtm_id)."');</script><!-- End Google Tag Manager -->";
    }
}
add_action('wp_head', 'add_gtm_integration_tag');

// Insert custom HTML as the first child inside the body tag
function insert_gtm_integration_tag_body_start() {
    $gtm_id = get_option('custom_gtm_id');
    if (!empty($gtm_id)) {
        $gtm_body_tag = '<!-- Google Tag Manager (noscript) --><iframe id="gtm-nonscript" src="https://www.googletagmanager.com/ns.html?id='.esc_attr($gtm_id).'" height="0" width="0" style="display:none;visibility:hidden"></iframe><script>document.querySelector("#gtm-nonscript").remove()</script><!-- End Google Tag Manager (noscript) -->';
        echo $gtm_body_tag;
    }
}
add_action('wp_body_open', 'insert_gtm_integration_tag_body_start');
