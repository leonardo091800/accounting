<?php
require_once '/var/www/html/accounting/z.scripts/root.php';
require_once($root_DB_main);
require_once $root_Scripts_cleanInput;
?>

<!DOCTYPE html PUBLIC>
<html>
<head>
<?php 
require_once $root_Style_reportDownload;
require_once $root_js_jquery;
?>
</head>
<body id='body'>
<?php
class report {

	/* 
	 * Create Report with indented parameters
	 * common usage is
	 * report::show($captionsRaw, array($trsRaw, tdsRaw, tdValuesRaw));
	 */
	/*
	public static function show($parameters, $indented=array()) {
		foreach($parameters as $p) {
			$tableFrom = $p['tableFrom'];
			$table = $p['table'];
			$id = $p['id'];
			echo "<div class='table'> {$p['name']} </div>";
			report::echoRmButton($table, $id);
			report::echoAddButton($table, $p);

			if(!empty($indented)) {
				$parameters = array_shift($indented);
				report:show($parameters, $indented);
			}
		}
	}
	 */


	/*
	public static function echoCaptions($parameters) {
		foreach($parameters as $p) {
			$tableFrom = $p['tableFrom'];
			$table = $p['table'];
			$id = $p['id'];
		}
	}
	 */

/*
	public static function echoAddCaptionButton() {
		global $root_DB_add_HTML, $root_report_HTML, $reportID;
		$table = 'report_captions';
echo "
<form id='create{$table}' class='table' action='$root_DB_add_HTML' method='GET'>
     <input form='create{$table}' type='hidden' name='table' value='{$table}'>
     <input form='create{$table}' type='hidden' name='reports_id' value='$reportID'>
     <input form='create{$table}' class='reportGeneric' type='text' name='name' placeholder='* name of New Caption (maximum 30 characters)'>
<br> <textarea form='create{$table}' class='reportGeneric' name='description' placeholder='description of New Caption (maximum 100 characters) (this is not shown in the report)'></textarea>
<br> <input form='createCaption' type='submit' value='create New Caption'>
</form>
";
	}
*/


	public static function echoAddButton($table, $parentTable, $parentID, $nameHidden=false) {
		global $root_DB_add_HTML, $root_report_HTML, $reportID;
echo "
<div class='$table'>
<form id='create{$table}{$parentID}' class='' action='$root_DB_add_HTML' method='GET'>
     <input form='create{$table}{$parentID}' type='hidden' name='table' value='reports'>
     <input form='create{$table}{$parentID}' type='hidden' name='parameters[reportID]' value='$reportID'>
     <input form='create{$table}{$parentID}' type='hidden' name='parameters[realTable]' value='{$table}'>
     <input form='create{$table}{$parentID}' type='hidden' name='parameters[{$parentTable}.id]' value='$parentID'>";
		if($nameHidden) {
			echo "
<input form='create{$table}{$parentID}' class='reportGeneric' type='hidden' name='parameters[name]' value=''  placeholder='* name of New $table (maximum 30 characters)'>
<br> <input form='create{$table}{$parentID}' type='submit' value='create New $table'>
";
		} else {
			echo "
<input form='create{$table}{$parentID}' class='reportGeneric' type='text' name='parameters[name]' value=''  placeholder='* name of New $table (maximum 30 characters)'>
<br> <input form='create{$table}{$parentID}' style='visibility:hidden;' type='submit' value='create New $table'>

";
		}
echo "<!-- 
<br> <textarea form='create{$table}{$parentID}' class='reportGeneric' name='parameters[description]' placeholder='description of New $table (maximum 100 characters) (this is not shown in the report)'></textarea>
-->
</form>
</div>
";
	}


	public static function echoRmButton($table, $id) {
		global $root_DB_rm_HTML, $root_report_HTML, $reportID;

		echo "
<div class='reportRmButton'>
<form id='rm{$table}{$id}' class=''  action='$root_DB_rm_HTML'>
<input form='rm{$table}{$id}' type='hidden' name='redirectTO' value='$root_report_HTML?reportID=$reportID'>
<input form='rm{$table}{$id}' type='hidden' name='table' value='$table'>
<input form='rm{$table}{$id}' type='hidden' name='id' value='$id'>
<input form='rm{$table}{$id}' class='reportRmButton' type='submit' value='-'>
</form>
</div>
		";
	}
}



?>
<?php
if(isset($_GET['reportID'])) {
	$reportID = cleanInput($_GET['reportID']);
} else {
	die("no report ID given");
}

require_once $root_DB_main;
$conn = db::connect();

// - - - get each report component linked to that report ID  - - - 
// - - - Basics of report  - - - 
$reportRaw = db::get('reports', array('id' => $reportID));
$report_captionsRaw = db::get('report_captions', array('reports.id' => $reportID));
echo "
<!-- needed for the creation of the report with js --> 
<div id='report' class='table'>
";

// - - - CAPTIONS - - - 
if(!isset($report_captionsRaw['error'])) {
	foreach($report_captionsRaw as $caption) {
		echo "<div id='caption{$caption['id']}' class='report_captions'>";
		echo "<div> {$caption['name']} </div>";


// - - - TRs - - - 
		$report_caption_trsRaw = db::get('report_caption_trs', array('report_captions.id' => $caption['id']));
		if(!isset($report_caption_trsRaw['error'])) {
			foreach($report_caption_trsRaw as $tr) {
				echo "<div class='report_caption_trs' id='tr{$tr['id']}'>";
// - - - TDs - - - 
				$report_caption_tr_tdsRaw = db::get('report_caption_tr_tds', array('report_caption_trs.id' => $tr['id']));
				if(!isset($report_caption_tr_tdsRaw['error'])) {
					foreach($report_caption_tr_tdsRaw as $td) {
						echo "<div class='report_caption_tr_tds' id='td{$td['id']}'>";

// - - - TD Values - - - 
						$report_caption_tr_td_valuesRaw = db::get('report_caption_tr_td_values', array('report_caption_tr_tds.id' => $td['id']));
						if(!isset($report_caption_tr_td_valuesRaw['error'])) {
							foreach($report_caption_tr_td_valuesRaw as $value) {
								echo "<div class='report_caption_tr_td_values' id='value{$value['id']}'>";
								echo $value['name'];

								echo "</div> <!-- / value{$value['id']} -->";
							}
						}
						echo "</div> <!-- /td {$td['id']} -->";
					}
				}
				echo "</div> <!-- /tr {$tr['id']} -->";
			}
		}
		echo "</div> <!-- /caption{$caption['id']} -->";
	}
}
	// button to export as PDF
	echo "
<br><br><br>
</div> <!-- /report -->
<button id='button' class='center'>Export in PDF!</button>

<script src='$root_Scripts_html2pdf_HTML'></script>
<script>
const btn = document.getElementById('button');

btn.addEventListener('click', function(){
var element = document.getElementById('report');
html2pdf().from(element).save('filename.pdf');
});
</script>
";
?>
<body>
</html>
