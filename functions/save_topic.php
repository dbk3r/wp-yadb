<?php
	# save new topic to db
	require( dirname( __FILE__ ) . '/../../../../wp-blog-header.php' );
	require( dirname( __FILE__ ) . '/io.php' );

	global $wpdb;
	$table_name = $wpdb->prefix . 'wpyadb';
	$uuid = gen_uuid();
	date_default_timezone_set("Europe/Berlin");
	$datetime = date('Y-m-d H:i:s');
	$user = $_POST['user'];
	if($user == "") {$user = "Guest";}
	$post_text = base64_encode($_POST['content']);
	$topic_text = $_POST['desc'];
	$category = $_POST['category'];

	$reply = $_POST['reply'];

	$sql = "INSERT INTO $table_name set reply='$reply',uuid='$uuid',time='$datetime',username='$user',topic_text='$topic_text',post_text='" . $post_text ."',categorie='$category'";
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );

	echo "{\"uuid\":\"" . $uuid . "\",\"date\":\"" . $datetime ."\",\"statement\":\"". $sql ."\"}";
?>
