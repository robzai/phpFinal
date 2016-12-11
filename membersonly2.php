<?php
ob_start();
session_start();
require_once('db.php');
//var_dump($_SESSION);
//echo "<br>";

if ( isset( $_POST['submit'] ) ){
	$comment = " ";
	if(isset($_POST["comment"]) && !empty($_POST["comment"])){
		$comment = $_POST["comment"];
	}
	$userId = $_SESSION['userId'];	
	$date = date("Y-m-d H:i:s");
	
	//upload comment to database
	$commentId = writeCommentToDB($userId, $comment, $date);
	//if there is a file to upload
	if(!empty($_FILES["fileToUpload"]["name"])){
		$target_dir = "uploads/";
		//$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
		//$fileType = pathinfo($target_file,PATHINFO_EXTENSION);
		$fullPath = $target_dir.$_SESSION['userName']."_".$_FILES["fileToUpload"]["name"];
		//if uploadFile is successful up date the database
		if(uploadFile($fullPath)){
			writePathToDB($userId, $fullPath, $commentId);
		}
	}

}

function writeCommentToDB($userId, $comment, $date){
	global $commentsTable;
	global $db;
	$query = "INSERT INTO $commentsTable values(null, $userId, '" . $comment . "', '" . $date . "')";
	//var_dump($query);
	mysqli_query($db, $query) or die(mysqli_error($db));
	return mysqli_insert_id($db);
}

function writePathToDB($userId, $path, $commentId){
	global $filesTable;
	global $db;
	$query = "INSERT INTO $filesTable values(null, $userId, '" . $path . "', '" . $commentId . "')";
	mysqli_query($db, $query) or die(mysqli_error($db));
}

/*
* http://www.w3schools.com/php/php_file_upload.asp
*/
function uploadFile($fullPath){
	if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $fullPath)) {
		return true;
	} else {
		return false;
	}	
}


ob_end_flush();	
?>
<!DOCTYPE HTML>
<HTML>
   <HEAD>
      <script>		
		function textAreaAdjust(o) {
		  console.log("in js function");
		  o.style.height = "1px";
		  o.style.height = (25+o.scrollHeight)+"px";
		}
	 </script> 
   </HEAD>
   <BODY>
	  <div style='border:1px solid black'>
		  <form action="membersonly2.php" method="post" enctype="multipart/form-data" id="usrform">
			 new message:<br>
			 <textarea onkeyup='textAreaAdjust(this)' rows="4" cols="50" name="comment" 
					form="usrform" style='resize: none;'></textarea><br>
			 attachment:
			 <input type="file" name="fileToUpload" id="fileToUpload"><br>
			 <input type="submit" name="submit">
		  </form>
	  </div>
	  <?php
			ob_start();
			require_once('db.php');
			//var_dump($_SESSION);
			//echo "<br>";

			if(isset($_SESSION['authenticated']) && $_SESSION['authenticated'] == "1"){
				$userName = $_SESSION['userName'];
				//echo "login as " . $userName;
				//echo "<br>";
				getComments($userName);
				echo "<a href='logout.php'>logout</a>";
			}else{
				//print_r($_SESSION);
				header("Location: signInUI.html");
			}

					
			function showComment($userName, $date, $contents){
				echo "<div id='divActivites' name='divActivites' style='border:1px solid black'>
						<div>
							<span>$userName</span>
							<span>$date</span>
						</div>
						<span>
							<textarea readonly autofocus='true' onfocus='textAreaAdjust(this)'
								id='inActivities' name='inActivities' 
								style='border:1px solid black; resize: none; overflow:visible'>$contents</textarea>
					 "; 
			}
			
			function showFile($path){
				if($path == null){
					echo "
						       </span>
					       </div>
					     ";
				}else{
					echo "          <span><a href='$path' download>file</a></span>
						       </span>
					       </div>
					     ";
				}

			}

			function getComments($userName){
				
				global $db;	
				global $usersTable;
				global $commentsTable;
				global $filesTable;
				//get userId for other select or insert to use	
				$selectQuery="select userId from $usersTable where userName = '" . $userName ."'";
				$resultSet = mysqli_query($db, $selectQuery) or die(mysqli_error($db));
				$row = mysqli_fetch_assoc($resultSet);
				$userId = $row["userId"];
				$_SESSION['userId'] = $userId;
				//get comments and files out base the userId
				$selectQuery="
								select $commentsTable.commentId, comment, date, path 
								from $commentsTable left join $filesTable
								ON $commentsTable.commentId = $filesTable.commentId
								where $commentsTable.userId = $userId 
								order by $commentsTable.commentId desc

							 ";
				$resultSet = mysqli_query($db, $selectQuery) or die(mysqli_error($db));
				while($row = mysqli_fetch_assoc($resultSet)){
					//var_dump($row["path"]);
					
					$contents = $row["comment"];
					$date = $row["date"];		
					showComment($userName, $date, $contents);
					showFile($row["path"]);
					/*
					foreach($row as $key=>$col){
						echo "$key-$col ";
					}
					echo "<br>";*/
				}
				
			}
					
			ob_end_flush();	
		?>
   </BODY>
</HTML>
