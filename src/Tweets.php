<?php

class Tweets {

    private $id_tweet;
    private $id_user;
    private $tweet;
    private $dateTime;
    private $username;

    function __construct() {
        $this->id_tweet = -1;
    }

    function getId_tweet() {
        return $this->id_tweet;
    }

    function getId_user() {
        return $this->id_user;
    }

    function getTweet() {
        return $this->tweet;
    }

    function getDateTime() {
        return $this->dateTime;
    }
    
    function getUsername() {
        return $this->username;
    }

    function setUsername($username) {
        $this->username = $username;
        return $this;
    }

        function setId_user($id_user) {
        $this->id_user = $id_user;
        return $this;
    }

    function setTweet($tweet) {
        $this->tweet = $tweet;
        return $this;
    }

    function setDateTime($dateTime) {
        $this->dateTime = $dateTime;
        return $this;
    }

    public function saveToDB(PDO $conn) {
        if ($this->id_tweet == -1) {
            //Saving new tweet to database

            $stmt = $conn->prepare('INSERT INTO Tweets(id_user, tweet, dateTime) VALUES (:id_user, :tweet, :dateTime)');

            $result = $stmt->execute([
                'id_user' => $this->id_user,
                'tweet' => $this->tweet,
                'dateTime' => $this->dateTime
            ]);

            if ($result !== false) {
                $this->id = $conn->lastInsertId();
                return true;
            }
        } else {
            $stmt = $conn->prepare('UPDATE Tweets SET tweet=:tweet WHERE id_tweet=:id_tweet');
            $result = $stmt->execute([
                'tweet' => $this->tweet,
                'id_tweet' => $this->id_tweet
            ]);

            if ($result === true) {
                return true;
            }
        }
        return false;
    }

    static public function loadTweetById(PDO $conn, $id_tweet) {
        $stmt = $conn->prepare('SELECT id_tweet, id_user, tweet, dateTime, username FROM Tweets JOIN users ON Tweets.id_user=users.id WHERE Tweets.id_tweet=:id_tweet;');
        $result = $stmt->execute(['id_tweet' => $id_tweet]);

        if ($result === true && $stmt->rowCount() > 0) {
            $row = $stmt->fetch();

            $loadedTweet = new Tweets();
            $loadedTweet->id_tweet = $row['id_tweet'];
            $loadedTweet->id_user = $row['id_user'];
            $loadedTweet->username = $row['username'];
            $loadedTweet->tweet = $row['tweet'];
            $loadedTweet->dateTime = $row['dateTime'];


            return $loadedTweet;
        } else {
            return null;
        }
    }

    static public function loadAllTweets(PDO $conn) {
        $sql = 'SELECT id_tweet, id_user, tweet, dateTime, username FROM Tweets JOIN users ON Tweets.id_user=users.id ORDER BY id_tweet DESC;';
        $ret = [];

        $result = $conn->query($sql);
        if ($result !== false && $result->rowCount() != 0) {
            foreach ($result as $row) {
                $loadedTweets = new Tweets();
                $loadedTweets->id_tweet = $row['id_tweet'];
                $loadedTweets->id_user = $row['id_user'];
                $loadedTweets->username = $row['username'];
                $loadedTweets->tweet = $row['tweet'];
                $loadedTweets->dateTime = $row['dateTime'];

                $ret[] = $loadedTweets;
            }
        }
        return $ret;
    }
    
    static public function loadAllUserTweets(PDO $conn, $id_user) {
        $ret = [];
        $stmt = $conn->prepare('SELECT id_tweet, id_user, tweet, dateTime, username FROM Tweets JOIN users ON Tweets.id_user=users.id WHERE Tweets.id_user=:id_user ORDER BY Tweets.id_tweet DESC;');
        $stmt->execute(['id_user' => $id_user]);
        $result = $stmt->fetchAll();
        
        
        if ($result !== false && count($result) != 0) {
            foreach ($result as $tweetNo => $tweet) {
                $loadedTweets = new Tweets();
                $loadedTweets->id_tweet = $tweet['id_tweet'];
                $loadedTweets->id_user = $tweet['id_user'];
                $loadedTweets->username = $tweet['username'];
                $loadedTweets->tweet = $tweet['tweet'];
                $loadedTweets->dateTime = $tweet['dateTime'];

                $ret[] = $loadedTweets;
            }
        }
        return $ret;
    }

    public function delete(PDO $conn) {
        if ($this->id_tweet != -1) {
            $stmt = $conn->prepare('DELETE FROM Tweets WHERE id_tweet=:id_tweet');
            $result = $stmt->execute(['id_tweet' => $this->id_tweet]);

            if ($result === true) {
                $this->id_tweet = -1;
                return true;
            }
            return false;
        }
        return true;
    }

}
