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
	$topic_text = base64_encode($_POST['desc']);
	$category = $_POST['category'];
	$reply = $_POST['reply'];
	if ($_POST['action'] == "newTopic")
	{
		$sql = "INSERT INTO $table_name set reply='$reply',uuid='$uuid',time='$datetime',username='$user',topic_text='$topic_text',post_text='" . $post_text ."',categorie='$category'";
		$return_value = "{\"category\":\"" . $category . "\",\"desc\":\"" . base64_decode($topic_text) . "\",\"user\":\"" . $user . "\",\"uuid\":\"" . $uuid . "\",\"date\":\"" . $datetime ."\",\"statement\":\"". $sql ."\"}";
	}
	else if ($_POST['action'] == "commentTopic")
	{
		$sql = "INSERT INTO $table_name set reply='$reply',uuid='". $_POST['uuid'] . "',time='$datetime',username='$user',topic_text='$topic_text',post_text='" . $post_text ."',categorie='$category'";

		$return_value = "{\"uuid\":\"" . $_POST['uuid'] . "\"}";
	}
	else if ($_POST['action'] == "pinTopic")
	{
		$yadb_id = $_POST['yid'];
		$value = $_POST['value'];
		$sql = "UPDATE $table_name SET pinned='$value' WHERE id=$yadb_id;";
		$return_value = "{\"yadb_id\":\"" . $sql . "\"}";
	}
	else if ($_POST['action'] == "deleteTopic")
	{
		$yadb_id = $_POST['yadb_id'];
		$reply = $_POST['reply'];
		$uuid = $_POST['uuid'];
		if ($reply == "1")
		{
			$sql = "delete from $table_name WHERE id='$yadb_id'";
		}
		else
		{
			 $sql = "delete from $table_name where uuid='$uuid'";
		}
		$return_value = "{\"sql\":\"" . $sql . "\"}";
	}
	else if ($_POST['action'] == "updateTopic")
	{
		$yadb_id = $_POST['yid'];
		$sql = "UPDATE $table_name set post_text='$post_text' where id=$yadb_id;";
		$return_value = "{\"status\":\"saved succesfully\"}";
	}


	if ($sql != "") { $wpdb->query($sql); }
	echo $return_value;

?>
