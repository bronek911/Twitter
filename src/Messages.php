<?php

class Messages {

    private $id_message;
    private $id_conversation;
    private $message;
    private $dateTime;
    private $status;
    private $id_sender;
    private $id_receiver;

    public function __construct() {
        $this->id_message = -1;
    }

    public function getId_sender() {
        return $this->id_sender;
    }

    public function getId_receiver() {
        return $this->id_receiver;
    }

    public function setId_sender($id_sender) {
        $this->id_sender = $id_sender;
    }

    public function setId_receiver($id_receiver) {
        $this->id_receiver = $id_receiver;
    }

    public function getId_message() {
        return $this->id_message;
    }

    public function getId_conversation() {
        return $this->id_conversation;
    }

    public function getMessage() {
        return $this->message;
    }

    public function getDateTime() {
        return $this->dateTime;
    }

    public function setId_conversation($id_conversation) {
        $this->id_conversation = $id_conversation;
    }

    public function setMessage($message) {
        $this->message = $message;
    }

    public function setDateTime($dateTime) {
        $this->dateTime = $dateTime;
    }
    public function getStatus() {
        return $this->status;
    }

    public function setStatus($status) {
        $this->status = $status;
    }

    public function saveToDB(PDO $conn) {
        if ($this->id_message == -1) {
            //Saving new tweet to database

            $stmt = $conn->prepare('INSERT INTO Messages(id_conversation, id_sender, message, dateTime, status) VALUES (:id_conversation, :sender, :message, :dateTime, 1)');

            $result = $stmt->execute([
                'id_conversation' => $this->id_conversation,
                'message' => $this->message,
                'sender' => $this->id_sender,
                'dateTime' => $this->dateTime,
            ]);

            if ($result !== false) {
                $this->id_message = $conn->lastInsertId();
                return true;
            }
        } else {
            $stmt = $conn->prepare('UPDATE Messages SET message=:message, status=:status WHERE id_message=:id_message');
            $result = $stmt->execute([
                'message' => $this->message,
                'status' => $this->status,
                'id_message' => $this->id_message,
            ]);

            if ($result === true) {
                return true;
            }
        }
        return false;
    }

    static public function loadConversationMessages(PDO $conn, $id_conversation) {
        $ret = [];
        $stmt = $conn->prepare(' 
                SELECT c.id_conversation, m.id_message, m.message, m.dateTime, m.id_sender, m.status 
                FROM Messages m
                    JOIN Conversation c ON m.id_conversation=c.id_conversation 
                WHERE c.id_conversation=:id_conversation 
                ORDER BY m.id_message DESC;
                ');
        $stmt->execute(['id_conversation' => $id_conversation]);
        $result = $stmt->fetchAll();

        if ($result !== false && count($result) != 0) {
            foreach ($result as $messageNo => $message) {
                $loadedMessages = new Messages();
                $loadedMessages->id_conversation = $message['id_conversation'];
                $loadedMessages->id_message = $message['id_message'];
                $loadedMessages->message = $message['message'];
                $loadedMessages->dateTime = $message['dateTime'];
                $loadedMessages->id_sender = $message['id_sender'];
                $loadedMessages->status = $message['status'];

                $ret[] = $loadedMessages;
            }
        }
        return $ret;
    }
    
    static public function loadLastConversationMessages(PDO $conn, $id_conversation) {
        
        $stmt = $conn->prepare('
            SELECT dateTime, message, status, m.id_sender AS sender
            FROM Messages m 
                JOIN Conversation c ON m.id_conversation=c.id_conversation 
            WHERE c.id_conversation=:id_conversation 
            ORDER BY dateTime DESC
            LIMIT 1;
            ');
        $result = $stmt->execute(['id_conversation' => $id_conversation]);
        
        if ($result === true && $stmt->rowCount() > 0) {
            $row = $stmt->fetch();
            
            $loadedMessage = new Messages();
            $loadedMessage->message = $row['message'];
            $loadedMessage->dateTime = $row['dateTime'];
            $loadedMessage->status = $row['status'];
            $loadedMessage->id_sender = $row['sender'];
            
            return $loadedMessage;
        }
        return null;
    }

//    static public function countUnreadMessages(PDO $conn, $id_conversation) {
//        
//        $stmt = $conn->prepare('
//            SELECT COUNT(*)
//            FROM Messages m
//                JOIN conversation c ON m.id_conversation=c.id_conversation
//            WHERE status=1
//            ');
//        $result = $stmt->execute(['id_conversation' => $id_conversation]);
//        
//        if ($result === true && $stmt->rowCount() > 0) {
//            $row = $stmt->fetch();
//            
//            $loadedMessage = new Messages();
//            $loadedMessage->message = $row['message'];
//            $loadedMessage->dateTime = $row['dateTime'];
//            $loadedMessage->status = $row['status'];
//            $loadedMessage->id_sender = $row['sender'];
//            
//            return $loadedMessage;
//        }
//        return null;
//    }
    
    public function delete(PDO $conn) {
        if ($this->id_tweet != -1) {
            $stmt = $conn->prepare('DELETE FROM Messages WHERE id_message=:id_message');
            $result = $stmt->execute(['id_message' => $this->id_message]);

            if ($result === true) {
                $this->id_message = -1;
                return true;
            }
            return false;
        }
        return true;
    }

}
