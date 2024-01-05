<?php
	if (!isset($_SESSION))
	{
		session_start();
	}
	require_once __DIR__.'/../controller/controller.php';
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Login</title>
		<link rel="stylesheet" href="">
	</head>
	<body>
		<div class="userform">
			<h2>Logo</h2>
			<h3>Login</h3>
			<form method="post">
				<input type="text" name="login" placeholder="Username">
				<input type="password" name="pswd" placeholder="Password">
				<button type="submit">Login</button>
			</form>
			<a href="pswdRequestForm.php">Forgot password?</a>
			<a href="signupForm.php">Signup</a>
		</div>
	</body>
</html>

<?php
	if ($_SERVER['REQUEST_METHOD'] == "POST")
	{
		$result = Controller::getInstance();
		$result->login($_POST);
		$_SESSION['user_id'] = $result->output_view->user_id;
		$_SESSION['username'] = $result->output_view->username;
		var_dump($_SESSION); //TEST
		print($result->output_view->err_msg);//TEST
	}
?>
