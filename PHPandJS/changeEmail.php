<?php
include_once("common.php");
session_start();
$has_session = session_status() == PHP_SESSION_ACTIVE;
if($has_session){
	$destroy = false;
	if (!isset($_SESSION['login']) or !isset($_SESSION['user_id'])){
		session_regenerate_id(true);
		session_destroy();
	}
	if($_SERVER['REMOTE_ADDR'] !== $_SESSION['login']['ip']){
		$destroy = true;
	}
	if($_SESSION['login']['born'] < time() - 300){
		$destroy = true;
	}
	if($_SESSION['login']["valid"] !== true){
		$destroy = true;
	}
	if($destroy===true){
		session_destroy();
	}
	// Reset our counter
	$_SESSION['login']['born'] = time();
	$id_to_get = $_SESSION['user_id'];
	print_r($_POST);
	if(isset($_POST['email'])){
		$prep = "UPDATE accounts SET email=? WHERE account_id=?";
		if($stmt = $mysqli->prepare($prep)){
			if($stmt->bind_param("si",$_POST['email'], $_SESSION['user_id'])){
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
}
?>
