<!DOCTYPE html>
<html>
	<head>
		<title>Bootstrap 101 Template | <?php echo $pageTitle; ?></title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<!-- Bootstrap -->
		<link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
		<link href="css/style.css" rel="stylesheet" media="screen">

		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
		  <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
		  <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
		<![endif]-->
	</head>
	<body>
		<div class="container">
			<div class="jumbotron">
				<h1>Welcome to aptitude!</h1>
				<p>You have arrived at the default page for the aptitude framework.</p>
				<p>
					<a class="btn btn-primary btn-lg" role="button">Learn more</a>
				</p>
			</div>
		</div>

		<?php if (isset($content)): ?>
			<?php echo $content; ?>
		<?php endif; ?>

		<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
		<script src="https://code.jquery.com/jquery.js"></script>
		<!-- Include all compiled plugins (below), or include individual files as needed -->
		<script src="js/bootstrap.min.js"></script>
	</body>
</html>