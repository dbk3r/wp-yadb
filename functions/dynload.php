<?php


  require_once('../../../../wp-blog-header.php' );
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
        $post_text = base64_decode($topic->post_text);
        $post_text = str_replace("\\", "", $post_text);
        $Activity = "";
        $output .= '<tr class=' . $_POST['uuid'] . ' style="background:#eeeeee;border-bottom-style:none;">';
        $output .= '<td colspan=6 style="border-bottom-style:none;">';
        $output .= '<table width=100% border=0 style="border-style:none;">';
        $output .= '<tr>';
        $output .= '<th valign=top align=left style="border-right-style:none;">' . get_avatar($user->ID,40,"",$topic->username) .'</th>';
        $output .= '<th align=right width=100 style="border-left-style:none;">'. $topic->time .'</th>';
        $output .= '</tr>';
        $output .= '<tr style="background:#ffffff">';
        $output .= '<td colspan=2>' . $post_text . '</td>';
        $output .= '</tr>';
        $output .= '</table>';
    		$output .= '</tr>';
      }
      $output .= '<tr class=' . $_POST['uuid'] . ' style="background:#eeeeee;border-top-style:none;"><td colspan=6 style="text-align:right;"><a style="cursor:pointer" class="btn_wpyadb-reply-topic" >REPLY</a></td></tr>';
      $sql = "UPDATE " . $table_name . " SET views=views+1 where uuid='" . $_POST['uuid'] . "';";
      require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
      dbDelta( $sql );
    }
  }

  if($_POST['content'] == "topics") {

    $item_per_page = 15;
    $page_number = filter_var($_POST["page"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH);
    $position = (($page_number-1) * $item_per_page);

    $reply_sql = "SELECT COUNT(*) AS total from " . $table_name . "where reply='1' ";
    $replies_result = $wpdb->get_results($reply_sql);
    if($replies_result->total) { $replies = $replies_result->total; } else { $replies = 0;}

    $sql = "SELECT * from " . $table_name . " where reply='0' ";

    $yadb_query =  $sql . " order by time DESC limit " . $position . "," . $item_per_page . ";";
    $topics = $wpdb->get_results($yadb_query);

    $output = '';
    if(!empty($topics)) {
      foreach($topics as $topic) {
        $user = get_userdatabylogin($topic->username);
        if ($user->ID) { $users = get_avatar($user->ID,24,"",$topic->username); } else {$users = "";}
        date_default_timezone_set("Europe/Berlin");
        $age = intval((strtotime(date('Y-m-d H:i:s')) - strtotime($topic->time)) / 60);
        if ($age < 1440) {
          if ($age < 60) { $age = intval($age) . " minutes";}
          else { $age = intval($age / 60) . " h";}

        }
        if ($age >= 1440) {
          if ($age < 1440) {$gk = "<";} else { $gk = ">"; }
          $age = $gk . " " .intval($age / 60 / 24) . " days";
        }
        $Activity = $age;
        $output .= '<tr class=wp_yadb_row onclick="loadTopicContent(this,\'' .$topic->uuid . '\')"; onmouseover="rowOver(this,\'.5\',\'#dddddd\')"; onmouseout="rowOver(this,\'1\',\'transparent\')";>';
        $output .= '<td style=text-align:left>'. $topic->topic_text . '<br><small>' .$topic->time . '</small></td>';
        $output .= '<td>' . $topic->categorie . '</td>';
        $output .= '<td align=left>' . $users . '</td>';
        $output .= '<td>' . $replies . '</td>';
        $output .= '<td>' . $topic->views . '</td>';
        $output .= '<td align=right><small>' .$Activity . '</small></td>';
    		$output .= '</tr>';
      }
    }
  }
  print $output;
?>
