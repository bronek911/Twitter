<?php
session_start();
require_once('src/headers.php');

if (!isset($_SESSION['isLogged']) || $_SESSION['isLogged'] !== 1) {
    header('Location: index.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['tweet'])) {
        $id_user = $_SESSION['userId'];
        $tweet = trim($_POST['tweet']);
        $dateTimeNow = new DateTime("now");
        $format = "Y-m-d H:i:s";

        if (strlen($tweet) > 0) {
            $newTweet = new Tweets();
            $newTweet->setId_user($id_user)->setTweet($tweet)->setDateTime($dateTimeNow->format($format));
            $newTweet->saveToDB($conn);
            header('Location: userTweets.php');
        }
    } else {
        echo "Brak danych!";
    }
} else if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    
    if (isset($_GET['id'])) {
        $id_tweet = $_GET['id'];
        $tweet = Tweets::loadTweetById($conn, $id_tweet);

        if ($tweet->getUsername() === $_SESSION['userName']) {
            
            $id_user = $tweet->getId_User();
            $tweetText = $tweet->getTweet();
            $dateTime = $tweet->getDateTime();
            $username = $tweet->getUsername();
        }else{
            
            return false;
        }
    }
} else {
    
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

                    <?php
//Editing tweet

                    $tweet = Tweets::loadTweetById($conn, $id_tweet);

                    echo "
            <div class='form-group'>
                <form action='userTweets.php' method='post'>
                    <div class='panel panel-default'>
                        <div class='panel-body'>ID:{$tweet->getId_tweet()}&nbsp;&nbsp;&nbsp;<b>{$tweet->getUsername()}</b> - utworzono: {$tweet->getDateTime()}</div>
                      
                    <textarea class='form-control' rows='5' id='comment' name='tweet' style='margin-top:0px;'>" . $tweetText . "</textarea>
                    <button type='submit' class='btn btn-primary btn-block'>Save tweet!</button>
                    </div>
                </form>
            </div><br>
        ";
                    ?>

                </div>
            </div>
        </div>  
    </body>

</html>