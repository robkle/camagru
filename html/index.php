<?php
	if (!isset($_SESSION))
	{
		session_start();
	}
?>

<!DOCTYPE html>
<html>
	<body>
		<?php include 'ui/signupForm.php'; ?>
	</body>
</html>
