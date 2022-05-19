<?php

header('Content-type: application/json; charset=utf-8');
include_once("./lib/config.php");
$items = array();
if(isset($_REQUEST['search']) && $_REQUEST['search'] != '') $items = searchCampaigns($_REQUEST['search']);
else if($_REQUEST['campaign_id'] && $_REQUEST['campaign_id'] > 0) $items = getCampaignById($_REQUEST['campaign_id']);
else $items = getAllCampaigns($_REQUEST['offset']);
echo json_encode($items);
