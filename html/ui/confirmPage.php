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
		<title>Confirmation</title>
	</head>
	<body>
		<h2>Account confirmation</h2>
		<div>
			<a href="loginForm.php">Login</a>
		</div>
	</body>
</html>

<?php
	if (isset($_GET['ckey']))
	{
		$result = Controller::getInstance();
		$result->confirm($_GET);
		print($result->output_view->err_msg);//TEST
	}
?>
