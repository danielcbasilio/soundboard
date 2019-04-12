<?php
	require("playerFunctions.php");
?>

<!DOCTYPE html>
<html>
<head>
<title>IEEE UP SB Sounds</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html" charset="utf-8" />
<meta name="keywords" content="Smooth Error Page template Responsive, Login form web template,Flat Pricing tables,Flat Drop downs  Sign up Web Templates, Flat Web forms, Login sign up Responsive web Forms, SmartPhone Compatible web template, free webdesigns for Nokia, Samsung, LG, SonyEricsson, Motorola web design" />
<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
<!-- Custom Theme files -->
<link href="../css/style.css" rel="stylesheet" type="text/css" media="all" />
<!-- //Custom Theme files -->
<!-- web font -->
<link href="//fonts.googleapis.com/css?family=Josefin+Sans" rel="stylesheet">
<link href='//fonts.googleapis.com/css?family=Roboto+Condensed:400,300,300italic,400italic,700,700italic' rel='stylesheet' type='text/css'>
<!-- //web font -->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="add-to-fifo.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
</head>
<body>
<!--mian-content-->
<h1 style="font-family:'Monospace'">¯\_(ツ)_/¯</h1>
<h1 style="font-family:'Monospace'">Discos Pedidos</h1>
	<div class="main-wthree">
		<form>
			<?php echo $YT_PREFIX ?>
			<input id="url" type="text" name="url">
			<input type="submit" value="Submit">
		</form>

		<?php insertQueue() ?>
	</div>
<!--//mian-content-->
<!-- copyright -->
	<div class="copyright-w3-agile">
		<p> IEEE UP SB - IT Dept | Design by <a href="http://w3layouts.com/" target="_blank">W3layouts</a></p>
	</div>
<!-- //copyright -->

</body>
</html>
