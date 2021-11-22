<?php

header('Content-type: application/json; charset=utf-8');

include_once("config.php");

if(isset($_REQUEST['search']) && $_REQUEST['search'] != '') $items = searchCampaigns($_REQUEST['search']);
else $items = getAllCampaigns($_REQUEST['limit'], $_REQUEST['offset']);

echo json_encode($items);