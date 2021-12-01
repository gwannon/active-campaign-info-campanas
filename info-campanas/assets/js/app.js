var offset = 0;
jQuery(document).ready(function() {
  loadMore();
  jQuery("#loadmore").click(function() { loadMore(); });
  jQuery("#search").submit(function(e) {
    e.preventDefault();
    offset = 0;
    jQuery("table tbody").empty();
    if (jQuery("input[name=search]").val() != '') {
      searchCampaign();
     } else {
      loadMore();
    }
  });
});

function generateTable(json) {
  json.forEach(function(data, index) {
    jQuery("table tbody").append("<tr>"+
      "<td><a href='./view.php?id="+data.id+"' target='_blank'>"+data.name+"</a><div class='image'><img src='"+data.image+"' /></div></td>"+
      "<td>"+data.date+"</td>"+
      "<td>"+data.subject+"</td>"+
      "<td>"+data.send_amt+"</td>"+
      "<td>"+data.uniqueopens+"</td>"+
      "<td>"+data.uniqueopens_percent+"%</td>"+
      "<td>"+data.opens+"</td>"+
      "<td>"+data.uniquelinkclicks+"</td>"+
      "<td>"+data.uniquelinkclicks_percent+"%</td>"+
      "<td>"+data.linkclicks+"</td>"+
      "<td>"+data.unsubscribes+"</td>"+
    "</tr>")
  });
}

function loadMore() {
  jQuery.ajax({
    url : './ajax.php',
    data : { offset: offset },
    type : 'GET',
    dataType : 'json',
    beforeSend: function () {
      jQuery("#loading").css("display", "block");
      jQuery("#loadmore").css("display", "none");
    },
    success : function(json) {
      generateTable(json);
      offset = offset + limit;
    },
    error : function(xhr, status) {
        alert('Disculpe, existió un problema');
    },
    complete : function(xhr, status) {
      jQuery("#loading").css("display", "none");
      jQuery("#loadmore").css("display", "block");
    }
  });
}

function searchCampaign() {
  jQuery.ajax({
    url : './ajax.php',
    data : { search: jQuery("#search input[name=search]").val() },
    type : 'GET',
    dataType : 'json',
    beforeSend: function () {
      jQuery("#loading").css("display", "block");
      jQuery("#loadmore").css("display", "none");
    },
    success : function(json) {
      generateTable(json)
    },
    error : function(xhr, status) {
        alert('Disculpe, existió un problema');
    },
    complete : function(xhr, status) {
      jQuery("#loading").css("display", "none");
    }
  });
}