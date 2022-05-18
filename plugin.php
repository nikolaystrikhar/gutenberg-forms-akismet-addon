<?php
/**
 * Plugin Name: Guten Forms Akismet
 * Plugin URI: https://www.gutenbergforms.com
 * Description: Akismet add-on for Gutenberg Forms. Connect with Akismet and protect your form submissions against spam via their global database of spam.
 * Author: Tetra Soft
 * Author URI: https://jak.dev/
 * Version: 1.0.1
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: cwp-gutenberg-forms-akismet
 */

require_once plugin_dir_path( __FILE__ ) . 'addon.php';

register_activation_hook(__FILE__, function() {
    add_option('gutenberg-forms-akismet-addon-activated', true);
});


// redirecting to the gutenberg forms dashboard

add_action('admin_init', function() {
    if (get_option('gutenberg-forms-akismet-addon-activated', false)) {
        delete_option('gutenberg-forms-akismet-addon-activated');
         exit( wp_redirect("admin.php?page=gutenberg_forms#/integrations") );
    }
});
