<?php
echo "<div class='panel panel-default'>
  <div class='panel-heading'>";

$user = User::loadUserByUsername($conn, $_SESSION['userName']);
echo $user->getUsername();

echo "</div>
  <div class='panel-body'>";

echo $user->getEmail();

echo "</div>
</div>";
