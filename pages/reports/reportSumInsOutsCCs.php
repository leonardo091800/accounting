<?php
require_once '/var/www/html/accounting/z.scripts/root.php';
require_once($root_DB_main);
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

// - - - menu - - - 
require_once $root_Pages_menu;
// - - - /menu - - - 

if(isset($_GET['beginDate']) && isset($_GET['endDate']) && isset($_GET['entrate']) && isset($_GET['uscite']) && isset($_GET['conti'])) {
	$beginDate = $_GET['beginDate'];
	$endDate = $_GET['endDate'];
	$entrate = $_GET['entrate'];
	$uscite = $_GET['uscite'];
	$conti = $_GET['conti'];

	/*
	echo "<br>";
	print_r($beginDate);
	echo "<br>";
	print_r($endDate);
	echo "<br>";
	print_r($entrate);
	echo "<br>";
	print_r($uscite);
	 */

	// inizio creazione rendiconto
	// Titolo
	echo "<div id='report' class='table'>
	        <div class='caption'> RENDICONTO </div>";

	// periodo riferimento
	echo "
		<div class='caption'> PERIODO DI RIFERIMENTO DAL $beginDate AL $endDate </div>";

	// Entrate
	echo "<div class='table'> 
<div class='caption'> Somma Entrate </div>  
	";
	foreach($entrate as $accName) {
		$accID = db::getID('accounts', array('name'=>$accName, 'users.id'=>$_SESSION['userID']));
//		echo "<br> id = $accID";

		// get initial sum of account
		$beginSum = db::getSum($accID, $beginDate);

		// get final sum of account
		$endSum = db::getSum($accID, $endDate);

		$tot = $endSum - $beginSum;

		echo "
<div class='tr'>
<div class='td'> $accName </div>
<div class='td'> $tot </div>
</div> <!-- /tr --> 
</div> <!-- /table -->
";
	}



	// Uscite 
	echo "<div class='table'> 
<div class='caption'> somma Uscite </div>  
	";
	foreach($uscite as $accName) {
		$accID = db::getID('accounts', array('name'=>$accName, 'users.id'=>$_SESSION['userID']));
//		echo "<br> id = $accID";

		// get initial sum of account
		$beginSum = db::getSum($accID, $beginDate);

		// get final sum of account
		$endSum = db::getSum($accID, $endDate);

		$tot = $endSum - $beginSum;

		echo "
<div class='tr'>
<div class='td'> $accName </div>
<div class='td'> $tot </div>
</div> <!-- /tr --> 
</div> <!-- /table -->
	";
	}



	// Conti 
		echo "
<div class='table'> 
  <div class='caption'> Conti / Banche </div>  
	";
	foreach($conti as $accName) {
		$accID = db::getID('accounts', array('name'=>$accName, 'users.id'=>$_SESSION['userID']));
//		echo "<br> id = $accID";

		// get initial sum of account
		$beginSum = db::getSum($accID, $beginDate);

		// get final sum of account
		$endSum = db::getSum($accID, $endDate);

		echo "
  <div class='tr'>
    <div class='td'> $accName </div>
    <div class='td'>
	Initial Sum = $beginSum 
<br>	Final Sum = $endSum
    </div>
  </div> <!-- /tr --> 
	";
	}
	echo " 
</div> <!-- /table Conti / Banche -->
	";


	echo "</div> <!-- /table report -->";

	// button to export as PDF
	echo "
<br><br><br>
<div id='exportButton' class='menu'> Export in PDF! </div>

<script src='$root_Scripts_html2pdf_HTML'></script>
<script>
const btn = document.getElementById('exportButton');

btn.addEventListener('click', function(){
var element = document.getElementById('report');
html2pdf().from(element).save('filename.pdf');
});
</script>
";

} else {
	echo "some parameters missing";
}
?>
<body>
