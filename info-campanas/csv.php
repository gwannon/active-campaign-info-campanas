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

echo array2csv($csv);

function array2csv($data, $delimiter = ',', $enclosure = '"', $escape_char = "\\") {
    $f = fopen('php://memory', 'r+');
    foreach ($data as $item) {
        fputcsv($f, $item, $delimiter, $enclosure, $escape_char);
    }
    rewind($f);
    return stream_get_contents($f);
}