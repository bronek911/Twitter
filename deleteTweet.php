<?php

session_start();
require_once('src/headers.php');

if (!isset($_SESSION['isLogged']) || $_SESSION['isLogged'] !== 1) {
    header('Location: index.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    if (isset($_GET['id'])) {
        $id_tweet = $_GET['id'];
        $tweet = Tweets::loadTweetById($conn, $id_tweet);
        if ($tweet->getUsername() === $_SESSION['userName']) {

            $tweet->delete($conn);
            
        }
    } else {
        echo "Brak danych!";
    }
    header('Location: main.php');
}