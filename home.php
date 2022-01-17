<?php
	session_start();
	$uid = 'Guest'; 
	if(isset($_SESSION['uid'])){
		$uid = $_SESSION['uid'];
	} 
	$_SESSION['uid'] = $uid;
	
	if(strcmp($uid, 'Guest') == 0){
		header('Location: loginpage.html');
	}
	
	$mysqli = new mysqli("localhost", "root", "password", "MyDatabase");
	if($SQLi -> connect_errorno){
		echo 'MySQL connect error: '.$SQLi -> connect_errno;
		exit();
	}
	
	echo 'Welcome<br><a href="editprofile.php">edit profile</a><br><br>';
	echo '<a href="logout_process.php">logout</a><br><br>';
	
	echo '<a href="createpost.php">create post</a><br><br>';
	echo 'My 5 recent posts:<hr>';
	$query = "
	SELECT 
		p.postid, p.title AS \"title\",
		DATE_FORMAT(p.postTime, \"%b %d, %Y at %r \") AS \"time\"
	FROM Posts p 
	WHERE p.ownerid = ".$uid."
	ORDER BY p.postTime DESC
	LIMIT 5;";
	$result = $mysqli -> query($query);
	
	if ($result -> num_rows > 0) {
		while($row = $result -> fetch_assoc()) {
			echo '<a href ="post.php?postid='.$row['postid'].'">'.$row['title'].'</a><br>';
			echo 'Posted at '.$row['time'].'<hr>';
		}
	} else {
		echo 'No posts yet...<hr>';
	}
	
	echo '<br><br>Newest Posts:<hr>';
	$query = "
	SELECT 
		p.postid, p.title AS \"title\", u.uid AS \"uid\", u.username,
		DATE_FORMAT(p.postTime, \"%b %d, %Y at %r \") AS \"time\"
	FROM Posts p 
		INNER JOIN Users u
		ON u.uid = p.ownerid
	ORDER BY p.postTime DESC
	LIMIT 15;";
	$result = $mysqli -> query($query);
	
	if ($result -> num_rows > 0) {
		while($row = $result -> fetch_assoc()) {
			echo '<a href ="post.php?postid='.$row['postid'].'">'.$row['title'].'</a><br>';
			echo 'by: <a href ="viewprofile.php?profileid='.$row['uid'].'">'.$row['username'].'</a><br>';
			echo 'Posted at '.$row['time'].'<hr>';
		}
	} else {
		echo 'No posts yet...<hr>';
	}
	
	//this query searches through all posts, posts with tags, and users
	//that contain the search term which is in $searchitem
	//this one searches everything with 'm' in it
	$searchTerm = 'm';
	
	$query = sprintf("
	SELECT 
		p.postid, p.title, u.username, LENGTH(p.title) AS \"Length\" 
	FROM POSTS p 
		INNER JOIN Users u ON u.uid = p.ownerid
	WHERE LOWER(p.title) LIKE LOWER('%%%s%%')
	OR p.postid in (
		SELECT t.postid FROM PostTags t 
		INNER JOIN MetaTags m ON t.tagid = m.tagid 
		WHERE LOWER(m.name) like LOWER('%%%s%%'))
	UNION 
	SELECT 
		uid, 'user', username, LENGTH(username) 
	FROM Users 
	WHERE LOWER(username) LIKE LOWER('%%%s%%')
	ORDER BY LENGTH;", 
	$searchTerm, $searchTerm, $searchTerm);
	
	$result = $mysqli->query($query);
	
	/*
	echo 'Search Results<hr><br>';
	//temp display
	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			if(strcmp($row['title'], 'user') == 0){
				echo 'User: <a href ="post.php?postid='.$row['postid'].'">'.$row['title'].'</a><hr>';
			} else {
				echo '<a href ="post.php?postid='.$row['postid'].'">'.$row['title'].'</a><br>';
				echo 'by: <a href ="viewprofile.php?profileid='.$row['uid'].'">'.$row['username'].'</a><br>';
				echo 'Posted at '.$row['time'].'<hr>';
			}
		}
	} else {
		echo 'no results found for: <br>'.$query.'<br>';
	}
	echo '<br>';
	*/
	
	//then for each post display the tags with this query
	$sql = "
	SELECT p.tagid, m.name FROM PostTags p
		INNER JOIN MetaTags m
		ON p.tagid = m.tagid
	WHERE p.postid = 1;";
	
	echo '<br>View Users:<br><br>';
	$result = $mysqli->query("SELECT * FROM Users;");
	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			echo 'User: <a href ="post.php?postid='.$row['uid'].'">'.$row['username'].'</a><hr>';
		}
	}
	$mysqli->close();
?>