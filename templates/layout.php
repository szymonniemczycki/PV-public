<html lang="pl">

  <head>
    <title>RCE importer</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css?ver=5">
    <link href="./public/style.css" rel="stylesheet">
  </head>

  <body class="body">
    <div class="wrapper">

      <div class="header">
        <h1><i class="far fa-chart-bar"></i>Rynkowa Cena Energii</h1>
        <div class="logout"><a href="./logout.php">logout</a></div>
      </div>

      <div class="container">
        <div class="menu">
          <ul>
            <li><a <?php echo $active = ($page=="main") ? 'class="active"' : null; ?> href="./">Main</a></li>
            <li><a <?php echo $active = ($page=="prices") ? 'class="active"' : null; ?> href="./?page=prices">Ceny</a></li>
            <li><a <?php echo $active = ($page=="import") ? 'class="active"' : null; ?> href="./?page=import">Import</a></li>
            <li><a <?php echo $active = ($page=="logs") ? 'class="active"' : null; ?> href="./?page=logs">Logi</a></li>
            <li><a <?php echo $active = ($page=="errors") ? 'class="active"' : null; ?> href="./?page=errors">Errors</a></li>
          </ul>
        </div>

        <div class="page">
          <?php require_once("templates/pages/$page.php"); ?>
        </div>
      </div>

      <div class="footer">
        <p>RCE importer - PHP</p>
      </div>
    </div>

  </body>

</html>