<?php

session_start();
require_once('src/headers.php');

if (!isset($_SESSION['isLogged']) || $_SESSION['isLogged'] !== 1) {
    header('Location: index.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    if (isset($_GET['id'])) {
        $id_comment = $_GET['id'];
        $comment = Comment::loadCommentById($conn, $id_comment);
        if ($comment->getId_user() === $_SESSION['userId']) {

            $comment->delete($conn);
            
        }
    } else {
        echo "Brak danych!";
    }
    header('Location: main.php');
}