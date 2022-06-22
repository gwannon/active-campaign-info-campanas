<?php

include_once("./lib/config.php");

if(!is_numeric($_REQUEST['id'])) die;
$campaign = getCampaignCachedInfo($_REQUEST['id']);

$extra = "<script src='https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js'></script>
<script>
  jQuery(document).ready(function () {
    jQuery('a').css('display', 'inline-block');
    jQuery('input[type=radio]').change(function() {
      jQuery('.heatmap').remove();
      jQuery('#blue,#green,#orange,#red,#stats,#black').html('--');
      loadHeatMap();
    });
  });

  function loadHeatMap() {
    jQuery.ajax({
      url : './ajax.php',
      data : {
        campaign_id: ".$_REQUEST['id']."
      },
      type : 'GET',
      dataType : 'json',
      beforeSend: function () { },
      success : function(json) {
        var uniquelinkclicks = json.uniquelinkclicks;
        var linkclicks = json.linkclicks;
        var countlinks = json.links.length;

        if(jQuery('input[name=clicks]:checked').val() == 'clicks')  {
          var step = Math.ceil(linkclicks / countlinks);
        } else {
          var step = Math.ceil(uniquelinkclicks / countlinks);
        }
        if(step < 1) step = 1;
        jQuery('#blue,#green,#orange,#red,#stats,#black').html('--');
        jQuery('#stats').html(uniquelinkclicks+'/'+linkclicks);
        jQuery('#blue').html('>= '+(step * 3));
        jQuery('#green').html((step * 2)+' - '+((step * 3)-1));
        jQuery('#orange').html(step+' - '+((step * 2)-1));
        jQuery('#red').html('1 - '+step);
        jQuery('#black').html('0');

        if(jQuery('input[name=clicks]:checked').val() == 'clicks')  {
          json.links.forEach(function(data, index) {
            if(data.linkclicks >= (step * 3)) generateZone(data, '#2196f396');
            else if(data.linkclicks >= (step * 2)) generateZone(data, '#00800085');
            else if(data.linkclicks >= step) generateZone(data, '#ffa50096');
            else if(data.linkclicks > 0)  generateZone(data, '#ff000057');
            else generateZone(data, '#00000057');
          });
        } else {
          json.links.forEach(function(data, index) {
            if(data.uniquelinkclicks >= (step * 3)) generateZone(data, '#2196f396');
            else if(data.uniquelinkclicks >= (step * 2)) generateZone(data, '#00800085');
            else if(data.uniquelinkclicks >= step) generateZone(data, '#ffa50096');
            else if(data.uniquelinkclicks > 0)  generateZone(data, '#ff000057');
            else generateZone(data, '#00000057');
          });
        }
      },
      error : function(xhr, status) { },
      complete : function(xhr, status) { }
    });
  }

  function generateZone(data, color) {
    jQuery('a[href$=\"'+data.link+'\"]').each(function(index) {
      position = jQuery(this).position();
      if(jQuery('input[name=clicks]:checked').val() == 'clicks')  { 
        jQuery('body').append('<div class=\"heatmap\" style=\"top:'+position.top+'px; left: '+position.left+'px; width: '+jQuery(this).outerWidth()+'px; height: '+jQuery(this).outerHeight()+'px; background-color: '+color+';\">'+(data.linkclicks != '' ? data.linkclicks : '0')+' ('+data.linkclickspercent+' %)</div>');
      } else {  
        jQuery('body').append('<div class=\"heatmap\" style=\"top:'+position.top+'px; left: '+position.left+'px; width: '+jQuery(this).outerWidth()+'px; height: '+jQuery(this).outerHeight()+'px; background-color: '+color+';\">'+(data.uniquelinkclicks != '' ? data.uniquelinkclicks : '0')+' ('+data.uniquelinkclickspercent+'%)</div>');
      }
    });
  }
</script>
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
<style>
  #control {
    position: fixed;
    top: 0px;
    right: 0px;
    text-align: center;
    font-weight: 700;
  }
  #blue,
  #green,
  #orange,
  #red,
  #black {
    color: white;
    padding: 10px;
    background-color: black;
  }
  #blue { background-color: blue; }
  #green { background-color: green; }
  #orange { background-color: orange; }
  #red { background-color: red; }

  .heatmap {
    text-shadow: 0 0 3px #000;
    position: absolute;
    color: white;
    font-size: 20px;
    z-index: 10;
    min-width: 30px;
    min-height: 20px;
    font-weight: 700;
  }
</style>";
if(strpos($campaign['text'], "</body>") > 0) $campaign['text'] = str_replace("</body>", $extra."</body>", $campaign['text']);
else $campaign['text'] = $campaign['text']. $extra;

echo $campaign['text'];