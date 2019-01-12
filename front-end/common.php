<?php
function fileTableRow($rowNum, $name, $tags, $adminPriv) { 
	$split = splitToLines($name, 20);
	
	$tagString = "<a href=\"" . $tags[0] . "\">" . $tags[0] . "</a>";
	for ($i=1; $i<count($tags); $i++) {
		$tagString = $tagString . ", " . "<a href=\"listTag.php?q=" . $tags[$i] . "\">" . $tags[$i] . "</a>";
	}
	?>
    <tr>
	    <th scope="row" style="text-align: center;"><?php echo $rowNum;?></th>
		<td><?php echo "<a href=\"/demoHome/$name\">$split</a>";?></td>
		<td><?php echo $tagString; ?></td>
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

<?php
function prevNext($url, $page, $totalFiles, $increment) {
    echo "<div>";
    if ($page > 0) {
        echo "<a href=\"$url.php?p=" . ($page - 1) . "\">Prev</a>";
    }
    if ($page < ($totalFiles/$increment) - 1) {
        echo "<a href=\"$url.php?p=" . ($page + 1) . "\">Next</a>";
    }
    echo "</div>";
}
?>