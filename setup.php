<?php
	
	//----------------------------------------------------------------
	// WARNING!!
	
	echo 'WARNING: Running this script will completely reset the database<br>';
	
	// WARNING!!
	//----------------------------------------------------------------
	
	// Also if the SQL queries don't seem to be working try going to 
	// phpMyAdmin and going to user accounts and giving ALL "Data"
	// and ALL "Structure" privileges to any users but only for test purposes
	
	// Since the database is reset after the script is ran we can make changes
	// easily to the database

	$mysqli = new mysqli("localhost", "root", "password", "MyDatabase");
	if($msqli -> connect_erno){
		echo 'failed to connect to MySQL '.$mysqli -> connect_error;
		exit();
	} else {
		echo 'connected to Database<br>';
	}
	
	$resetDB = '';
	$resetDB .= "DROP TABLE IF EXISTS PostTags;";
	$resetDB .= "DROP TABLE IF EXISTS MetaTags;";
	$resetDB .= "DROP TABLE IF EXISTS PostComments;";
	$resetDB .= "DROP TABLE IF EXISTS Posts;";
	$resetDB .= "DROP TABLE IF EXISTS Users;";
	
	//we drop the tables to remove the old schema and any data there
	run_queries($mysqli, $resetDB, 'Dropped all tables');
	
	$makeDB = "";
	$makeDB .= "
		CREATE TABLE Users (
			username 	VARCHAR(25) NOT NULL,
			password 	VARCHAR(25) NOT NULL,
			uid 		INT(16) NOT NULL AUTO_INCREMENT,
			privilege 	INT DEFAULT 0,
			userBio 	VARCHAR(2048),
			PRIMARY KEY (uid)
		);";
	$makeDB .= "
		CREATE TABLE Posts (
			postid 		INT NOT NULL AUTO_INCREMENT,
			ownerid 	INT(16) NOT NULL,
			title 		VARCHAR(64) NOT NULL,
			postTime 	DATETIME,
			lastUpdate 	DATETIME,
			postContent VARCHAR(4096) NOT NULL,
			PRIMARY KEY (postid),
			FOREIGN KEY (ownerid) REFERENCES Users (uid)
		);";
		
	$makeDB .= "
		CREATE TABLE PostComments (
			commentid 	INT NOT NULL AUTO_INCREMENT,
			ownerid 	INT(16) NOT NULL,
			postid 		INT(16) NOT NULL,
			postTime 	DATETIME,
			lastUpdate 	DATETIME,
			postContent VARCHAR(2048) NOT NULL,
			PRIMARY KEY (commentid),
			FOREIGN KEY (ownerid) REFERENCES Users (uid),
			FOREIGN KEY (postid) REFERENCES Posts (postid)
		);";
	
	// The Meta tags will allow us to label posts with certain tags
	// if we don't have enough time we can forego this feature
	// MetaTags stores the tags
	// PostTags is the table that say which tags belong to which tables
	$makeDB .= "
		CREATE TABLE MetaTags (
			tagid 		INT NOT NULL AUTO_INCREMENT,
			name 		VARCHAR(32),
			PRIMARY KEY (tagid)
		);";
	$makeDB .= "
		CREATE TABLE PostTags (
			tagid 		INT NOT NULL,
			postid 		INT NOT NULL, 
			FOREIGN KEY (tagid) REFERENCES MetaTags (tagid), 
			FOREIGN KEY (postid) REFERENCES Posts (postid)
	);";
		
	run_queries($mysqli, $makeDB, 'Created tables');
		
	$populateDB = "";
	
	// populate the user table first and the tables that dont rely on data
	// from other tables (ie tables with no foreign keys first) same thing
	// with dropping tables but in the opposite order
	
	//Populate user tables
	//note here we don't need to insert uid for user and privilege for
	//basic accounts
	$populateDB .= 
		"INSERT INTO Users 
		(username, password, privilege, userBio) VALUES
		('Admin', 'root123', 2, 'Hello I am an administrator for the website.');";
	$populateDB .= 
		"INSERT INTO Users 
		(username, password, privilege, userBio) VALUES
		('Moderator', 'PASSWORD', 1, 'This is the moderator profile.');";
	$populateDB .= 
		"INSERT INTO Users 
		(username, password, userBio) VALUES
		('Bob', '123', 'Hello my name is Bob and I like to talk about etc etc etc...');";
	
	//Populate MetTags table
	$populateDB .= "INSERT INTO MetaTags (name) VALUES('video games');";
	$populateDB .= "INSERT INTO MetaTags (name) VALUES('computers');";	
	$populateDB .= "INSERT INTO MetaTags (name) VALUES('crypto');";
		
	//Populate Posts table
	$populateDB .= 
		"INSERT INTO Posts 
		(ownerid, title, postTime, lastUpdate, postContent) VALUES
		(1, 'Hello world', '2022-1-02 15:20:00', CURDATE(), 'This is the very first post');";
	$populateDB .= 
		"INSERT INTO Posts 
		(ownerid, title, postTime, lastUpdate, postContent) VALUES
		(1, 'Roblox', '2022-1-05 08:05:00', '2022-1-05 9:15:00', 'I like to play Roblox');";
	$populateDB .= 
		"INSERT INTO Posts 
		(ownerid, title, postTime, lastUpdate, postContent) VALUES
		(3, 'I stole 8 billion worth of bitcoin', '2022-1-10 22:45:00', '2022-1-10 22:45:00', 'This is how I stole 8 billion in bitcoin. Do not tell the IRS.etc...');";
	
	//Populate PostTags
	$populateDB .= "INSERT INTO PostTags (tagid, postid) VALUES (1, 2);";
	$populateDB .= "INSERT INTO PostTags (tagid, postid) VALUES (2, 3);";
	$populateDB .= "INSERT INTO PostTags (tagid, postid) VALUES (3, 3);";
	
	//Populate PostComments
	$populateDB .= "
		INSERT INTO PostComments
		(ownerid, postid, postTime, lastUpdate, postContent) VALUES
		(2, 1, '2022-1-02 15:26:00', '2022-1-02 15:37:00', 'Hello World. Note: edited');";
	$populateDB .= "
		INSERT INTO PostComments
		(ownerid, postid, postTime, lastUpdate, postContent) VALUES
		(3, 1, '2022-1-02 17:26:00', '2022-1-02 17:26:00', 'Hello also!!!!!!');";
	$populateDB .= "
		INSERT INTO PostComments
		(ownerid, postid, postTime, lastUpdate, postContent) VALUES
		(3, 2, '2022-1-05 12:56:00', '2022-1-02 12:56:00', 'I also like to play Roblox.');";
	$populateDB .= "
		INSERT INTO PostComments
		(ownerid, postid, postTime, lastUpdate, postContent) VALUES
		(1, 3, '2022-1-12 08:36:00', '2022-1-12 09:36:00', 'I am telling the IRS.');";
	$populateDB .= "
		INSERT INTO PostComments
		(ownerid, postid, postTime, lastUpdate, postContent) VALUES
		(2, 3, '2022-1-12 08:39:00', '2022-1-12 08:39:00', 'Haha very funny!');";
	$populateDB .= "
		INSERT INTO PostComments
		(ownerid, postid, postTime, postContent) VALUES
		(2, 3, '2022-1-12 09:15:00', 'This is a comment without update time.');";	
	
	run_queries($mysqli, $populateDB, 'Populated tables');
	
	echo 'example queries<br><br>';
	
	$sql = "SELECT * FROM Users";
	print_query($mysqli, $sql, 'printing all users<br>');
	
	$sql = "SELECT * FROM POSTS WHERE postid = 3;";
	print_query($mysqli, $sql, 'printing A specific post though its not very neat<br>');
	
	// we can also select data from multiple tables and combine the rows, the different types of joins
	// tell us which rows to keep and combine together. For the most part we are probably just using this
	// inner join
	
	$sql = "
	SELECT p.ownerid, p.postContent, u.username
	FROM PostComments p 
		INNER JOIN Users u
		ON u.uid = p.ownerid
	WHERE postid = 3;";
	print_query($mysqli, $sql, '..Then printing the comments along with the owner username from the user table<br>');
	
	$sql = "
	SELECT p.tagid, m.name FROM PostTags p
		INNER JOIN MetaTags m
		ON p.tagid = m.tagid
	WHERE p.postid = 3;";
	print_query($mysqli, $sql, '..Then printing the tags, pulling data from 2 tables together<br>');
	
	
	$mysqli->close();
	echo 'end of setup<br>';
	
	function run_queries($mysqli, $query, $success_msg){
		mysqli_multi_query($mysqli, $query);
		while ($mysqli->next_result()) {
			if (!$mysqli->more_results()) break;
		}
		echo $success_msg.'<br>';
	}
	
	function print_query($mysqli, $query, $text){
		echo $text.'<br>';
		$result = $mysqli -> query($query);
		if ($result->num_rows > 0) {
			 while($row = $result->fetch_assoc()) {
				//echo $row[0].'<br>';
				foreach($row as $cname => $cvalue){
					echo $cname.': '.$cvalue.'|   ';
				}
				echo'<br>';
			}
		} else {
			echo 'no results found for: <br>'.$query.'<br>';
		}
		echo '<br>';
	}
?>
