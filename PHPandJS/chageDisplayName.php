<?php
include_once("common.php");

if (!isset($_COOKIE["JSESSIONID"]) or !isset($_SESSION['userID'])){
	echo "Invalid Session";
}

//ip needs to be changed
$url = "http://localhost/isAuthenticated?sessionId=" . $_COOKIE["JSESSIONID"];
$contents = file_get_contents($url);
if (strpos($contents, '200') == false){
  echo 'Invalid Session';
}

$id_to_get = $_SESSION['userID'];
print_r($_POST);
if(isset($_POST['displayName'])){
	$prep = "UPDATE user SET displayName=? WHERE id=?";
	if($stmt = $mysqli->prepare($prep)){
		if($stmt->bind_param("si",$_POST['displayName'], $_SESSION['user_id'])){
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
