<!DOCTYPE html>
<html>
	<head>
		<title>Bootstrap 101 Template | <?php echo $pageTitle; ?></title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<!-- Bootstrap -->
		<link href="/css/bootstrap.min.css" rel="stylesheet" media="screen">
		<link href="/css/style.css" rel="stylesheet" media="screen">

		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
		  <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
		  <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
		<![endif]-->
	</head>
	<body>
		
		<!-- Navigation -->
		<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
		  <div class="container-fluid">

		  	<!-- Burger and logo -->
		  	<div class="navbar-header">
		      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
		        <span class="sr-only">Toggle navigation</span>
		        <span class="icon-bar"></span>
		        <span class="icon-bar"></span>
		        <span class="icon-bar"></span>
		      </button>
		      <a class="navbar-brand" href="/">Aptitude PHP</a>
		    </div>

		    <!--Nav links -->
		    <div class="collapse navbar-collapse navbar-right" id="bs-example-navbar-collapse-1">
		      <ul class="nav navbar-nav">
		      	<li class="active">
		      		<a href="/">Home</a>
		      	</li>
		        <li>
		        	<a href="/docs">Documentation</a>
		        </li>
		        <li>
		        	<a href="#">Forums</a>
		        </li>
		        <li>
		        	<a href="#">About</a>
		        </li>
		        <li>
		        	<a href="/articles/create">Create article</a>
		        </li>
		      </ul>
		    </div>
		  </div>
		</nav>

		<!-- Site Content -->
		<div class="container">
			<?php echo $content; ?>
		</div>

		<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
		<script src="https://code.jquery.com/jquery.js"></script>
		<!-- Include all compiled plugins (below), or include individual files as needed -->
		<script src="/js/bootstrap.min.js"></script>
	</body>
</html>