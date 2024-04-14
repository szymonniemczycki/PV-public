<head>
    <title>RCE importer</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css">
    <link href="./csstemplate/style.css" rel="stylesheet">
    <?php
        if (!empty($_SESSION['userName'])) {
            echo '<meta http-equiv="refresh" content="301">';
        } 
    ?>
</head>