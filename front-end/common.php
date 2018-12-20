<?php
function olistbox($background, $title, $list) { ?>
	<div class="card text-white text-center bg-light mb-5" style=" width: 380px; height: 150px; background-color: <?php echo $background ?>; margin: 5px auto;">
		<div class="card-header" style="background-color:<?php echo $background ?>;">
			<h5><?php echo $title ?></h5>
		</div>
		<div class="card-body" style="background-color:<?php echo $background ?>;">
			<ol style="text-align:left;">
				<?php
					//generate list from array
					foreach ($list as $listItem) {
						echo "<li>".$listItem."</li>\n";
					}
				?>
			</ol>
		</div>
	</div>
<?php } ?>
<?php

function ulistbox($background, $title, $list) { ?>
	<div class="card text-white text-center bg-light mb-5" style=" width: 380px; height: 150px; background-color: <?php echo $background ?>; margin: 5px auto;">
		<div class="card-header" style="background-color:<?php echo $background ?>;">
			<h5><?php echo $title ?></h5>
		</div>
		<div class="card-body" style="background-color:<?php echo $background ?>;">
			<ul style="text-align:left;">
				<?php
					//generate list from array
					foreach ($list as $listItem) {
						echo "<li>".$listItem."</li>\n";
					}
				?>
			</ol>
		</div>
	</div>
<?php } ?>


<?php
function simpleBox($background, $icon, $title, $value) { ?>
	<div class="card text-white bg-light text-center mb-5" style="min-width: 11rem; max-width: 20rem;">
		<div class="card-header" style="background-color:<?php echo $background ?>;">
			<h6><?php echo $title ?></h6>
		</div>
		<div class="card-body" style="background-color:<?php echo $background ?>;">
			<h5><i class="<?php echo $icon ?>"></i> <?php echo $value ?></h5>
		</div>
	</div>
<?php } ?>

<?php
function fileTableRow($rowNum, $name, $tags, $adminPriv) { ?>
    <tr>
	    <th scope="row" style="text-align: center;"><?php echo $rowNum;?></th>
		<td><?php echo "<a href=\"/demoHome/$name\">$name</a>";?></td>
		<td><?php echo $tags; ?></td>
		<td style="text-align: center;">
			<?php if ($adminPriv == true) { ?>
			    <button type="button" class="btn btn-outline-danger" onclick="deleteRow(this)"><i class="fas fa-trash"></i>&nbsp;Delete</button>
			<?php } ?>
		</td>
	</tr>
<?php   
}
?>