<?php
session_start();
require_once('src/headers.php');

if (!isset($_SESSION['isLogged']) || $_SESSION['isLogged'] !== 1) {
    header('Location: index.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {


    if (isset($_GET['user'])) {
        $username = $_GET['user'];
        $user = User::loadUserByUsername($conn, $username);

        if ($user->getUsername() === $_SESSION['userName']) {

            $id_user = $user->getId();
            $email = $user->getEmail();
        } else {
            header("Location: main.php");
            return false;
        }
    }
} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //Checking if we recived data by POST
    if (isset($_POST['newUserName']) && isset($_POST['pass']) && isset($_POST['rePass'])) {
        //Checking if two passwords from input are the same

        if (trim($_POST['pass']) === trim($_POST['rePass'])) {
            //Creating new object by username
            $user = User::loadUserByUsername($conn, $_SESSION['userName']);
            //Getting hashed password from database
            $hash = $user->getHashPass();
            $password = $_POST['pass'];
            $newUserName = $_POST['newUserName'];
            //verifying password from input
            if (password_verify($password, $hash)) {
                //We check if such an user already exists
                if (!(checkIfUserExist($conn, $newUserName))) {
                    //If verified, we set new username and save to database
                    $newUserName = $_POST['newUserName'];
                    $user->setUsername($newUserName);
                    $user->saveToDB($conn);
                    //Redirection to login site
                    session_unset();

                    header('Location: index.php');
                } else {
                    echo "User with such name already exists!";
                }
            }
        }
    } else if (isset($_POST['newEmail']) && isset($_POST['pass']) && isset($_POST['rePass'])) {
        if (trim($_POST['pass']) === trim($_POST['rePass'])) {
            //Creating new object by username
            $user = User::loadUserByUsername($conn, $_SESSION['userName']);
            //Getting hashed password from database
            $hash = $user->getHashPass();
            $password = $_POST['pass'];
            $newEmail = $_POST['newEmail'];
            //verifying password from input
            if (password_verify($password, $hash)) {
                //We check if such an email already exists
                if (!(checkIfEmailExist($conn, $newEmail))) {
                    //If verified, we set new username and save to database
                    $user->setEmail($newEmail);
                    $user->saveToDB($conn);
                    //Redirection to login site
                    session_unset();

                    header('Location: index.php');
                } else {
                    echo "Email adress already exists!";
                }
            }
        }
    }if (isset($_POST['newPass']) && isset($_POST['reNewPass']) && isset($_POST['oldPass'])) {
        if (trim($_POST['newPass']) === trim($_POST['reNewPass'])) {
            //Creating new object by username
            $user = User::loadUserByUsername($conn, $_SESSION['userName']);
            //Getting hashed password from database
            $hash = $user->getHashPass();
            $password = $_POST['oldPass'];
            $newPass = trim($_POST['newPass']);
            //verifying password from input
            if (password_verify($password, $hash)) {
                //If verified, we set new username and save to database
                $options = ['cost' => 11];
                $hashPass = password_hash($newPass, PASSWORD_BCRYPT, $options);
                $user->setHashPass($hashPass);
                $user->saveToDB($conn);
                
                header('Location: main.php');
            }
        }
    }
}
?>
<!DOCTYPE html>

<html lang="pl">
    <head>

        <meta charset="utf-8">
        <title>Twitter</title>
        <meta http-equiv="X-Ua-Compatibile" content="IE=edge,chrome=1">

        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    </head>

    <body>


        <?php require_once('top_nav.php'); ?>
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-mg-3 col-sm-3">
                    <?php require_once('left_bar.php'); ?>
                </div>
                <div class="col-lg-1 col-mg-1 col-sm-1">
                    &nbsp;
                </div>
                <div class="col-lg-6 col-mg-6 col-sm-6">



                    <h2>User settings</h2>
                    <br>


                    <div class="panel-group">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" href="#collapse1">Username: <?php echo $user->getUsername(); ?></a>
                                </h4>
                            </div>
                            <div id="collapse1" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <h4>Change username</h4>
                                    <br>
                                    <div class="form-group">
                                        <form method="post" action="">
                                            <label for="usr">New username:
                                                <input type="text" class="form-control" id="newUserName" name="newUserName"></label><br>
                                            <label for="usr">Enter password:
                                                <input type="password" class="form-control" id="pass" name="pass"></label><br>
                                            <label for="usr">Retype password:
                                                <input type="password" class="form-control" id="rePass" name="rePass"></label>
                                            <button type="submit" class="btn btn-default">Set new username</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="panel-group">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" href="#collapse2">Email: <?php echo $user->getEmail(); ?></a>
                                </h4>
                            </div>
                            <div id="collapse2" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <h4>Change email</h4>
                                    <br>
                                    <div class="form-group">
                                        <form method="post" action="">
                                            <label for="usr">New email:
                                                <input type="text" class="form-control" id="newEmail" name="newEmail"></label><br>
                                            <label for="usr">Enter password:
                                                <input type="password" class="form-control" id="pass" name="pass"></label><br>
                                            <label for="usr">Retype password:
                                                <input type="password" class="form-control" id="rePass" name="rePass"></label>
                                            <button type="submit" class="btn btn-default">Set new email</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-group">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" href="#collapse3">Password</a>
                                </h4>
                            </div>
                            <div id="collapse3" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <h4>Change password</h4>
                                    <br>
                                    <div class="form-group">
                                        <form method="post" action="">
                                            <label for="usr">New password:
                                                <input type="password" class="form-control" id="newPass" name="newPass"></label><br>
                                            <label for="usr">Retype new password:
                                                <input type="password" class="form-control" id="reNewPass" name="reNewPass"></label><br>
                                            <label for="usr">Old password:
                                                <input type="password" class="form-control" id="oldPass" name="oldPass"></label>
                                            <button type="submit" class="btn btn-default">Set new password</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>





                </div>
            </div>
        </div>  
    </body>

</html>