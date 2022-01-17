<?php
  session_start();
  $_SESSION['uid'] = 'Guest';
  header('Location: loginpage.html');
?>
