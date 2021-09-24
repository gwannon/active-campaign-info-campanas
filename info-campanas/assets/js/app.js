jQuery(document).ready(function() {
  loadMore();

  jQuery("#loadmore").click(function(e) {
    e.preventDefault();
    loadMore();
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
          jQuery("table tbody").append("<tr><td>"+data.name+"</td><td>"+data.subject+"</td><td>"+data.send_amt+"</td><td>"+data.uniqueopens+"</td><td>"+data.opens+"</td><td>"+data.uniquelinkclicks+"</td><td>"+data.linkclicks+"</td><td>"+data.unsubscribes+"</td></tr>")
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