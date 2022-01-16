<?php
	$servername = 'localhost';
	$username = 'root';
    $password = 'password';
    $dbname = 'mydatabase';
    $SQli = new mysqli($servername, $username, $password, $dbname);

    if($SQLi -> connect_errno) {
      echo 'mySQL CONNECTION FAILURE: '.$SQLi -> connect_errno;
      exit();
    }
    if(empty($_POST['username'])) {
      echo 'You left username blank!';
      exit();
     }
    if(empty($_POST['password'])) {
      echo 'You left password blank!';
      exit();
    }

    $uid = strtolower($_POST['username']);
    $pass = $_POST['password'];

// Insert given info into table


	echo 'Username : '.$uid.' Password: .'.$pass;
	$sql = "INSERT INTO Users (username, password)
			VALUES ('$uid', '$pass')";
	$SQLi -> query($sql);
	$SQLi->close();

?>
