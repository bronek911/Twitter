<?php

class User {

    private $id;
    private $username;
    private $email;
    private $hashPass;
    private $img_src;

    function __construct() {
        $this->id = -1;
        $this->username = '';
        $this->email = '';
        $this->hashPass = '';
    }

    public function getId() {
        return $this->id;
    }

    public function getUsername() {
        return $this->username;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getHashPass() {
        return $this->hashPass;
    }

    function getImg_src() {
        return $this->img_src;
    }

    function setImg_src($img_src) {
        $this->img_src = $img_src;
    }

    public function setUsername($username) {

        function filterName($name, $filter = "[^a-zA-Z0-9\-\_\.]") {
            return preg_match("~" . $filter . "~iU", $name) ? false : true;
        }

        if (strlen($username) >= 20 || strlen($username) < 4) {
            throw new Exception('Allowed length of username if between 4 and 20 characters!');
        } else if (!ctype_alnum($username)) {
            throw new Exception('Only alphanumeric characters!');
        } else {
            $this->username = $username;
            return $this;
        }
    }

    public function setEmail($email) {
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('Email format not valid!');
        } else {
            $this->email = $email;
            return $this;
        }
    }

    function setHashPass($hashPass) {
        $this->hashPass = $hashPass;
        return $this;
    }

    public function saveToDB(PDO $conn) {
        if ($this->id == -1) {
            //Saving new user to database

            $stmt = $conn->prepare('INSERT INTO Users(username, email, hash_pass) VALUES (:username, :email, :pass)');

            $result = $stmt->execute([
                'username' => $this->username,
                'email' => $this->email,
                'pass' => $this->hashPass
            ]);

            if ($result !== false) {
                $this->id = $conn->lastInsertId();
                return true;
            }
        } else {
            $stmt = $conn->prepare('UPDATE Users SET username=:username, email=:email, hash_pass=:hash_pass, img_src=:img_src WHERE id=:id');
            $result = $stmt->execute([
                'username' => $this->username,
                'email' => $this->email,
                'img_src' => $this->img_src,
                'hash_pass' => $this->hashPass,
                'id' => $this->id
            ]);

            if ($result === true) {
                return true;
            }
        }
        return false;
    }

    static public function loadUserById(PDO $conn, $id) {
        $stmt = $conn->prepare('SELECT * FROM Users WHERE id=:id');
        $result = $stmt->execute(['id' => $id]);

        if ($result === true && $stmt->rowCount() > 0) {
            $row = $stmt->fetch();

            $loadedUser = new User();
            $loadedUser->id = $row['id'];
            $loadedUser->username = $row['username'];
            $loadedUser->hashPass = $row['hash_pass'];
            $loadedUser->email = $row['email'];
            $loadedUser->img_src = $row['img_src'];

            return $loadedUser;
        } else {
            return null;
        }
    }

    static public function loadUserByUsername(PDO $conn, $username) {
        $stmt = $conn->prepare('SELECT * FROM Users WHERE username=:username');
        $result = $stmt->execute(['username' => $username]);

        if ($result === true && $stmt->rowCount() > 0) {
            $row = $stmt->fetch();

            $loadedUser = new User();
            $loadedUser->id = $row['id'];
            $loadedUser->username = $row['username'];
            $loadedUser->hashPass = $row['hash_pass'];
            $loadedUser->email = $row['email'];
            $loadedUser->img_src = $row['img_src'];

            return $loadedUser;
        } else {
            return null;
        }
    }

    static public function loadUserByEmail(PDO $conn, $email) {
        $stmt = $conn->prepare('SELECT * FROM Users WHERE email=:email');
        $result = $stmt->execute(['email' => $email]);

        if ($result === true && $stmt->rowCount() > 0) {
            $row = $stmt->fetch();

            $loadedUser = new User();
            $loadedUser->id = $row['id'];
            $loadedUser->username = $row['username'];
            $loadedUser->hashPass = $row['hash_pass'];
            $loadedUser->email = $row['email'];
            $loadedUser->img_src = $row['img_src'];

            return $loadedUser;
        } else {
            return null;
        }
    }

    static public function loadAllUsers(PDO $conn) {
        $sql = 'SELECT * FROM Users';
        $ret = [];

        $result = $conn->query($sql);
        if ($result !== false && $result->rowCount() != 0) {
            foreach ($result as $row) {
                $loadedUser = new User();
                $loadedUser->id = $row['id'];
                $loadedUser->username = $row['username'];
                $loadedUser->hashPass = $row['hash_pass'];
                $loadedUser->email = $row['email'];
                $loadedUser->img_src = $row['img_src'];

                $ret[] = $loadedUser;
            }
        }
        return $ret;
    }

    public function delete(PDO $conn) {
        if ($this->id != -1) {
            $stmt = $conn->prepare('DELETE FROM Users WHERE id=:id');
            $result = $stmt->execute(['id' => $this->id]);

            if ($result === true) {
                $this->id = -1;
                return true;
            }
            return false;
        }
        return true;
    }

}
