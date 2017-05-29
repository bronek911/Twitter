<?php
session_start();
require_once('src/headers.php');

if (!isset($_SESSION['isLogged']) || $_SESSION['isLogged'] !== 1) {
    header('Location: index.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['message'])) {
        
        $id_sender = $_SESSION['userId'];
        $id_receiver = $_GET['userId'];
        $message = trim($_POST['message']);
        $dateTimeNow = new DateTime("now");
        $format = "Y-m-d H:i:s";
        
        if(Conversation::checkConversationId($conn, $id_sender, $id_receiver)===null){
            $newConversation = new Conversation();
            $newConversation->setId_sender($id_sender);
            $newConversation->setId_receiver($id_receiver);
            $newConversation->saveToDB($conn);
        }
            
        $id_conversation = Conversation::checkConversationId($conn, $id_sender, $id_receiver);

        if (strlen($message) > 0) {
            $newMessage = new Messages();
            $newMessage->setId_conversation($id_conversation);
            $newMessage->setId_sender($id_sender);
            $newMessage->setId_receiver($id_receiver);
            $newMessage->setMessage($message);
            $newMessage->setDateTime($dateTimeNow->format($format));
            $newMessage->saveToDB($conn);
            header('Location: send_message.php?userId='.$id_receiver);
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

                    <form action='' method='post'>
                        <textarea class='form-control' rows='3' id='message' name='message' placeholder='Write a message...'></textarea>
                        <button type='submit' class='btn btn-primary btn-block'>Send message!</button>
                    </form>
                    <br>
                    <?php
                        $id_conversation = Conversation::checkConversationId($conn, $_SESSION['userId'], $_GET['userId']);
                                              
                        $loadedMessages = Messages::loadConversationMessages($conn, $id_conversation);
                        
                        
                        
                        showMessages($loadedMessages, $conn);
                    ?>
                </div>
            </div>
        </div>  
    </body>

</html>