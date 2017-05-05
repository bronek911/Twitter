<?php

class Conversation {

    private $id_conversation;
    private $id_sender;
    private $id_receiver;

    public function __construct() {
        $this->id_conversation = -1;
    }

    function getId_conversation() {
        return $this->id_conversation;
    }

    function getId_sender() {
        return $this->id_sender;
    }

    function getId_receiver() {
        return $this->id_receiver;
    }

    public function setId_sender($id_sender) {
        $this->id_sender = $id_sender;
    }

    public function setId_receiver($id_receiver) {
        $this->id_receiver = $id_receiver;
    }

    public function saveToDB(PDO $conn) {
        if ($this->id_conversation == -1) {
            //Saving new tweet to database

            $stmt = $conn->prepare('INSERT INTO Conversation(id_sender, id_receiver) VALUES (:id_sender, :id_receiver)');

            $result = $stmt->execute([
                'id_sender' => $this->id_sender,
                'id_receiver' => $this->id_receiver,
            ]);

            if ($result !== false) {
                $this->id_conversation = $conn->lastInsertId();
                return true;
            }
        } else {

        }
        return false;
    }

//    static public function loadUsersConversations(PDO $conn, $id_user) {
//        $ret = [];
//        $stmt = $conn->prepare('SELECT * FROM Conversations WHERE id_sender=:id_user OR id_receiver=:id_user;');
//        $stmt->execute(['id_user' => $id_user]);
//        $result = $stmt->fetchAll();
//
//        if ($result !== false && count($result) != 0) {
//            foreach ($result as $conversationNo => $conversation) {
//                $loadedConversations = new Conversation();
//                $loadedMessages->id_conversation = $message['id_conversation'];
//                $loadedMessages->id_sender = $message['id_sender'];
//                $loadedMessages->id_receiver = $message['id_receiver'];
//
//                $ret[] = $loadedConversations;
//            }
//        }
//        return $ret;
//    }

    static public function checkConversationId(PDO $conn, $id_sender, $id_receiver) {

        $stmt = $conn->prepare('SELECT * FROM Conversation WHERE (id_sender=:id_sender AND id_receiver=:id_receiver) OR (id_sender=:id_receiver AND id_receiver=:id_sender);');
        $result = $stmt->execute([
            'id_sender' => $id_sender,
            'id_receiver' => $id_receiver,
        ]);

        if ($result !== false && count($result) != 0) {
            $row = $stmt->fetch();

            $id_conversation = $row['id_conversation'];
            
            return $id_conversation;
        }
        return null;
    }

}
