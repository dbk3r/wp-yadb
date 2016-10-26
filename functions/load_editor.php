<?php

require( dirname( __FILE__ ) . '/../../../../wp-blog-header.php' );
$nn = $_GET['nn'];
$settings = array('editor_class' => '$nn', 'media_buttons' => false , 'tinymce' => true, 'quicktags' => false, 'automatic_uploads' => true, 'paste_data_images' => true, 'editor_height' => 200 );
global $wpdb;
$table_name = $wpdb->prefix . 'wpyadb';
$id = $_GET['id'];
$sql = "SELECT * from " . $table_name . " where id='" . $id . "'";
$yadb_url = plugins_url() . "/wp-yadb";
$results = $wpdb->get_results($sql);
if(!empty($results)) {
  foreach($results as $topic) {
    $user = get_userdatabylogin($topic->username);
    $content = base64_decode($topic->post_text) ;
  }
}
else {
  #$content = "";
}
print "<textarea id='wp-yadb_edit-topic' name=''wp-yadb_edit-topic'>$content</textarea>";
?>
