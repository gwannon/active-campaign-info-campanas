<?php

function getAllCampaigns($max_items, $offset = 0) {
  $curl = curl_init();
  curl_setopt($curl, CURLOPT_URL, AC_API_DOMAIN."/api/3/campaigns?orders[sdate]=DESC&offset=".$offset."&limit=".$max_items);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($curl, CURLOPT_HTTPHEADER, array('Api-Token: '.AC_API_TOKEN));
  $result = json_decode(curl_exec($curl));
  return $result->campaigns;
}

function getMessage($link) {
  $curl = curl_init();
  curl_setopt($curl, CURLOPT_URL, $link);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($curl, CURLOPT_HTTPHEADER, array('Api-Token: '.AC_API_TOKEN));
  $result = json_decode(curl_exec($curl));
  return $result->campaignMessage;
}