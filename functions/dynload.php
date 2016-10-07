<?php
  require_once( dirname( __FILE__ ) . '/../../../../wp-blog-header.php' );
  global $wpdb;
  $table_name = $wpdb->prefix . 'wpyadb';

  $item_per_page = 15;

  $page_number = filter_var($_POST["page"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH);



  //get current starting point of records
  $position = (($page_number-1) * $item_per_page);

  $sql = "SELECT * from " . $table_name;

  $yadb_query =  $sql . " order by time DESC limit " . $position . "," . $item_per_page . ";";
  $topics = $wpdb->get_results($yadb_query);

  $output = '';
  if(!empty($topics)) {
    foreach($topics as $topic) {
      $user = get_userdatabylogin($topic->username);
      if ($user->ID) { $users = get_avatar($user->ID,30); } else {$users = "";}
      $Activity = "";
      $output .= '<tr class=wp_yadb_row onclick="loadTopicContent(this,\'' .$topic->uuid . '\')"; onmouseover="rowOver(this,\'.5\',\'#dddddd\')"; onmouseout="rowOver(this,\'1\',\'transparent\')";>';
      $output .= '<td style=text-align:left>'. $topic->topic_text . '<br><small>' .$topic->time . '</small></td>';
      $output .= '<td>' . $topic->categorie . '</td>';
      $output .= '<td align=left>' . $users . '</td>';
      $output .= '<td>' . $Replies . '</td>';
      $output .= '<td>' . $topic->views . '</td>';
      $output .= '<td align=right><small>' .$Activity . '</small></td>';
  		$output .= '</tr>';
    }
  }
  print $output;
?>
