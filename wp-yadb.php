<?php

/*
Plugin Name: Yust Another Discuss Board
Plugin URI: https://github.com/dbk3r/wp-yadb
Description: Wordpress Discourse Clone
Version: 0.11 Alpha
Author: Denis Becker
*/


function insert_wpyadb() {

	global $current_user;

	require_once(ABSPATH . 'wp-includes/category.php');
	require_once('functions/io.php');
	require_once('functions/read_topic_content.php');
	require_once('functions/new_topic.php');
	require_once('functions/read_topics.php');
?>
	<div class='wrap'>
	<Script type="text/javascript" src="/wp/wp-content/plugins/wp-yadb/js/functions.js"></Script>
<?php

	# echo $current_user->user_login;
			#wpyadb_hello();
			wpyadb_menu();
			wpyadb_new_topic();
			wpyadb_header();
			wpyadb_topics();
			wpyadb_menu();
			wpyadb_footer();
?>
	</div>
<?php
}
register_activation_hook(__FILE__, 'wpyadb_activate');
register_uninstall_hook( __FILE__, 'wpyadb_uninstall' );

add_shortcode('wpyadb', 'insert_wpyadb');
add_action('admin_init', 'wpyadb_init');


function wpyadb_init() {

}



function wpyadb_activate() {

	global $wpdb;
	$table_name = $wpdb->prefix . 'wpyadb';
	$charset_collate = $wpdb->get_charset_collate();
	$sql = "CREATE TABLE $table_name (
		id bigint NOT NULL AUTO_INCREMENT,
		uuid varchar(255),
		time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		username tinytext NOT NULL,
		topic_text text NOT NULL,
		post_text text NOT NULL,
		categorie varchar(255) DEFAULT '' NOT NULL,
		views int DEFAULT 0,
		url varchar(55) DEFAULT '' NOT NULL,
		UNIQUE KEY id (id)
	) $charset_collate;";
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );

}

function wpyadb_uninstall() {

	global $wpdb;
    $table_name = $wpdb->prefix . 'wpyadb';
    $sql = "DROP TABLE $table_name";
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );

}



?>
