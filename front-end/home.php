<html lang="en">
<head>
	<?php
		include 'head.php';
		generateHead();
	?>
</head>
<body>
<?php
    include 'navbar.php';
    headerFunction("navbar-light", "background-color: rgba(251, 237, 254, 0.8)", __FILE__); //for home page
?>
	<div id="wrapper">
		<div class="container-fluid text-center">
			<!-- 3 row layout -->
			<div class="row row-eq-height" style="padding-top: 70px">
				<div class="col-sm-2 sidenavr">
				</div>
				<div class="col-sm-8 text-left">
					<h2>Macks stuff</h2>
					<p>Text goes here</p>
				</div>
				<div class="col-sm-2 sidenavr right"></div>
			</div>
		</div>
	</div>
	<?php include "footer.php" ?>
</body>
</html>