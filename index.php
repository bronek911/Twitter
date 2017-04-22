<?php
session_start();
require_once('src/headers.php');

if(isset($_SESSION['isLogged']) && $_SESSION['isLogged']==1){
    header('Location: main.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['login']) && $_POST['login'] == 1) {
        //Login
        //Checking if values sent by POST are set
        if (isset($_POST['username']) && isset($_POST['password'])) {
            
            //Getting values from POST to variable
            $username = trim($_POST['username']);
            $password = trim($_POST['password']);
            unset($_POST['password']);
            
            //Creating object of class User
            $user = User::loadUserByUsername($conn, $username);
            //Getting users hased password from the database
            $hash = $user->getHashPass();
            
            //Verifying if password from the form is same as in database
            if (password_verify($password, $hash)) { //If it is
                //Saving info about user to session variables
                $_SESSION['isLogged'] = 1;
                $_SESSION['userId'] = $user->getId();
                $_SESSION['userName'] = $user->getUsername();
                $_SESSION['userEmail'] = $user->getEmail();
//                echo '<pre>';
//                print_r($_SESSION);
//                echo '</pre>';
//                die();
                //Redirects us to file main.php
                header('Location: main.php');
            } else { //If isn't
                //header('Location: index.php');
                
            }
        } else {
            
        }
    } else if (isset($_POST['login']) && $_POST['login'] == 0) {
        //Registration
        try {

            if (isset($_POST['pass']) && isset($_POST['rePass']) && $_POST['pass'] === $_POST['rePass']) {

                $user = new User;

                $user->setUsername(trim($_POST['user']));
                $user->setEmail(trim($_POST['email']));
                $options = ['cost' => 11];
                $hashPass = password_hash(trim($_POST['pass']), PASSWORD_BCRYPT, $options);
                $user->setHashPass($hashPass);
                $user->saveToDB($conn);

                header('Location: welcome.php');

                return true;
            } else {
                throw new Exception('You have to write twice the same password!');
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html >
    <head>
        <meta charset="UTF-8">
        <title>Day 001 Login Form</title>


        <link rel='stylesheet prefetch' href='http://fonts.googleapis.com/css?family=Open+Sans:600'>

        <link rel="stylesheet" href="css/style.css">


    </head>

    <body>
        <div class="login-wrap">
            <div class="login-html">
                <input id="tab-1" type="radio" name="tab" class="sign-in" checked><label for="tab-1" class="tab">Sign In</label>
                <input id="tab-2" type="radio" name="tab" class="sign-up"><label for="tab-2" class="tab">Sign Up</label>
                <div class="login-form">
                    <div class="sign-in-htm">
                        <form action='index.php' method='post'>
                            <div class="group">
                                <label for="user" class="label">Username</label>
                                <input id="user" name="username" type="text" class="input">
                            </div>
                            <div class="group">
                                <label for="pass" class="label">Password</label>
                                <input id="pass" name="password" type="password" class="input" data-type="password">
                            </div>
                            <div class="group" style="display:none;">
                                <label for="pass" class="label"></label>
                                <input id="login1" name="login" value="1" type="hidden" class="input" data-type="password">
                            </div>
                            <!--<div class="group">
                                    <input id="check" type="checkbox" class="check" checked>
                                    <label for="check"><span class="icon"></span> Keep me Signed in</label>
                            </div>-->
                            <br><br>
                            <div class="group">
                                <input type="submit" class="button" value="Sign In">
                            </div>
                            <div class="hr"></div>
                            <!--<div class="foot-lnk">
                                    <a href="#forgot">Forgot Password?</a>
                            </div>-->
                        </form>
                    </div>
                    <div class="sign-up-htm">
                        <form action="index.php" method="post">
                            <div class="group">
                                <label for="user" class="label">Username</label>
                                <input id="userR" name="user" type="text" class="input">
                            </div>
                            <div class="group">
                                <label for="pass" class="label">Email Address</label>
                                <input id="emailR" name="email" type="text" class="input">
                            </div>
                            <div class="group">
                                <label for="pass" class="label">Password</label>
                                <input id="passR" name="pass" type="password" class="input" data-type="password">
                            </div>
                            <div class="group">
                                <label for="pass" class="label">Repeat Password</label>
                                <input id="rePassR" name="rePass" type="password" class="input" data-type="password">
                            </div>

                            <div class="group" style="display:none;">
                                <label for="pass" class="label">Password</label>
                                <input id="login0" name="login" value="0" type="hidden" class="input" data-type="password">
                            </div>
                            <br><br>
                            <div class="group">
                                <input type="submit" class="button" value="Sign Up">
                            </div>
                        </form>
                        <div class="hr"></div>
                        <div class="foot-lnk">
                            <label for="tab-1">Already Member?</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </body>
</html>
