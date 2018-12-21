<?php
function fileTableRow($rowNum, $name, $tags, $adminPriv) { 
	$split = splitToLines($name, 20);
	?>
    <tr>
	    <th scope="row" style="text-align: center;"><?php echo $rowNum;?></th>
		<td><?php echo "<a href=\"/demoHome/$name\">$split</a>";?></td>
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

<?php
function splitToLines($text, $maxLength) {
	$formatted = substr($text, 0, $maxLength);
	$remaining = substr($text, $maxLength);
	
	while (strlen($remaining) > $maxLength) {
		$formatted = $formatted . "<br>" . substr($remaining, 0, $maxLength);
		$remaining = substr($remaining, $maxLength);
	}
	
	return $formatted . "<br>" . $remaining;
}
?>