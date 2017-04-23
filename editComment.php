<?php
session_start();
require_once('src/headers.php');

if (!isset($_SESSION['isLogged']) || $_SESSION['isLogged'] !== 1) {
    header('Location: index.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['comment'])) {
        $id_comment = $_POST['id_comment'];
        $id_user = $_SESSION['userId'];
        $id_tweet = $_POST['id_tweet'];
        $comment = trim($_POST['comment']);
        $dateTimeNow = new DateTime("now");
        $format = "Y-m-d H:i:s";
        $dateTime = $dateTimeNow->format($format);
        

        if (strlen($comment) > 0) {
            $newComment = Comment::loadCommentById($conn, $id_comment);
            $newComment->setCommentText($comment);
            $newComment->saveToDB($conn);
            header('Location: userTweets.php');
        }
    } else {
        echo "Brak danych!";
    }
} else if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    
    if (isset($_GET['id'])) {
        $id_comment = $_GET['id'];
        $comment = Comment::loadCommentById($conn, $id_comment);

        if ($comment->getId_user() === $_SESSION['userId']) {
            
            $id_tweet = $comment->getId_tweet();
            $id_user = $comment->getId_user();
            $commentText = $comment->getCommentText();
            $dateTime = $comment->getDateTime();
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

                    $comment = Comment::loadCommentById($conn, $id_comment);

                    echo "
            <div class='form-group'>
                <form action='' method='post'>
                    <div class='panel panel-default'>
                        <div class='panel-body'>ID:{$comment->getId_comment()}&nbsp;&nbsp;&nbsp;<b>{$comment->getUsername()}</b> - utworzono: {$comment->getDateTime()}</div>
                      
                    <textarea class='form-control' rows='5' id='comment' name='comment' style='margin-top:0px;'>" . $commentText . "</textarea>
                    <input type='hidden' name='id_comment' value='{$comment->getId_comment()}'></input>
                    <input type='hidden' name='id_tweet' value='{$comment->getId_tweet()}'></input>
                    <button type='submit' class='btn btn-primary btn-block'>Save comment!</button>
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