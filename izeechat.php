<?php
/*
* Plugin Name: Apizee Contact
* Plugin URI: https://www.apizee.com
* Description: IzeeChat is a video conferencing solution with text messaging, allowing you to communicate directly with your visitors. So, you can offer your help and your advice instantly to your visitors when they need it.
* Version: 2.0
* Author: Apizee
* Author URI: https://www.apizee.com/
* License: GPL2
*/

// Check if main wordpress constants are defined
if (!defined('WP_CONTENT_URL'))
      define('WP_CONTENT_URL', get_option('siteurl').'/wp-content');
if (!defined('WP_CONTENT_DIR'))
      define('WP_CONTENT_DIR', ABSPATH.'wp-content');
if (!defined('WP_PLUGIN_URL'))
      define('WP_PLUGIN_URL', WP_CONTENT_URL.'/plugins');
if (!defined('WP_PLUGIN_DIR'))
      define('WP_PLUGIN_DIR', WP_CONTENT_DIR.'/plugins');

// Plugin activation
function activate() {
	global $wpdb, $wp_db_version;
	$tableName = $wpdb->prefix . 'izeechat';

	if ($wpdb->get_var("show tables like '$tableName'") != $tableName) {
		$sql = "CREATE TABLE " . $tableName . " (
			`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
			`apiKey` varchar(255) DEFAULT NULL,
			`siteKey` varchar(255) DEFAULT NULL,
			`serverDomain` varchar(255) DEFAULT NULL,
			`updated_at` TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
			UNIQUE KEY id (id)
		);";
	}

	if ($wp_db_version >= 5540) {
		require_once (ABSPATH . 'wp-admin/includes/upgrade.php');
	} else {
		require_once (ABSPATH . 'wp-admin/upgrade-functions.php');
	}
	dbDelta($sql);
}
register_activation_hook(__FILE__, 'activate');

// Add configure link of plugin page
function plugin_add_settings_link($links) {
    $settings_link = '<a href="admin.php?page=izeechat/izeechat-admin.php">' . __( 'Configure' ) . '</a>';
    array_push($links, $settings_link);
  	return $links;
}
$plugin = plugin_basename( __FILE__ );
add_filter( "plugin_action_links_$plugin", 'plugin_add_settings_link' );

// Plugin deactivation
function deactivate() {
}
register_deactivation_hook(__FILE__, 'deactivate');

function uninstall() {
	global $wpdb;
	$tableName = $wpdb->prefix . 'izeechat';

	$wpdb->query("DROP TABLE IF EXISTS $tableName");
}
register_uninstall_hook(__FILE__, 'uninstall');

// Load scripts
function loadScripts() {
    wp_enqueue_script('jquery');
}
add_action('admin_enqueue_scripts', 'loadScripts');

function initialization() {
	load_plugin_textdomain('izeechat', false, dirname(plugin_basename(__FILE__)) . '/lang');
}
add_action('init', 'initialization');

// When plugin is loaded
function isLoaded() {
	add_action('wp_footer', 'loadContactBox');
	//add_action('in_admin_footer', 'loadContactBox');
}
add_action('plugins_loaded', 'isLoaded');

function loadContactBox() {
	global $wpdb;
	$init = false;

	$results = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}izeechat") ;
	$pos = strpos(get_locale(), '_');
	$culture = substr(get_locale(), 0, $pos);

	if (count($results) > 0) $data = end($results);

	if (!empty($data->siteKey)) {
		if (!empty($data->serverDomain)) {
			$serverDomainRoot = "//" . $data->serverDomain . "/";
		} else {
			$serverDomainRoot = "//cloud.apizee.com/";
		}
		$siteKey = $data->siteKey;
		echo '
		<script type="text/javascript" src="' . $serverDomainRoot . 'contactBox/loaderIzeeChat.js"></script>
		<script>
		    siteKey = "' . $siteKey . '",
		    params = {
		    	serverDomainRoot : "' . $serverDomainRoot . '",
				culture: "' . $culture . '"     
		    };
		loaderIzeeChat(siteKey, params);
		</script>';
	}
}

// Add entry in administration menu
function addMenu() {
	add_menu_page('custom menu title', 'IzeeChat', 'manage_options', 'izeechat/izeechat-admin.php', '', plugins_url( 'izeechat/images/icon.png' ), 6);
}
add_action('admin_menu', 'addMenu');
