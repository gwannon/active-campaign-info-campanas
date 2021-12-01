<?php include_once("config.php"); ?><html>
<head>
  <title>INFO CAMPAÑAS</title>
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
  <link href="https://fonts.googleapis.com/css2?family=Ubuntu:wght@300;400;500;700&display=swap" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
  <script>
    var limit = <?php echo AC_API_LIMIT; ?>;
  </script>
  <script src="./assets/js/app.js"></script>
  <style>
    body {
      font-family: 'Ubuntu', cursive;
    }
    td {
      position: relative;
    }

    td > div.image {
      display: none;
      position: absolute;
      top: 100%;
      left: 40%;
      height: 300px;
      overflow: hidden;
      z-index: 10;
      border: 1px solid #000;
      min-width: 250px;
    }

    td:hover > div.image {
      display: block;
    }

    td > div.image img {
      width: 100%;
    }
  </style>
</head>
<body>
  <section>
    <div class="container-fluid">
      <div class="row">
        <div class="col-12 p-0">
          <table class="table table-striped">
            <thead class="table-dark">
              <tr>
                <th colspan="11">
                  <form id="search" class="row m-0">
                    <div class="col-auto"><input class="form-control" type="text" name="search" value="" /></div>
                    <div class="col-auto"><button class="btn btn-secondary">Buscar</button></div>
                    <div class="col-auto"><a href="./csv.php" class="btn btn-secondary">Exportar a CSV</a></div>
                  </form>
                </th>
              </tr>
              <tr>
                <th scope="col">Nombre</th>
                <th scope="col">Fecha</th>
                <th scope="col">Título</th>
                <th scope="col">Enviados</th>
                <th scope="col">Aperturas únicas</th>
                <th scope="col">Porcentaje de aperturas únicas</th>
                <th scope="col">Aperturas</th>
                <th scope="col">Clicks únicos</th>
                <th scope="col">Porcentaje de clicks únicos</th>
                <th scope="col">Clicks totales</th>
                <th scope="col">Bajas</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
        <div class="col-12 p-3">
          <div id="loading" style="display: none;">Cargando ...</div>
          <button id="loadmore" class="btn btn-primary" style="display: none;">Cargar más</button>
        </div>      
      </div>
    </div>
  </section>
</body>
</html>
