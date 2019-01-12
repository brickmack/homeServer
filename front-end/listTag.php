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
				<div class="col-sm-9 text-left">
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
                            //first load an array of the first n files
                            $fileStmt = $connection->prepare("SELECT * from file limit 100");
                            $fileStmt->execute() or die(mysqli_error());
                            
                            $fileNames = array();
                            $fileIDs = array();
                            
                            //we maintain a separate list of tags, so we don't have to repeatedly look up each tag name hundreds of times
                            $tagNames = array();
                            $tagIDs = array();
                            
                            while ($fileResult = $fileStmt->fetch(PDO::FETCH_ASSOC)) {
                                $fileNames[] = $fileResult["name"];
                                $fileIDs[] = $fileResult["id"];
                            }
                            
                            //now select all tags associations for each ID
                            for ($i = 0; $i<count($fileNames); $i++) {
                                $associatedTagStmt = $connection->prepare("SELECT ft.* from file_tag as ft where ft.file_id = " . $fileIDs[$i]);
                                $associatedTagStmt->execute() or die(mysqli_error());
                                
                                $associatedTags = array();
                                while ($associationResult = $associatedTagStmt->fetch(PDO::FETCH_ASSOC)) {
                                    $foundAt = -1;
                                    for ($j = 0; $j<count($tagIDs); $j++) {
                                        if ($tagIDs[$j] == $associationResult["tag_id"]) {
                                            $foundAt = $j;
                                            break;
                                        }
                                    }
                                    if ($foundAt > -1) {
                                        $associatedTags[] = $tagNames[$foundAt];
                                    }
                                    else {
                                        //wasn't there, we have to add it now.
                                        $tagStmt = $connection->prepare("SELECT name from tag where id = " . $associationResult["tag_id"]);
                                        $tagStmt->execute() or die(mysqli_error($connection));
                                        $tagResult = $tagStmt->fetch(PDO::FETCH_ASSOC);
                                            
                                        $tagIDs[] = $associationResult["tag_id"];
                                        $tagNames[] = $tagResult["name"];
                                        $associatedTags[] = $tagResult["name"];
                                    }
                                }
                                
                                fileTableRow($i, $fileNames[$i], $associatedTags, $adminPriv);
                            }
                            ?>
                        </tbody>
					</table>
				</div>
				<div class="col-sm-1 sidenavr right"></div>
			</div>
		</div>
	</div>
	<?php include "footer.php" ?>
</body>
</html>