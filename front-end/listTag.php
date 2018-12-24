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
					<h2>Search</h2>
					
					<?php
					if (isset($_GET["q"])) {
					    $q = $_GET["q"];
					    echo 'Searching for ' . htmlspecialchars($q) . '!';
					}
					?>

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
                            //SELECT f.name, group_concat(ft.tag_id separator ',') FROM file as f, file_tag as ft where f.id = ft.file_id group by f.id
                            $fileStmt = $connection->prepare("SELECT f.name, ft.tag_id FROM file as f, file_tag as ft where f.id = ft.file_id limit 500");
                            $fileStmt->execute() or die(mysqli_error());
                            
                            $prevFile = "";
                            $prevTags = "";
                            $i = 1;
                            
                            while ($fileResult = $fileStmt->fetch(PDO::FETCH_ASSOC)) {
                                $currentFile = $fileResult["name"];
                                
                                $tagStmt = $connection->prepare("SELECT name from tag where id = " . $fileResult["tag_id"]);
                                $tagStmt->execute() or die(mysqli_error($connection));
                                $tagResult = $tagStmt->fetch(PDO::FETCH_ASSOC);
                                
                                $currentTag = $tagResult["name"];
                                
                                if ($currentFile != $prevFile) {
                                    //previous file is done
                                    if ($prevFile != "") {
                                        //make sure its not the initialization still
                                        fileTableRow($i, $prevFile, $prevTags, $adminPriv);
                                        $i++;
                                    }
                                    $prevFile = $currentFile;
                                    $prevTags = $currentTag;
                                }
                                else {
                                    //continuing with the same file
                                    $prevTags = $prevTags . ", " . $currentTag;
                                }
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