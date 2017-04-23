<?php

class Comment {

    private $id_comment;
    private $id_tweet;
    private $id_user;
    private $commentText;
    private $dateTime;
    private $username;

    function __construct() {
        $this->id_comment = -1;
    }

    function getId_comment() {
        return $this->id_comment;
    }

    function getId_tweet() {
        return $this->id_tweet;
    }

    function getId_user() {
        return $this->id_user;
    }

    function getCommentText() {
        return $this->commentText;
    }

    function getDateTime() {
        return $this->dateTime;
    }

    function setId_tweet($id_tweet) {
        $this->id_tweet = $id_tweet;
    }

    function setId_user($id_user) {
        $this->id_user = $id_user;
    }

    function setCommentText($commentText) {
        $this->commentText = $commentText;
    }

    function setDateTime($dateTime) {
        $this->dateTime = $dateTime;
    }

    function getUsername() {
        return $this->username;
    }

    public function saveToDB(PDO $conn) {
        if ($this->id_comment == -1) {
            //Saving new comment to database

            $stmt = $conn->prepare('INSERT INTO comments (id_tweet, id_user, commentText, dateTime) VALUES (:id_tweet, :id_user, :commentText, :dateTime);');
                        
            $result = $stmt->execute([
                'id_tweet' => $this->id_tweet,
                'id_user' => $this->id_user,
                'commentText' => $this->commentText,
                'dateTime' => $this->dateTime
            ]);
            
            
            if ($result !== false) {
                $this->id_comment = $conn->lastInsertId();
                return true;
            }
        } else {
            $stmt = $conn->prepare('UPDATE Comments SET commentText=:commentText WHERE id_comment=:id_comment');
            $result = $stmt->execute([
                'commentText' => $this->commentText,
                'id_comment' => $this->id_comment
            ]);

            if ($result === true) {
                return true;
            }
        }return false;
    }

    static public function loadCommentById(PDO $conn, $id_comment) {
        $stmt = $conn->prepare('SELECT id_comment, id_tweet, id_user, commentText, dateTime, username FROM Comments JOIN Users ON Comments.id_user=Users.id WHERE Comments.id_comment=:id_comment;');
        $result = $stmt->execute(['id_comment' => $id_comment]);

        if ($result === true && $stmt->rowCount() > 0) {
            $row = $stmt->fetch();

            $loadedComment = new Comment();
            $loadedComment->id_comment = $row['id_comment'];
            $loadedComment->id_tweet = $row['id_tweet'];
            $loadedComment->id_user = $row['id_user'];
            $loadedComment->commentText = $row['commentText'];
            $loadedComment->dateTime = $row['dateTime'];
            $loadedComment->username = $row['username'];

            return $loadedComment;
        } else {
            return null;
        }
    }

    static public function loadCommentsByTweetId(PDO $conn, $id_tweet) {
        $stmt = $conn->prepare('SELECT id_comment, id_tweet, id_user, commentText, dateTime, username FROM Comments JOIN Users ON Comments.id_user=Users.id WHERE Comments.id_tweet=:id_tweet;');
        $stmt->execute(['id_tweet' => $id_tweet]);
        $ret = [];
        $result = $stmt->fetchAll();
        if ($stmt->rowCount() > 0) {
            foreach ($result as $row) {
                $loadedComments = new Comment();
                $loadedComments->id_comment = $row['id_comment'];
                $loadedComments->id_tweet = $row['id_tweet'];
                $loadedComments->id_user = $row['id_user'];
                $loadedComments->commentText = $row['commentText'];
                $loadedComments->dateTime = $row['dateTime'];
                $loadedComments->username = $row['username'];
                $ret[] = $loadedComments;
            }
            return $ret;
        } else {
            return null;
        }
    }

    static public function loadAllComments(PDO $conn) {
        $sql = 'SELECT id_comment, id_tweet, id_user, commentText, dateTime, username FROM Comments JOIN Users ON Comments.id_user=Users.id ORDER BY id_tweet DESC;';
        $ret = [];

        $result = $conn->query($sql);
        if ($result !== false && $result->rowCount() != 0) {
            foreach ($result as $row) {
                $loadedComments = new Tweets();
                $loadedComments->id_comment = $row['id_comment'];
                $loadedComments->id_tweet = $row['id_tweet'];
                $loadedComments->id_user = $row['id_user'];
                $loadedComments->commentText = $row['commentText'];
                $loadedComments->dateTime = $row['dateTime'];
                $loadedComments->username = $row['username'];

                $ret[] = $loadedComments;
            }
        }
        return $ret;
    }
    
    public function delete(PDO $conn) {
        if ($this->id_comment != -1) {
            $stmt = $conn->prepare('DELETE FROM Comments WHERE id_comment=:id_comment');
            $result = $stmt->execute(['id_comment' => $this->id_comment]);

            if ($result === true) {
                $this->id_comment = -1;
                return true;
            }
            return false;
        }
        return true;
    }

}
