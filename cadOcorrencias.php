	<!DOCTYPE html>
	<html>
	<head>
		<!-- LOADER -->
		<link href='../paginas/loader.css' rel='stylesheet' type='text/css'>
		<link href='http://fonts.googleapis.com/css?family=Lato:900,400' rel='stylesheet' type='text/css'>
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
		<script>
			function loadOff()
			{
				$(document).ready(function (){
					$("#loader-wrapper").fadeOut('slow');
				});
			}
		</script>
		<!-- /LOADER -->
	</head>

	<body class="nav-md" onload="loadOff()">
		<!-- LOADER -->
		<div id="loader-wrapper">
			<div id="loader"></div>
		</div>
		<!-- /LOADER -->

	</body>
	</html>
