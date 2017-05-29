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


            <?php

            $userConversations = Conversation::loadUsersConversations($conn, $_SESSION['userId'], 5);

            for ($i = 0; $i < count($userConversations); $i++) {
                $id_sender = $userConversations[$i]->getId_sender();
                $id_receiver = $userConversations[$i]->getId_receiver();

                if ($id_sender == $_SESSION['userId']) {
                    $id_sender = $userConversations[$i]->getId_receiver();
                    $username = User::loadUserById($conn, $id_receiver)->getUsername();
                    $userId = $id_receiver;
                } else if ($id_receiver == $_SESSION['userId']) {
                    $id_receiver = $userConversations[$i]->getId_sender();
                    $username = User::loadUserById($conn, $id_sender)->getUsername();
                    $userId = $id_sender;
                }

                $dateTime = $userConversations[$i]->getLastMessageDatetime();
                $lastMesstage = $userConversations[$i]->getLastMessage();

                echo "
                        
                        <!-- Trigger the modal with a button -->
                        <button type='button' class='btn btn-default btn-lg btn-block' data-toggle='modal' data-target='#myModal$i'>
                            
                            <span class='navbar-left' style='margin-left:10px;margin-top:0px;'>
                                <small>$username - $dateTime</small>
                            </span>
                            
                            <span class='navbar-right' style='margin-right:10px;margin-top:0px;'>
                                <small>";

                if ($userConversations[$i]->getLastMessageStatus() == 1 && $userConversations[$i]->getLastMessageSender() != $_SESSION['userId']) {
                    echo "<b>" . $lastMesstage . "</b>";
                } else {
                    echo $lastMesstage;
                }

                echo "</small>
                            </span>
                        
                        </button>

                        <!-- Modal -->
                        <div id='myModal$i' class='modal fade' role='dialog'>
                          <div class='modal-dialog'>


                        <!-- Modal content-->
                        <div class='modal-content'>
                          <div class='modal-header'>
                            <button type='button' class='close' data-dismiss='modal'>&times;</button>
                            <h4 class='modal-title'>Conversation with <b>$username</b></h4>
                          </div>";

                $id_conversation = $userConversations[$i]->getId_conversation();
                $loadedMessages = Messages::loadConversationMessages($conn, $id_conversation);
                showMessages($loadedMessages, $conn);

                echo "<div class='modal-footer'>
                            <button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>
                          </div>
                        </div>
                        

                    </div>
                </div><br><br>
                        ";


//                        echo "<li style='width: 300px; margin-bottom: 10px; margin-left: 10px;'>
//                            <small><small><b>$username</b> - </small>$dateTime</small><br>
//                            <a href='send_message.php?userId=$userId'>";
//
//                            if($userConversations[$i]->getLastMessageStatus()==1 && $userConversations[$i]->getLastMessageSender()!=$_SESSION['userId']){
//                                echo "<b>".$lastMesstage."</b>";
//                            } else {
//                                echo $lastMesstage;
//                            }
//
//
//                            echo "</a>
//                        </li>";
            }

            ?>


        </div>
    </div>
</div>
</body>

</html>