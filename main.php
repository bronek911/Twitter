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
            header('Location: main.php');
        }
    } else if(isset($_POST['comment'])){
        
        $id_user = $_SESSION['userId'];
        $id_tweet = $_POST['id_tweet'];
        $commentText = trim($_POST['comment']);
        $dateTimeNow = new DateTime("now");
        $format = "Y-m-d H:i:s";
        $dateTime = $dateTimeNow->format($format);

        if (strlen($commentText) > 0) {
            $newComment = new Comment();
            
            $newComment->setId_tweet($id_tweet);
            $newComment->setId_user($id_user);
            $newComment->setCommentText($commentText);
            $newComment->setDateTime($dateTime);
                    
            $newComment->saveToDB($conn);
            
            
            
            header('Location: main.php');
        }
    }
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
                    //Form for adding a tweet
                    showTweetTextarea();

                 //Displaying tweets

                    $tweets = Tweets::loadAllTweets($conn);

                    for ($i = 0; $i < count($tweets); $i++) {
                        showTweet($tweets, $i, $conn);
                    }
                    ?>

                </div>
            </div>
        </div>  
    </body>

</html>