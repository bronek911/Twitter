<?php

class Conversation {

    private $id_conversation;
    private $id_sender;
    private $id_receiver;
    
    private $senderUsername;
    private $receiverUsername;
    private $lastMessage;
    private $lastMessageSender;
    private $lastMessageDatetime;
    private $lastMessageStatus;

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
    
    function getSenderUsername() {
        return $this->senderUsername;
    }

    function getReceiverUsername() {
        return $this->receiverUsername;
    }

    function getLastMessage() {
        return $this->lastMessage;
    }

    function getLastMessageDatetime() {
        return $this->lastMessageDatetime;
    }

    function getLastMessageStatus() {
        return $this->lastMessageStatus;
    }
    function getLastMessageSender() {
        return $this->lastMessageSender;
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

    static public function loadUsersConversations(PDO $conn, $id_user, $limit) {
        $ret = [];
        $stmt = $conn->prepare('
            SELECT 
                c.id_conversation, 
                s.id AS senderId, 
                s.username AS sender,  
                r.id AS receiverId,
                r.username AS receiver 
            FROM conversation c 
                JOIN users s ON c.id_sender=s.id 
                JOIN users r ON c.id_receiver=r.id 
            WHERE 
                s.id=:id_user OR 
                r.id=:id_user
            ORDER BY c.id_conversation DESC
            ;');
        $stmt->execute(['id_user' => $id_user, 'limit'=>$limit]);
        $result = $stmt->fetchAll();

        if ($result !== false && count($result) != 0) {
            foreach ($result as $conversationNo => $conversation) {
                                
                $loadedConversations = new Conversation();
                $loadedConversations->id_conversation = $conversation['id_conversation'];
                $loadedConversations->id_sender = $conversation['senderId'];
                $loadedConversations->senderUsername = $conversation['sender'];
                $loadedConversations->id_receiver = $conversation['receiverId'];
                $loadedConversations->receiverUsername = $conversation['sender'];
                
                $lastMessage = Messages::loadLastConversationMessages($conn, $conversation['id_conversation']);
                $loadedConversations->lastMessage = $lastMessage->getMessage();
                $loadedConversations->lastMessageDatetime = $lastMessage->getDateTime();
                $loadedConversations->lastMessageStatus = $lastMessage->getStatus();
                $loadedConversations->lastMessageSender = $lastMessage->getId_sender();
                
                
//                echo '<pre>';
//                print_r($lastMessage);
//                echo '</pre>';
//                die();

                $ret[] = $loadedConversations;
            }
        }
        return $ret;
    }

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
