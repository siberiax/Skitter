<?php

include_once("common.php");

echo $_SESSION['userID'];
$userID = $_SESSION['userID'];

$file_exts = array("jpg", "bmp", "jpeg", "gif", "png");
$tmp = explode(".", $_FILES["file"]["name"]);
$upload_exts = strtolower(end($tmp));
if (in_array($upload_exts, $file_exts)){
	echo "Upload: " . $_FILES["file"]["name"] . "<br>";
	echo "Type: " . $_FILES["file"]["type"] . "<br>";
	echo "Size: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
	echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br>";

	move_uploaded_file($_FILES["file"]["tmp_name"], "/var/www/html/images/" . $userID);
}

/*
$id_to_get = $_SESSION['userID'];

if(isset($_POST['profileImage'])){
	$prep = "UPDATE user SET displayName=? WHERE id=?";
	if($stmt = $mysqli->prepare($prep)){
		if($stmt->bind_param("si",$_POST['profileImage'], $_SESSION['userID'])){
			if(!$stmt->execute()){
				die("Error - Issue executing prepared statement: " . mysqli_error($mysqli));
			}
		}else{
			die("Error - Issue binding prepared statement: " . mysqli_error($mysqli));
		}
		if($stmt->close()){
			echo "Value updated succesfully";
		}else{
			die("Error - Failed to close prepared statement" . mysqli_error($mysqli));
		}
	}else{
		die("Error - Issue preparing statement: " . mysqli_error($mysqli));
	}
}
?>
*/
