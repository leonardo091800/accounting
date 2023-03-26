<?php
function cleanInput($a) {
	return htmlspecialchars($a);
}
function cleanInputArr($a) {
	return filter_input_arraY(INPUT_GET);
}
?>
