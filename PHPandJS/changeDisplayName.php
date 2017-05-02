<?php

include_once("common.php");

echo $_SESSION['userID'];

$id_to_get = $_SESSION['userID'];

if(isset($_POST['displayName'])){
	$prep = "UPDATE user SET displayName=? WHERE id=?";
	if($stmt = $mysqli->prepare($prep)){
		if($stmt->bind_param("si",$_POST['displayName'], $_SESSION['userID'])){
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
