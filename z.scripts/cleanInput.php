<?php
function cleanInput($a) {
	return htmlspecialchars($a);
}
function cleanInputArr($a) {
	return (is_array($a))?array_map('cleanInputArr',$a):htmlspecialchars($a, ENT_QUOTES, 'UTF-8');
}
?>
