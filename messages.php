<?php
session_start();
require_once('src/headers.php');

if (!isset($_SESSION['isLogged']) || $_SESSION['isLogged'] !== 1) {
    header('Location: index.php');
}

?>
<!DOCTYPE html>

<html lang="pl">
    <head>

        <meta charset="utf-8">
        <title>Twitter</title>
        <meta http-equiv="X-Ua-Compatibile" content="IE=edge,chrome=1">

        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    </head>

    <body>


        <?php require_once('top_nav.php'); ?>
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-mg-3 col-sm-3">
                    <?php require_once('left_bar.php'); ?>
                </div>
                <div class="col-lg-1 col-mg-1 col-sm-1">
                    &nbsp;
                </div>
                <div class="col-lg-6 col-mg-6 col-sm-6">

                    

                </div>
            </div>
        </div>  
    </body>

</html>