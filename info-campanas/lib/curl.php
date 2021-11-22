<?php

function getAllCampaigns($max_items, $offset = 0) {
  $items = array();
  $curl = curl_init();
  curl_setopt($curl, CURLOPT_URL, AC_API_DOMAIN."/api/3/campaigns?orders[sdate]=DESC&offset=".$offset."&limit=".$max_items);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($curl, CURLOPT_HTTPHEADER, array('Api-Token: '.AC_API_TOKEN));
  $result = json_decode(curl_exec($curl));
  foreach ($result->campaigns as $campaign) { 
    $message = getMessage($campaign);
    $items[] = [
      "id" => $campaign->id,
      "name" => $campaign->name,
      "subject" => $message,
      "send_amt" => $campaign->send_amt,
      "uniqueopens" => $campaign->uniqueopens,
      "uniqueopens_percent" => ($campaign->uniqueopens > 0 && $campaign->send_amt > 0 ? round(($campaign->uniqueopens / $campaign->send_amt * 100), 2) : 0)."%",
      "opens" => $campaign->opens,
      "uniquelinkclicks" => $campaign->uniquelinkclicks,
      "linkclicks" => $campaign->linkclicks,
      "unsubscribes" => $campaign->unsubscribes,
      "image" =>  getImagePreview($campaign)
    ];
  } 
  return $items;
}

function searchCampaigns($search) {
  global $mysqli;
  $items = array();
  $res = $mysqli->query("SELECT campaign_id, title, image FROM `messages` WHERE (`title` LIKE '%{$_REQUEST['search']}%' OR `text` LIKE '%{$_REQUEST['search']}%') ORDER BY campaign_id DESC");
  if($res->num_rows > 0) {
    while ($row = $res->fetch_assoc()) {
      $curl = curl_init();
      curl_setopt($curl, CURLOPT_URL, AC_API_DOMAIN."/api/3/campaigns/".$row['campaign_id']);
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($curl, CURLOPT_HTTPHEADER, array('Api-Token: '.AC_API_TOKEN));
      $result = json_decode(curl_exec($curl));
      $items[] = [
        "id" => $result->campaign->id,
        "name" => $result->campaign->name,
        "subject" => $row['title'],
        "send_amt" => $result->campaign->send_amt,
        "uniqueopens" => $result->campaign->uniqueopens,
        "uniqueopens_percent" => ($result->campaign->uniqueopens > 0 && $result->campaign->send_amt > 0 ? round(($result->campaign->uniqueopens / $result->campaign->send_amt * 100), 2) : 0)."%",
        "opens" => $result->campaign->opens,
        "uniquelinkclicks" => $result->campaign->uniquelinkclicks,
        "linkclicks" => $result->campaign->linkclicks,
        "unsubscribes" => $result->campaign->unsubscribes,
        "image" => $row['image']
      ];
    }
  }
  return $items;
}

function getMessage($campaign) {
  global $mysqli;
  $result = $mysqli->query("SELECT title FROM messages WHERE campaign_id = ".$campaign->id);
  if($result->num_rows == 0) {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $campaign->links->campaignMessage);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Api-Token: '.AC_API_TOKEN));
    $message = json_decode(curl_exec($curl));
    //print_r ($message);
    curl_close($curl);

    
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $message->campaignMessage->links->message);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Api-Token: '.AC_API_TOKEN));
    $html = json_decode(curl_exec($curl));
    //Guardamos en base de datos
    $res = $mysqli->query("INSERT INTO `messages` (`id`, `campaign_id`, `title`, `text`, `image`, `date`) VALUES ('', '".$campaign->id."', '".$message->campaignMessage->subject."', '".addslashes($html->message->html)."', '".$message->campaignMessage->screenshot."', CURRENT_TIMESTAMP)");
    return $message->campaignMessage->subject;
  } else {
    $row = $result->fetch_row();
    return $row[0];
  }
}

function getImagePreview($campaign) {
	global $mysqli;
  $result = $mysqli->query("SELECT image FROM messages WHERE campaign_id = ".$campaign->id);
  if($result->num_rows > 0) {
    $row = $result->fetch_row();
    return $row[0];
  } else {
    return "";
  }

}
