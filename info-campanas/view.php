<?php

include_once("config.php");
if(!is_numeric($_REQUEST['id'])) die;
$result = $mysqli->query("SELECT text FROM messages WHERE campaign_id = ".$_REQUEST['id']);
if($result->num_rows > 0) {
  $row = $result->fetch_assoc();
  echo $row['text'];
}