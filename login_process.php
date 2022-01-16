<?php

	include 'setup.php'

	$servername = 'localhost';
	$username = 'root';
	$password = 'password';
	$dbname = 'mydatabase';
	$SQLi = new mysqli($servername, $username, $password, $dbname);
	
	if($SQLi -> connect_errorno){
		echo 'MySQL connect error: '.$SQLi -> connect_errno;
		exit();
	}
	
	if(empty($_POST['username'])){
		echo 'Username left blank';
		exit();
	}
	if(empty($_POST['password'])){
		echo 'You left password blank!';
		exit();
	}

	$uid = strtolower($_POST['username']);
	$pass = $_POST['password'];
	
	$result = $SQLcon -> query("SELECT * FROM Users");
	if($result -> num_rows > 0) {
    while($row = $result -> fetch_assoc()) {
     // echo "Username: ".$uid.' '.$row["username"].':'.$pass.' '.$row["password"].'<br>';
      if((strcmp($uid, $row["username"]) && strcmp($pass, $row["password"])) == 0) {
        session_start();
        $_SESSION['uid'] = $uid;
        header('Location: logstatus.php');
      }
    }
    echo 'Invalid username and/or password.';
    exit();
	}
	else echo 'USERS IS EMPTY';

  $SQLi->close();

?>