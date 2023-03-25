<?php
require_once '/var/www/html/accounting/z.scripts/root.php';
require_once($root_DB_main);
require_once $root_Scripts_cleanInput;
?>

<!DOCTYPE html PUBLIC>
<html>
<head>
<?php 
require_once $root_Style_main;
?>
</head>
<body id='body'>
<?php
if(isset($_GET['reportID'])) {
	$reportID = cleanInput($_GET['reportID']);
} else {
	die("no report ID given");
}

require_once $root_DB_main;
$conn = db::connect();

// - - - get each report component linked to that report ID  - - - 
// get report name
$reportRaw = db::get('reports', $reportID);
echo " report is : "; print_r($reportRaw);
?>
<body>
</html>
