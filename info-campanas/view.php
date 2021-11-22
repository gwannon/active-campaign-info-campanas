<?php

include_once("config.php");

$result = $mysqli->query("SELECT text FROM messages WHERE campaign_id = ".$_REQUEST['id']);
//echo $result->num_rows;
if($result->num_rows > 0) {
  $row = $result->fetch_assoc();
  echo $row['text'];
}