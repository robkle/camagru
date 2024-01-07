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
		<title>Modify email</title>
		<link rel="stylesheet" href="">
	</head>
	<body>
		<div class="userform">
			<h2>Logo</h2>
			<h3>Modify email</h3>
			<form method="post">
				<input type="text" name="email" placeholder="New email">
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
		$result->modifyEmail($_SESSION['user_id'], $_POST);
		print($result->output_view->err_msg);//TEST
	}
?>
