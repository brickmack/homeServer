<?php
session_start();
require ('connection.php');
//determine if user has admin privs or not
$adminPriv = false;
if (isset($_SESSION["username"])) {
    //user is logged in, check if they're an admin
    $result = $connection->prepare("SELECT * FROM Person WHERE (user = ?) and (isAdmin = 1)");
    $result->execute(array($_SESSION["username"])) or die(mysqli_error());
    if ($result->rowCount() == 1) {
        //user is an admin
        $adminPriv = true;
    }
}
?>
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
    include "common.php";
    headerFunction("navbar-light", "background-color: rgba(251, 237, 254, 0.8)", __FILE__); //for home page
?>
	<div id="wrapper">
		<div class="container-fluid text-center">
			<!-- 3 row layout -->
			<div class="row row-eq-height" style="padding-top: 70px">
				<div class="col-sm-2 sidenavr">
				</div>
				<div class="col-sm-8 text-left">
					<h2>Porn</h2>
					<table class="table" id="table" style="-ms-overflow-style: -ms-autohiding-scrollbar; max-height: 200px; margin: 10px auto;">
					    <thead>
                            <tr>
                                <th scope="col" style="text-align:center;">#</th>
                                <th scope="col">Filename</th>
                                <th scope="col">Tags</th>
                                <?php
                                if ($adminPriv == true) {
                                    //action column is only available to admins
                                    echo '<th scope="col" style="text-align:center;">Action</th>';
                                }
                                ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($handle = opendir('/home/ubuntu/workspace/demoHome')) {
                                $i = 1;
                                while (false !== ($entry = readdir($handle))) {
                                    if ($entry != "." && $entry != "..") {
                                        fileTableRow($i, $entry, null, $adminPriv);
                                        $i++;
                                    }
                                }
                                closedir($handle);
                            }
                            ?>
                        </tbody>
					</table>
				</div>
				<div class="col-sm-2 sidenavr right"></div>
			</div>
		</div>
	</div>
	<?php include "footer.php" ?>
</body>
</html>