<?php
  require_once( dirname( __FILE__ ) . '/../../../../wp-blog-header.php' );
  global $wpdb;
  $table_name = $wpdb->prefix . 'wpyadb';

  if($_POST['content'] == "topic") {

    $sql = "SELECT * from " . $table_name . " where uuid='" . $_POST['uuid'] . "'";

    $yadb_query =  $sql . " order by time ASC;";
    $topics = $wpdb->get_results($yadb_query);

    $output = '';
    if(!empty($topics)) {
      foreach($topics as $topic) {
        $user = get_userdatabylogin($topic->username);
        if ($user->ID) { $users = get_avatar($user->ID,30,"",$topic->username); } else {$users = "";}
        # $post_text = str_replace("&semicolon&", ";", $topic->post_text);
        $post_text = base64_decode($topic->post_text);
        $post_text = str_replace("\\", "", $post_text);
        $Activity = "";
        $output .= '<tr class=' . $_POST['uuid'] . '>';
        $output .= '<td colspan=6>';
        $output .= '<table width=100%>';
        $output .= '<tr>';
        $output .= '<th valign=top align=left>Autor: ' . $topic->username .'</th>';
        $output .= '<th align=right width=100>'. $topic->time .'</th>';
        $output .= '</tr>';
        $output .= '<tr>';
        $output .= '<td colspan=2>' . $post_text;
        $output .= '</td>';
        $output .= '</tr>';
        $output .= '<td colspan=2 style="text-align:right;"><a style="cursor:pointer" class="btn_wpyadb-reply-topic" >REPLY</a>';
        $output .= '</td>';
        $output .= '</table>';
    		$output .= '</tr>';
      }
    }
  }

  if($_POST['content'] == "topics") {

    $item_per_page = 15;
    $page_number = filter_var($_POST["page"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH);
    $position = (($page_number-1) * $item_per_page);

    $sql = "SELECT * from " . $table_name . " where reply='0' ";

    $yadb_query =  $sql . " order by time DESC limit " . $position . "," . $item_per_page . ";";
    $topics = $wpdb->get_results($yadb_query);

    $output = '';
    if(!empty($topics)) {
      foreach($topics as $topic) {
        $user = get_userdatabylogin($topic->username);
        if ($user->ID) { $users = get_avatar($user->ID,30,"",$topic->username); } else {$users = "";}
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
  }
  print $output;
?>
