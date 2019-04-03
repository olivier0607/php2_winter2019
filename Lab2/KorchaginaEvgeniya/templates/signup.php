<!DOCTYPE html>

<html lang="en">

<head>

	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->

	<title>Login App</title>

	<!-- Bootstrap -->
	<link href="css/bootstrap.css" rel="stylesheet">

	<!-- Custom styles for this template -->
	<link href="css/signin.css" rel="stylesheet">

	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->

	<!--[if lt IE 9]>
		<script src="js/html5shiv.min.js"></script>
		<script src="js/respond.min.js"></script>
	<![endif]-->

</head>

<body>

	<div class="container">
        <form class="form-signin" method="post" data-toggle="validator" role="form">
			<h2 class="form-signin-heading" style="margin-bottom: 40px;"><?php echo isset($this->userMessage) ? $this->getUserMessage() : ''?></h2>
			<div class="form-group">
			<div id="input_container">
						<img src="images/icon_person.png" id="input_img1">				<label for="inputUsername" class="control-label">Username:</label>
				<input class="form-control" id="inputUsername" name="username" placeholder="Username" type="text" pattern="^[a-zA-Z]+$" maxlength="40" data-error="Invalid character." required autofocus>
				<div class="help-block with-errors"></div>
			</div>
			<div class="form-group">
						<img src="images/icon_lock.png" id="input_img2">				<label for="inputPassword" class="control-label">Password:</label>
				<input class="form-control" id="inputPassword" name="password" placeholder="Password" type="password" pattern="^[_a-zA-Z0-9]+$" maxlength="40" data-error="Invalid character." required>
				<div class="help-block with-errors"></div>
			</div>
						<img src="images/icon_lock.png" id="input_img2">				<label for="inputPassword" class="control-label">Repeat password:</label>
				<input class="form-control" id="inputPassword" name="repeatpassword" placeholder="Password" type="password" pattern="^[_a-zA-Z0-9]+$" maxlength="40" data-error="Invalid character." required>
				<div class="help-block with-errors"></div>
			</div>
			<div class="form-group">
				<button class="btn btn-lg btn-primary btn-block" name="submit" type="submit" value="1">Submit</button>
			</div>
		</form>
		<form class="form-back" action="index.php" method="post" role="form">
				<button class="btn btn-lg btn-primary btn-block" type="submit" name="back">Back</button>
		</form>
	</div> <!-- /container -->

</body>

</html>