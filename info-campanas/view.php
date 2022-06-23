<?php

include_once("./lib/config.php");

if(!is_numeric($_REQUEST['id'])) die;

if(isset($_REQUEST['recache']) && $_REQUEST['recache'] == 'yes') deleteCampaignCachedInfo($_REQUEST['id']);

$campaign = getCampaignCachedInfo($_REQUEST['id']);

$extra = "<link href='./assets/css/heatmap.css' rel='stylesheet'>
<script src='https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js'></script>
<script>var view_campaign_id = ".$_REQUEST['id'].";</script>
<script src='./assets/js/heatmap.js'></script>
<div id='control'>
  Mapa de Calor<br/>
  <input type='radio' name='clicks' value='uniqueclicks' title='Clicks únicos'>
  <input type='radio' name='clicks' value='clicks' title='Clicks totales'><br/>
  <small>Únicos/Totales</small><br/>
  <div id='stats' style='padding: 10px; '>--</div>
  <div id='blue' title='Por encima de la media x3'>--</div>
  <div id='green' title='Por encima de la media x2'>--</div>
  <div id='orange' title='Por encima de la media'>--</div>
  <div id='red' title='Por debajo de la media'>--</div>
  <div id='black' title='0 clicks'>--</div>
</div>
";
if(strpos($campaign['text'], "</body>") > 0) echo str_replace("</body>", $extra."</body>", $campaign['text']);
else echo $campaign['text']. $extra;