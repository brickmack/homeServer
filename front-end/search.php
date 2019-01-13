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

//check search parameters
if (isset($_REQUEST["filenameCheck"])) {
    $filenameCheck = true;
}
if (isset($_REQUEST["tagCheck"])) {
    $tagCheck = true;
}
if (isset($_REQUEST["untaggedCheck"])) {
    $untaggedCheck = true;
}
?>
<html lang="en">
<head>
	<?php
		include 'head.php';
		generateHead();
	?>
	
	<script src="js/common.js"></script>
</head>
<body>
<?php
    include 'navbar.php';
    include "common.php";
    headerFunction("navbar-light", "background-color: rgba(251, 237, 254, 0.8)", __FILE__); //for home page
?>
	<div id="wrapper">
		<div class="container-fluid text-center">
			<div class="row row-eq-height" style="padding-top: 70px">
				<div class="col-sm-2 sidenavr">
				</div>
				<div class="col-sm-9 text-left">
					<h2>Search</h2>
					
					<div>
					    <form action="search.php" method="POST" id="basicSearch" name="basicSearch">
					    	<input id="q" name="q" type="text">
					    	<input id="submit" type="submit" value="Submit">
					    	<a href="" onclick="fold('advancedSearch'); fold('basicSearch'); return false">Advanced search</a>
					    </form>
					    
					    <form action="search.php" method="POST" id="advancedSearch" name="advancedSearch" style="display: none;">
					    	<input id="q" name="q" type="text">
					    	<input id="submit" type="submit" value="Submit">
					    	<a href="" onclick="fold('advancedSearch'); fold('basicSearch'); return false">Basic search</a>

							<div>
								Search in:
								<ul style="list-style: none">
									<li><label><input name="filenameCheck" type="checkbox" value="true">Filenames</label></li>
									<li><label><input name="tagCheck" type="checkbox" value="true">Tags</label></li>
								</ul>
					            
								<label><input name="untaggedCheck" type="checkbox" value="true">Include untagged files</label>

								<br>
								<label>Increment: <input name="incrementSize" type="text"></label>
							</div>
					    </form>
					</div>
					
					<?php
					if (!($increment = $_REQUEST["incrementSize"]) || $increment < 0) {
					    $increment = 100;
					}
					
					if (isset($_REQUEST["q"])) {
					    $q = $_REQUEST["q"];
					    echo 'Searching for ' . htmlspecialchars($q) . '!<br>';
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
                            if ($filenameCheck == true) {
                                $filenameStmt = $connection->prepare("select * from file where INSTR(name, '$q') > 0");
                                $filenameStmt->execute() or die(mysqli_error());

                                while ($fileResult = $filenameStmt->fetch(PDO::FETCH_ASSOC)) {
                                    $fileNames[] = $fileResult["name"];
                                    $fileIDs[] = $fileResult["id"];
                                }
                                
                                for ($i = 0; $i<count($fileNames); $i++) {
                                    fileTableRow($i, $fileNames[$i], "", $adminPriv);
                                }
                            }
                            ?>
                            </tbody>
                        </table>
					<?php  
					}
					else {
					    echo "No query entered";
					}
					?>
				</div>
				<div class="col-sm-1 sidenavr right"></div>
			</div>
		</div>
	</div>
	<?php include "footer.php" ?>
</body>
</html>