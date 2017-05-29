<?php
require_once('src/headers.php');

$id_user = $_SESSION['userId'];
$user = User::loadUserById($conn, $id_user);


echo "
    <div class='jumbotron' style='margin-bottom:0;'>
            <h1>&nbsp;&nbsp;&nbsp;Twitter</h1>
        </div>
<nav class='navbar navbar-default'>
  <div class='container-fluid'>
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class='navbar-header'>
      <button type='button' class='navbar-toggle collapsed' data-toggle='collapse' data-target='#bs-example-navbar-collapse-1' aria-expanded='false'>
        <span class='sr-only'>Toggle navigation</span>
        <span class='icon-bar'></span>
        <span class='icon-bar'></span>
        <span class='icon-bar'></span>
      </button>
      <a class='navbar-brand' href='main.php'>Twitter</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class='collapse navbar-collapse' id='bs-example-navbar-collapse-1'>
      <ul class='nav navbar-nav'>
        
        <li><a href='userTweets.php'>Your tweets</a></li>
        <li class='dropdown'>
          <a href='#' class='dropdown-toggle' data-toggle='dropdown' role='button' aria-haspopup='true' aria-expanded='false'>Settings <span class='caret'></span></a>
          <ul class='dropdown-menu'>
            <li><a href='userSettings.php?user={$user->getUsername()}'>Your profile</a></li>
            <li role='separator' class='divider'></li>
            <li><a href='logout.php'>Logout</a></li>
          </ul>
        </li>
      </ul>
      <form class='navbar-form navbar-right'>
        <div class='form-group'>
          <input type='text' class='form-control' placeholder='Not working (yet)'>
        </div>
        <button type='submit' class='btn btn-default'>Submit</button>
      </form>
      <ul class='nav navbar-nav navbar-right'>
        <li class='dropdown'>
          <a href='#' class='dropdown-toggle' data-toggle='dropdown' role='button' aria-haspopup='true' aria-expanded='false'>Messages <span class='caret'></span></a>
          <ul class='dropdown-menu'>";
          
            $userConversations = Conversation::loadUsersConversations($conn, $_SESSION['userId'], 5);
            
            for($i = 0; $i < count($userConversations); $i++){
                $id_sender = $userConversations[$i]->getId_sender();
                $id_receiver = $userConversations[$i]->getId_receiver();
                
                if($id_sender == $_SESSION['userId']){
                    $id_sender = $userConversations[$i]->getId_receiver();
                    $username = User::loadUserById($conn, $id_receiver)->getUsername();
                    $userId = $id_receiver;
                } else if($id_receiver == $_SESSION['userId']){
                    $id_receiver = $userConversations[$i]->getId_sender();
                    $username = User::loadUserById($conn, $id_sender)->getUsername();
                    $userId = $id_sender;
                }
                
                $dateTime = $userConversations[$i]->getLastMessageDatetime();
                $lastMesstage = $userConversations[$i]->getLastMessage();
                
                echo "<li style='width: 300px; margin-bottom: 10px; margin-left: 10px;'>
                    <small><small><b>$username</b> - </small>$dateTime</small><br>
                    <a href='send_message.php?userId=$userId'>";
                    
                    if($userConversations[$i]->getLastMessageStatus()==1 && $userConversations[$i]->getLastMessageSender()!=$_SESSION['userId']){
                        echo "<b>".$lastMesstage."</b>";
                    } else {
                        echo $lastMesstage;
                    }
                
                    
                    echo "</a>
                </li>";
            }
            
            echo "<li role='separator' class='divider'></li>
            
            <li><a href='messages.php'>Read all messages</a></li>
            

          </ul>
        </li>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
<br>";