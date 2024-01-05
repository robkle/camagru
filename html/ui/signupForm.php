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
		<title>Signup</title>
		<link rel="stylesheet" href="">
	</head>
	<body>
		<div class="userform">
			<h2>Camagru</h2>
			<h3>Signup</h3>
			<form method="post">
				<input type="text" name="login" placeholder="Username">
				<input type="email" name="email" placeholder="Email">
				<input type="password" name="pswd" placeholder="Password">
				<p>*Minimum of 6 characters. Should include at least one capital letter and one digit!</p>
				<input type="password" name="pswd2" placeholder="Re-enter password">
				<button type="sumbit">Signup</button>
			</form>
			<a href="ui/loginForm.php">Login</a>
		</div>
	</body>
</html>

<?php
	if ($_SERVER['REQUEST_METHOD'] == "POST")
	{
		$result = Controller::getInstance();
		$result->signup($_POST);
		print($result->output_view->err_msg);//TEST
	}
?>
