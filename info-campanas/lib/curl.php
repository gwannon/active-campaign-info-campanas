<?php

function getAllCampaigns($offset = 0) {
  $items = array();
  $campaigns = curlCall(AC_API_DOMAIN."/api/3/campaigns?orders[sdate]=DESC&offset=".$offset."&limit=".AC_API_LIMIT)->campaigns;
  foreach ($campaigns as $campaign) { 
    $info = getCampaignCachedInfo($campaign);
    $items[] = generateCampaignArray($campaign, $info['title'], $info['image']);
  }
  if (count($items) == 0) return false;
  return $items;
}

function searchCampaigns($search) {
  global $mysqli;
  $items = array();
  $res = $mysqli->query("SELECT campaign_id, title, image FROM `messages` WHERE (`title` LIKE '%{$_REQUEST['search']}%' OR `text` LIKE '%{$_REQUEST['search']}%') ORDER BY campaign_id DESC");
  if($res->num_rows > 0) {
    while ($row = $res->fetch_assoc()) {
      $campaign = curlCall(AC_API_DOMAIN."/api/3/campaigns/".$row['campaign_id'])->campaign;
      $items[] = generateCampaignArray($campaign, $row['title'], $row['image']);
    }
  }
  return $items;
}

function getCampaignCachedInfo($campaign) {
  global $mysqli;
  $result = $mysqli->query("SELECT title, image, text FROM messages WHERE campaign_id = ".(is_numeric($campaign) ? $campaign : $campaign->id));
  if($result->num_rows == 0) {
    $message = curlCall($campaign->links->campaignMessage); //Conseguimos el título de la campaña
    $html = curlCall($message->campaignMessage->links->message); //Conseguimos el texto de la campaña
    //Guardamos en base de datos
    $res = $mysqli->query("INSERT INTO `messages` (`campaign_id`, `title`, `text`, `image`, `date`) VALUES ('".$campaign->id."', '".$message->campaignMessage->subject."', '".addslashes($html->message->html)."', '".$message->campaignMessage->screenshot."', CURRENT_TIMESTAMP)");
    return array("title" => $message->campaignMessage->subject, "image" => $message->campaignMessage->screenshot, "text" => $html->message->html);
  } else {
    $row = $result->fetch_assoc();
    return array("title" => $row['title'], "image" => $row['image'], "text" => $row['text']);
  }
}

function generateCampaignArray($campaign, $title, $image) {
  return [
    "id" => $campaign->id,
    "date" => date("Y-m-d H:i", strtotime($campaign->sdate)),
    "name" => $campaign->name,
    "subject" => $title,
    "send_amt" => $campaign->send_amt,
    "uniqueopens" => $campaign->uniqueopens,
    "uniqueopens_percent" => number_format(($campaign->uniqueopens > 0 && $campaign->send_amt > 0 ? round(($campaign->uniqueopens / $campaign->send_amt * 100), 2) : 0), 2, ",", "."),
    "opens" => $campaign->opens,
    "uniquelinkclicks" => $campaign->uniquelinkclicks,
    "uniquelinkclicks_percent" => number_format(($campaign->uniquelinkclicks > 0 && $campaign->send_amt > 0 ? round(($campaign->uniquelinkclicks / $campaign->send_amt * 100), 2) : 0), 2, ",", "."),
    "linkclicks" => $campaign->linkclicks,
    "unsubscribes" => $campaign->unsubscribes,
    "image" => $image,
    "segment_name" => $campaign->segmentname
  ];
}

function curlCall($link) {
  $curl = curl_init();
  curl_setopt($curl, CURLOPT_URL, $link);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($curl, CURLOPT_HTTPHEADER, array('Api-Token: '.AC_API_TOKEN));
  $json = json_decode(curl_exec($curl));
  curl_close($curl);
  return $json;
}

function arrayToCsv($data, $delimiter = ',', $enclosure = '"', $escape_char = "\\") {
  $f = fopen('php://memory', 'r+');
  foreach ($data as $item) {
      fputcsv($f, $item, $delimiter, $enclosure, $escape_char);
  }
  rewind($f);
  return stream_get_contents($f);
}


function getCampaignById($id) {
  $items = array();
  $campaign = curlCall(AC_API_DOMAIN."/api/3/campaigns/{$id}")->campaign;
  $items['uniquelinkclicks'] = $campaign->uniquelinkclicks;
  $items['linkclicks'] = $campaign->linkclicks;
  $links = curlCall(AC_API_DOMAIN."/api/3/campaigns/{$id}/links")->links;
  foreach ($links as $link) { 
    if (filter_var($link->link, FILTER_VALIDATE_URL)) {
      $items['links'][] = [
        "link" => $link->link,
        "uniquelinkclicks" => $link->uniquelinkclicks,
        "linkclicks" => $link->linkclicks, 
      ];
    }
  }
  if (count($items['links']) == 0) return false;
  return $items;
}