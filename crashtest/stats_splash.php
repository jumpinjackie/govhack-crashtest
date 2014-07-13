<!DOCTYPE html>
<html>
	<head>Computing statistics</head>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" />
	<script type="text/javascript" src="" />
	<body>
		<div class="row">
		  <div class="col-sm-6 col-md-4">
			<div class="thumbnail">
			  <img src="images/spinner.gif">
			  <div class="caption">
				<h3>Computing statistics from the current map view. Please wait</h3>
			  </div>
			</div>
		  </div>
		</div>
	</body>
</html>
<?php
header("Location: stats.php?mapname=$mapname&session=$session");
die;
?>