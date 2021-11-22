jQuery(document).ready(function() {
  loadMore();

  jQuery("#loadmore").click(function(e) {
    console.log("Hola");
    e.preventDefault();
    loadMore();
  });

  jQuery("button#search").click(function(e) {
    
    if (jQuery("input[name=search]").val() != '') {
      offset = 0;
      jQuery("#loading").css("display", "block");
      jQuery("#loadmore").css("display", "none");
      jQuery("table tbody").empty();
      jQuery.ajax({
        // la URL para la petición
        url : './ajax.php',
        data : { search: jQuery("input[name=search]").val() },
        type : 'GET',
        dataType : 'json',
        success : function(json) {
            json.forEach(function(data, index) {
                /* console.log(data); */
                jQuery("table tbody").append("<tr><td><a href='/info-campanas/view.php?id="+data.id+"' target='_blank'>"+data.name+"</a><div class='image'><img src='"+data.image+"' /></div></td><td>"+data.subject+"</td><td>"+data.send_amt+"</td><td>"+data.uniqueopens+"</td><td>"+data.uniqueopens_percent+"</td><td>"+data.opens+"</td><td>"+data.uniquelinkclicks+"</td><td>"+data.linkclicks+"</td><td>"+data.unsubscribes+"</td></tr>")
            });
            offset = offset + limit;
            /*console.log("offset: "+offset);*/
        },
        error : function(xhr, status) {
            alert('Disculpe, existió un problema');
        },
        complete : function(xhr, status) {
          jQuery("#loading").css("display", "none");
          //jQuery("#loadmore").css("display", "block");
        }
      });
     } else {
      offset = 0;
      jQuery("table tbody").empty();
      loadMore();
    }
  });

  function loadMore() {
    jQuery("#loading").css("display", "block");
    jQuery("#loadmore").css("display", "none");
    jQuery.ajax({
      // la URL para la petición
      url : './ajax.php',
      data : { limit: limit, offset: offset },
      type : 'GET',
      dataType : 'json',
      success : function(json) {
          json.forEach(function(data, index) {
              /* console.log(data); */
              jQuery("table tbody").append("<tr><td><a href='/info-campanas/view.php?id="+data.id+"' target='_blank'>"+data.name+"</a><div class='image'><img src='"+data.image+"' /></div></td><td>"+data.subject+"</td><td>"+data.send_amt+"</td><td>"+data.uniqueopens+"</td><td>"+data.uniqueopens_percent+"</td><td>"+data.opens+"</td><td>"+data.uniquelinkclicks+"</td><td>"+data.linkclicks+"</td><td>"+data.unsubscribes+"</td></tr>")
          });
          offset = offset + limit;
          console.log("offset: "+offset);
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
});
