<?php
session_start();
require_once('src/headers.php');

//if(isset($_SESSION['islogged']) && $_SESSION['islogged']!==1){
//    header('Location: index.php');
//}

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
echo "
            <div class='form-group'>
                <form action='userTweets.php' method='post'>
                    <textarea class='form-control' rows='5' id='comment' name='tweet' placeholder='Write something...'></textarea>
                    <button type='submit' class='btn btn-primary btn-block'>Tweet!</button>
                </form>
            </div><br>
        ";

//Displaying tweets

$tweets = Tweets::loadAllUserTweets($conn, $_SESSION['userId']);

for ($i = 0; $i < count($tweets); $i++) {

    echo "<div class='panel panel-default'>
  <div class='panel-heading'>";
    echo $tweets[$i]->getUsername();
    echo" 
  <span class='navbar-right' style='margin-right:10px;margin-top:0px;'>
                    <div class='dropdown'>
                        <a class='dropdown-toggle' data-toggle='dropdown' href='#'><span class='glyphicon glyphicon-chevron-down'></span></a>
                        <ul class='dropdown-menu' role='menu' aria-labelledby='menu1'>
                            <li role='presentation'><a role='menuitem' tabindex='-1' href='editTweet.php?id={$tweets[$i]->getId_tweet()}'>Edytuj</a></li>
                            <li role='presentation'><a role='menuitem' tabindex='-1' href='deleteTweet.php?id={$tweets[$i]->getId_tweet()}'>Usuń</a></li>
                        </ul>
                    </div>
                </span>
                </div>
  <div class='panel-body'>";
    echo $tweets[$i]->getTweet();
    echo"</div>
</div>";
}
?>

                </div>
            </div>
        </div>  
    </body>

</html>