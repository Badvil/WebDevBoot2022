<?php
	if(empty($_POST['username'])){
		echo 'Username left blank<br>';
		exit_process();
	}
	if(empty($_POST['password'])){
		echo 'You left password blank!<br>';
		exit_process();
	}
	
	$servername = 'localhost';
	$username = 'root';
	$password = 'password';
	$dbname = 'mydatabase';
	$SQLi = new mysqli($servername, $username, $password, $dbname);
	
	if($SQLi -> connect_errorno){
		echo 'MySQL connect error: '.$SQLi -> connect_errno;
		exit();
	}

	$uid = strtolower($_POST['username']);
	$pass = $_POST['password'];
	
	$query = sprintf("SELECT uid, password FROM Users WHERE LOWER(username) = '%s';",
			mysqli_real_escape_string($SQLi, $uid));
	$result = mysqli_query($SQLi, $query);

	// code below by caldwell-matthew
	if($result -> num_rows > 0) {
        $row = $result -> fetch_assoc();
		if (strcmp($pass, $row["password"]) == 0){
			session_start();
			$_SESSION['uid'] = $row['uid'];
			header('Location: home.php');
		}
		/*while($row = $result -> fetch_assoc()) {
        if(strcmp($uid, $row["username"])== 0 && strcmp($pass, $row["password"]) == 0) {
          session_start();
          $_SESSION['uid'] = $uid;
          header('Location: logstatus.php');
      }*/
    }

	$SQLi->close();
	
	exit_process();
	
	function exit_process(){
		echo 'login failed<br>';
		echo '<a href="loginpage.html">back to login page</a>';
		exit();
	}
?>
