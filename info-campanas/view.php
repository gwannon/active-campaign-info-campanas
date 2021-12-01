<?php

include_once("config.php");
if(!is_numeric($_REQUEST['id'])) die;
$campaign = getCampaignCachedInfo($_REQUEST['id']);
echo $campaign['text'];