<?php

require_once('src/headers.php');

$id_user = $_SESSION['userId'];
$user = User::loadUserById($conn, $id_user);


echo "
    <div class='jumbotron' style='margin-bottom:0;'>
            <h1>&nbsp;&nbsp;&nbsp;Twitter</h1>
        </div>
<nav class='navbar navbar-default'>
  <div class='container-fluid'>
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class='navbar-header'>
      <button type='button' class='navbar-toggle collapsed' data-toggle='collapse' data-target='#bs-example-navbar-collapse-1' aria-expanded='false'>
        <span class='sr-only'>Toggle navigation</span>
        <span class='icon-bar'></span>
        <span class='icon-bar'></span>
        <span class='icon-bar'></span>
      </button>
      <a class='navbar-brand' href='main.php'>Twitter</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class='collapse navbar-collapse' id='bs-example-navbar-collapse-1'>
      <ul class='nav navbar-nav'>
        
        <li><a href='userTweets.php'>Your tweets</a></li>
        <li class='dropdown'>
          <a href='#' class='dropdown-toggle' data-toggle='dropdown' role='button' aria-haspopup='true' aria-expanded='false'>Settings <span class='caret'></span></a>
          <ul class='dropdown-menu'>
            <li><a href='userSettings.php?user={$user->getUsername()}'>Your profile</a></li>
            <li role='separator' class='divider'></li>
            <li><a href='logout.php'>Logout</a></li>
          </ul>
        </li>
      </ul>
      <form class='navbar-form navbar-right'>
        <div class='form-group'>
          <input type='text' class='form-control' placeholder='Search'>
        </div>
        <button type='submit' class='btn btn-default'>Submit</button>
      </form>
      <ul class='nav navbar-nav navbar-right'>
        <li class='dropdown'>
          <a href='#' class='dropdown-toggle' data-toggle='dropdown' role='button' aria-haspopup='true' aria-expanded='false'>Messages <span class='caret'></span></a>
          <ul class='dropdown-menu'>
            <li style='width: 300px; margin-bottom: 10px; margin-left: 10px;'>
                <small><small><b>Abcdefghijk</b></small></small><br>
                <a href='#'>Separated link</a>
            </li>
            <li role='separator' class='divider'></li>
            <li><a href='messages.php'>Read all messages</a></li>
          </ul>
        </li>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
<br>";