<?php


require_once('src/headers.php');

function checkIfUserExist($conn, $newUserName){
    
    $newUser = User::loadUserByUsername($conn, $newUserName);
    if($newUser===null){
        return false;
    }else{
        return true;
    }
    
}

function checkIfEmailExist($conn, $newEmail){
    
    $newUser = User::loadUserByEmail($conn, $newEmail);
    if($newUser===null){
        return false;
    }else{
        return true;
    }
    
}

function showTweet($tweets, $i){
    
    echo "<div class='panel panel-default'>
            <div class='panel-heading' style='height:40px;'>";
                
            echo" <span class='navbar-right' style='margin-right:10px;margin-top:0px;'>
                    <div class='dropdown'>
                        <a class='dropdown-toggle' data-toggle='dropdown' href='#'><span class='glyphicon glyphicon-chevron-down'></span></a>
                        <ul class='dropdown-menu' role='menu' aria-labelledby='menu1'>
                            <li role='presentation'><a role='menuitem' tabindex='-1' href='editTweet.php?id={$tweets[$i]->getId_tweet()}'>Edytuj</a></li>
                            <li role='presentation'><a role='menuitem' tabindex='-1' href='deleteTweet.php?id={$tweets[$i]->getId_tweet()}'>Usuń</a></li>
                        </ul>
                    </div>
                </span>
                </div>
                <div class='panel-body'>
                    <div class='media'>
                        <div class='media-left media-top'>
                            <img src='{$tweets[$i]->getImg_src()}' class='media-object' style='width:60px'><center><b>{$tweets[$i]->getUsername()}</b></center>
                        </div>
                        <div class='media-body'>
                            <br>
                            &nbsp;&nbsp;&nbsp;{$tweets[$i]->getTweet()}
                        </div>
                    </div>
                </div>
        </div>";
}

function showTweetTextarea(){
    echo "
            <div class='form-group'>
                <form action='main.php' method='post'>
                    <textarea class='form-control' rows='3' id='comment' name='tweet' placeholder='Write something...'></textarea>
                    <button type='submit' class='btn btn-primary btn-block'>Tweet!</button>
                </form>
            </div><br>
        ";
}


