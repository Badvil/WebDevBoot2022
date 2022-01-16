<?php
    if(empty($_POST['username'])) {
		echo 'You left the username blank!';
		exit_process();
    }
	 
	if(empty($_POST['email'])) {
		echo 'You left the email blank!';
		exit_process();
    }
    if(empty($_POST['password'])) {
		echo 'You left the password blank!';
		exit_process();
    }
	
	$servername = 'localhost';
	$username = 'root';
    $password = 'password';
    $dbname = 'mydatabase';
    $SQli = new mysqli($servername, $username, $password, $dbname);

    if($SQLi -> connect_errno) {
      echo 'mySQL CONNECTION FAILURE: '.$SQLi -> connect_errno;
      exit();
    }

    $uname = trim(strtolower($_POST['username']));
	$email = trim($_POST['email']);
	
	if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
		echo 'invalid email';
		exit_process();
	}
    $pass = $_POST['password'];
	
	"INSERT INTO Users 
		(username, email, password, userBio) VALUES
		('Bob', 'bob@hotmail.com', '123',
		'Hello my name is Bob and I like to talk about etc etc etc...');";
	
	/*
	$query = sprintf(
	"INSERT INTO Users (username, email, password) VALUES ('%s', '%s', '%s');", 
	$uname, $email, $pass);*/
	
	$query = "
	INSERT INTO Users 
	(username, email, password) VALUES 
	('".$uname."', '".$email."', '".$pass."');";
	
	echo $query." Query does not seem to run!<br>";
	
	if(!mysqli_query($SQLi, $query)){
		echo "query error: ". mysqli_error($SQLi);
	}
	
	echo 'You have successfully created your account<br>';
	echo '<a href="loginpage.php">login page</a>';
	
	$SQLi->close();
	
	function exit_process(){
		echo 'Click <a href="signup.html">here</a> to go to the signup page<br>';
		exit();
	}
?>
