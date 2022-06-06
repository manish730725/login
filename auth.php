<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

 include_once $_SERVER['DOCUMENT_ROOT'] . '/dbConnect.php';

	if ( !isset($_POST['username'], $_POST['password']) ) {

	exit('Please fill both the username and password fields!');
	}
	if ($stmt = $con->prepare('SELECT id,password,firstname,surname,mobile,type,team_id,permission_ACF FROM staff WHERE username = ?')) {

	$stmt->bind_param('s', $_POST['username']);
	$stmt->execute();

	$stmt->store_result();
	if ($stmt->num_rows > 0) {
	$stmt->bind_result($id,$password,$firstname,$surname,$mobile,$type,$team_id,$permission_ACF);
	$stmt->fetch();

	// Note: remember to use password_hash in your registration file to store the hashed passwords.
	if (md5($_POST['password']) == $password) {
		$_SESSION['loggedin'] = TRUE;
		$_SESSION['staff_username'] = $_POST['username'];
		$_SESSION['staff_id'] = $id;
		$_SESSION['staff_firstname'] = $firstname;
		$_SESSION['staff_surname'] = $surname;
		$_SESSION['staff_mobile'] = $mobile;
		$_SESSION['staff_type'] = $type;
		$_SESSION['staff_team_id'] = $team_id;
		$_SESSION['permission_ACF'] = $permission_ACF;
		if($_SESSION['staff_type'] != "stock_management"){
      header('Location: ../Template/view_notice.php');
    }
    else
    {
      header('Location: ../website_management/auto_trader.php');
    }
	} else {
		header("location:https://www.motor.market/staff/index.php?login=0");
	}
}
else {
	// Incorrect username
	header("location:https://www.motor.market/staff/index.php?login=0");
}

	$stmt->close();
}

?>
