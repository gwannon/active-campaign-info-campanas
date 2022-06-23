jQuery(document).ready(function () {
    jQuery('a').css('display', 'inline-block');
    jQuery('input[type=radio]').change(function() {
      jQuery('.heatmap').remove();
      jQuery('#blue,#green,#orange,#red,#stats,#black').html('--');
      loadHeatMap(view_campaign_id);
    });
});
  
function loadHeatMap(id) {
    jQuery.ajax({
        url : './ajax.php',
        data : {
        campaign_id: id
        },
        type : 'GET',
        dataType : 'json',
        beforeSend: function () { jQuery('a img').css('opacity', '1'); },
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
        complete : function(xhr, status) { jQuery('a img').css('opacity', '0.45'); }
    });
}

function generateZone(data, color) {
    jQuery('a[href$=\"'+data.link+'\"]').each(function(index) {
        position = jQuery(this).position();
        console.log(position);
        if(jQuery('input[name=clicks]:checked').val() == 'clicks')  { 
        jQuery('body').append('<div class=\"heatmap\" style=\"top:'+position.top+'px; left: '+position.left+'px; width: '+jQuery(this).outerWidth()+'px; height: '+jQuery(this).outerHeight()+'px; background-color: '+color+';\">'+(data.linkclicks != '' ? data.linkclicks : '0')+' ('+data.linkclickspercent+' %)</div>');
        } else {  
        jQuery('body').append('<div class=\"heatmap\" style=\"top:'+position.top+'px; left: '+position.left+'px; width: '+jQuery(this).outerWidth()+'px; height: '+jQuery(this).outerHeight()+'px; background-color: '+color+';\">'+(data.uniquelinkclicks != '' ? data.uniquelinkclicks : '0')+' ('+data.uniquelinkclickspercent+'%)</div>');
        }
    });
}