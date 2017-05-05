<?php

class Messages {

    private $id_message;
    private $id_conversation;
    private $message;
    private $dateTime;
    private $id_sender;
    private $id_receiver;

    function __construct() {
        $this->id_message = -1;
    }

    function getId_sender() {
        return $this->id_sender;
    }

    function getId_receiver() {
        return $this->id_receiver;
    }

    function setId_sender($id_sender) {
        $this->id_sender = $id_sender;
    }

    function setId_receiver($id_receiver) {
        $this->id_receiver = $id_receiver;
    }

    function getId_message() {
        return $this->id_message;
    }

    function getId_conversation() {
        return $this->id_conversation;
    }

    function getMessage() {
        return $this->message;
    }

    function getDateTime() {
        return $this->dateTime;
    }

    function setId_conversation($id_conversation) {
        $this->id_conversation = $id_conversation;
    }

    function setMessage($message) {
        $this->message = $message;
    }

    function setDateTime($dateTime) {
        $this->dateTime = $dateTime;
    }

    public function saveToDB(PDO $conn) {
        if ($this->id_message == -1) {
            //Saving new tweet to database

            $stmt = $conn->prepare('INSERT INTO Messages(id_conversation, id_sender, message, dateTime) VALUES (:id_conversation, :sender, :message, :dateTime)');

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
            $stmt = $conn->prepare('UPDATE Messages SET message=:message WHERE id_message=:id_message');
            $result = $stmt->execute([
                'message' => $this->message,
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
        $stmt = $conn->prepare('SELECT id_message, message, dateTime, Messages.id_sender, id_receiver FROM Messages JOIN Conversation ON Messages.id_conversation=Conversation.id_conversation WHERE Conversation.id_conversation=:id_conversation ORDER BY id_message DESC;');
        $stmt->execute(['id_conversation' => $id_conversation]);
        $result = $stmt->fetchAll();

        if ($result !== false && count($result) != 0) {
            foreach ($result as $messageNo => $message) {
                $loadedMessages = new Messages();
                $loadedMessages->id_message = $message['id_message'];
                $loadedMessages->message = $message['message'];
                $loadedMessages->dateTime = $message['dateTime'];
                $loadedMessages->id_sender = $message['id_sender'];
                $loadedMessages->id_receiver = $message['id_receiver'];

                $ret[] = $loadedMessages;
            }
        }
        return $ret;
    }

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
