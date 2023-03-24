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
	echo "<div class='title'> RENDICONTO </div>";

	// periodo riferimento
	echo "<div class='subtitle'> PERIODO DI RIFERIMENTO DAL $beginDate AL $endDate </div>";

	// Entrate
	echo "<div class='table'> 
<div class='caption'> Entrate </div>  
	";
	foreach($entrate as $accName) {
		$accID = db::getID('accounts', array('name'=>$accName));
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
<div class='caption'> Uscite </div>  
	";
	foreach($uscite as $accName) {
		$accID = db::getID('accounts', array('name'=>$accName));
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
	echo "<div class='table'> 
<div class='caption'> Conti / Banche </div>  
	";
	foreach($conti as $accName) {
		$accID = db::getID('accounts', array('name'=>$accName));
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
</div> <!-- /table -->
	";
	}



	// button to export as PDF
	echo "
<br><br><br>
<button id='button' class='center'>Export in PDF!</button>

<script src='$root_Scripts_html2pdf_HTML'></script>
<script>
const btn = document.getElementById('button');

btn.addEventListener('click', function(){
var element = document.getElementById('body');
html2pdf().from(element).save('filename.pdf');
});
</script>
";

} else {
	echo "some parameters missing";
}
?>
<body>
</html>
