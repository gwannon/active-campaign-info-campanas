<?php
header("Content-Type: text/csv");
header("Content-Disposition: attachment; filename=active_campaign-".date("Y-m-d").".csv");
include_once("config.php");
$csv = [["Fecha","Nombre","Título","Enviados","Aperturas únicas","Porcentaje de aperturas únicas","Aperturas","Clicks únicos","Porcentaje de clicks únicos","Clicks totales","Bajas"]];
$offset = 0;
while($items = getAllCampaigns($offset)) {
  foreach ($items as $item) {
    unset($item['image']);
    unset($item['id']);
    if(is_array($item)) $csv[] = $item;
  }
  $offset = $offset + AC_API_LIMIT;
}

echo arrayToCsv($csv);