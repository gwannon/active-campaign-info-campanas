<?php

include_once("config.php");
$result = getAllCampaigns($_REQUEST['limit'], $_REQUEST['offset']);
$items = array();
foreach ($result as $campaign) { 
  if(in_array($campaign->segmentname, $allowed_segments)) $message = getMessage($campaign->links->campaignMessage);
  else unset($message);
  
  $items[] = [
    "name" => $campaign->name,
    "subject" => isset($message) ? $message->subject : "",
    "send_amt" => $campaign->send_amt,
    "uniqueopens" => $campaign->uniqueopens,
    "opens" => $campaign->opens,
    "uniquelinkclicks" => $campaign->uniquelinkclicks,
    "linkclicks" => $campaign->linkclicks,
    "unsubscribes" => $campaign->unsubscribes
  ];
} 

echo json_encode($items);