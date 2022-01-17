<?php
	// This file is meant for displaying profiles
	
	// the url should look like viewprofile.php?profileid=x
	// with x being the profileid number, the db is populated with ids 1, 2, 3
	
	if(empty($_GET['profileid']) or !is_numeric($_GET['profileid'])){
		echo 'No user found<br>';
		exit();
	}
	
	$profileid = $_GET['profileid'];
	
	session_start();
	$uid = 'Guest'; 
	if(isset($_SESSION['uid'])){
		$uid = $_SESSION['uid'];
	} 
	$_SESSION['uid'] = $uid;
	
	if($uid == $profileid){
		header('Location: editprofile.php');
	}
	echo '<a href="home.php">home</a><br><br>';
	
	$mysqli = new mysqli("localhost", "root", "password", "MyDatabase");
	
	if($mysqli -> connect_erno){
		echo 'failed to connect to MySQL '.$mysqli -> connect_error;
		exit();
	}
	
	$profileQuery = "SELECT username, userBio FROM Users WHERE uid = ".$profileid.";";
	$profileResult = $mysqli -> query($profileQuery);
	
	if($profileResult -> num_rows > 0){
		$row = $profileResult -> fetch_assoc();
		
		echo $row['username'].'<br>';
		echo 'Bio:<br>'.$row['userBio'].'<br>';
		
		echo 'Posts by '.$row['username'].':<hr>';
		$query = "
		SELECT 
			p.postid, p.title AS \"title\",
			DATE_FORMAT(p.postTime, \"%b %d, %Y at %r \") AS \"time\"
		FROM Posts p 
		WHERE p.ownerid = ".$profileid."
		ORDER BY p.postTime DESC;";
		$result = $mysqli -> query($query);
	
		if ($result -> num_rows > 0) {
			while($row = $result -> fetch_assoc()) {
				echo '<a href ="post.php?postid='.$row['postid'].'">'.$row['title'].'</a><br>';
				echo 'Posted at '.$row['time'].'<hr>';
			}
		} else {
			echo 'No posts made yet<hr>';
		}
		
	} else {
		echo 'No user found<br>';
	}

	$mysqli->close();
?>