<?php
require_once '/var/www/html/accounting/z.scripts/root.php';
require_once $root_Scripts_showErrors;
require_once $root_Scripts_checkSessionVariables;
require_once $root_Scripts_style;
?>

<!DOCTYPE html PUBLIC>
<html>
<head>
<?php 
require_once $root_Style_main;
require_once $root_js_toggleDisplay;
require_once $root_js_setStyle;
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
<?php
echo "
<script>
$(document).ready(function(){
";
$menuSelected = $_SESSION['menuSelected'];
switch($menuSelected) {
case 'generalLedger':
	echo "
	setStyle('id', 'generalLedger', 'display', '');
	setStyle('id', 'reports', 'display', 'none');
	";
	break;
case 'reports':
	echo "
	setStyle('id', 'generalLedger', 'display', 'none');
	setStyle('id', 'reports', 'display', '');
	";
	break;
default:
	echo "
	setStyle('id', 'generalLedger', 'display', 'none');
	setStyle('id', 'reports', 'display', 'none');
	";
	break;
}
echo "
});
</script>
";
?>

</head>
<body>
<?php


// - - - Session variables - - -
$_SESSION['sums'] = array();
// if I don't do this, it warns me that 'undefined offset'
foreach($_SESSION['accounts'] as $acc) {
	$accID = $acc['id'];
	$_SESSION['sums'][$accID] = 0;
}
// - - - /session variables - - -



// - - - menu - - - 
require_once $root_Pages_menu;
// - - - /menu - - - 


// - - - generalLedger - - - 
require_once $root_generalLedger;
// - - - /menu - - - 


// - - - reports - - - 
require_once $root_reports;
// - - - /reports - - - 





?>
</body>
</html>
