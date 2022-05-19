<?php
ini_set("display_errors", "1");
include_once("./lib/config.php");

ini_set("display_errors", "1");

if(!is_numeric($_REQUEST['id'])) die;
$campaign = getCampaignCachedInfo($_REQUEST['id']);

$extra = "<script src='https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js'></script>
<script>
jQuery(document).ready(function () {
    jQuery('a').css('display', 'inline-block');
    jQuery('input[type=checkbox]').change(function() {
        if(jQuery(this).is(':checked')) {
            loadHeatMap();
        } else {
            jQuery('.heatmap').remove();
            jQuery('#blue,#green,#orange,#red,#stats').html('--');
        }
    });

});
function loadHeatMap() {
  jQuery.ajax({
    url : './ajax.php',
    data : { campaign_id: ".$_REQUEST['id']." },
    type : 'GET',
    dataType : 'json',
    beforeSend: function () {

    },
    success : function(json) {

        var uniquelinkclicks = json.uniquelinkclicks;
        var linkclicks = json.linkclicks;
        var countlinks = json.links.length;

        var step = Math.floor(uniquelinkclicks / countlinks);
        jQuery('#blue,#green,#orange,#red,#stats').html('--');
        jQuery('#stats').html(uniquelinkclicks+'/'+linkclicks);
        jQuery('#blue').html('>= '+(step * 3));
        jQuery('#green').html((step * 2)+' - '+((step * 3)-1));
        jQuery('#orange').html(step+' - '+((step * 2)-1));
        jQuery('#red').html('< '+step);

        json.links.forEach(function(data, index) {
            if(data.uniquelinkclicks >= (step * 3)) generateZone(data, '#2196f396');
            else if(data.uniquelinkclicks >= (step * 2)) generateZone(data, '#00800085');
            else if(data.uniquelinkclicks >= step) generateZone(data, '#ffa50096');
            else generateZone(data, '#ff000057');
        });
    },
    error : function(xhr, status) {

    },
    complete : function(xhr, status) {

    }
  });
}

function generateZone(data, color) {
  jQuery('a[href$=\"'+data.link+'\"]').each(function(index) {
    position = jQuery(this).position();
    width = jQuery(this).outerWidth();
    height = jQuery(this).outerHeight(); 
    jQuery('body').append('<div class=\"heatmap\" style=\"text-shadow: 0 0 3px #000; position: absolute; top:'+position.top+'px; left: '+position.left+'px; width: '+width+'px; height: '+height+'px; background-color: '+color+'; color: white; font-size: 20px; z-index: 10; min-width: 30px; min-height: 20px; font-weight: 700;\">'+data.uniquelinkclicks+'/'+data.linkclicks+'</div>');
  });
}

</script>
<div style='position: fixed; top: 0px; right: 0px; text-align: center; font-weight: 700;'>
    Mapa de Calor<br/>
    <input type='checkbox'>
    <div id='stats' style='padding: 10px; '>--</div>
    <div id='blue' style='background-color: blue; color: white; padding: 10px; '>--</div>
    <div id='green' style='background-color: green; color: white; padding: 10px;'>--</div>
    <div id='orange' style='background-color: orange; color: white; padding: 10px;'>--</div>
    <div id='red' style='background-color: red; color: white; padding: 10px;'>--</div>
</div>";
if(strpos($campaign['text'], "</body>") > 0) $campaign['text'] = str_replace("</body>", $extra."</body>", $campaign['text']);
else $campaign['text'] = $campaign['text']. $extra;


echo $campaign['text'];