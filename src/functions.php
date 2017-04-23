<?php

include('src/headers.php');

function checkIfUserExist($conn, $newUserName) {

    $newUser = User::loadUserByUsername($conn, $newUserName);
    if ($newUser === null) {
        return false;
    } else {
        return true;
    }
}

function checkIfEmailExist($conn, $newEmail) {

    $newUser = User::loadUserByEmail($conn, $newEmail);
    if ($newUser === null) {
        return false;
    } else {
        return true;
    }
}

function showTweet($tweets, $i, $conn) {
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
                <div class='panel-footer'>
                    <h4 class='panel-title'>
                        <a data-toggle='collapse' href='#collapse{$tweets[$i]->getId_tweet()}'><small>
                            Comments
                        </small></a>
                    </h4>
                </div>
                <div id='collapse{$tweets[$i]->getId_tweet()}' class='panel-collapse collapse'>
                    <ul class='list-group'>";
    echo showCommentsByTweet($tweets[$i], $conn);

    echo "</ul>
                    <div class='panel-footer'>";
    showCommentTextarea($tweets[$i]->getId_tweet());
    echo "</div>
                </div>
        </div>";
}

function showTweetTextarea() {
    echo "
            <div class='form-group'>
                <form action='' method='post'>
                    <textarea class='form-control' rows='3' id='comment' name='tweet' placeholder='Write something...'></textarea>
                    <button type='submit' class='btn btn-primary btn-block'>Tweet!</button>
                </form>
            </div><br>
        ";
}

function showCommentTextarea($id_tweet) {
    echo "
            <div class='form-group'>
                <form action='' method='post'>
                    <input typr='text' class='form-control' id='comment' name='comment' placeholder='Comment...'></input>
                    <input type='hidden' name='id_tweet' value='{$id_tweet}'></input>
                    <button type='submit' class='btn btn-primary btn-block'>Add comment</button>
                </form>
            </div>
        ";
}

function showCommentsByTweet(Tweets $tweet, $conn) {
    $comments = Comment::loadCommentsByTweetId($conn, $tweet->getId_tweet());

    for ($i = 0; $i < count($comments); $i++) {
        echo "
        <li class='list-group-item'>
            <small><small><b>{$comments[$i]->getUsername()} - {$comments[$i]->getDateTime()}</b>";
            if($_SESSION['userId'] === $comments[$i]->getId_user()){
                echo " - 
                <a href='editComment.php?id={$comments[$i]->getId_comment()}'>Edit</a>
                <a href='deleteComment.php?id={$comments[$i]->getId_comment()}'>Delete</a>";
            }
            echo "</small></small><br>
            {$comments[$i]->getCommentText()}
        </li>
    ";
    }
}
