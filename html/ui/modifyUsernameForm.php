<?php
	if (!isset($_SESSION))
	{
		session_start();
	}
	require_once __DIR__.'/../controller/controller.php';
	
	if (!isset($_SESSION['user_id'])){
		header("Location: ui/loginForm.php");
	}
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Modify username</title>
		<link rel="stylesheet" href="">
	</head>
	<body>
		<div class="userform">
			<h2>Logo</h2>
			<h3>Modify username</h3>
			<form method="post">
				<input type="text" name="username" placeholder="New username">
				<input type="text" name="pswd" placeholder="Password">
				<button type="submit">Save</button>
			</form>
			<a href="index.php">Cancel</a>
		</div>
	</body>
</html>

<?php
	if ($_SERVER['REQUEST_METHOD'] == "POST")
	{
		$result = Controller::getInstance();
		$result->modifyUsername($_SESSION['user_id'], $_POST);
		$_SESSION['username'] = $result->output_view->username;
		var_dump($_SESSION); //TEST
		print($result->output_view->err_msg);//TEST
	}
?>
