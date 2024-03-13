<?php
require_once '/var/www/html/accounting/z.scripts/root.php';
require_once($root_DB_main);
require_once $root_Scripts_cleanInput;
?>

<!DOCTYPE html PUBLIC>
<html>
<head>
<?php 
require_once $root_Style_report;
require_once $root_js_jquery;
?>
</head>
<body id='body'>
<?php
class report {
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
		global $root_DB_rm_HTML, $root_reportPersonalized_HTML, $reportID;
		echo "
<div class='reportRmButton'>
<form id='rm{$table}{$id}' class='reportRmButton'  action='$root_DB_rm_HTML'>
<input form='rm{$table}{$id}' type='hidden' name='redirectTO' value='$root_reportPersonalized_HTML?reportID=$reportID'>
<input form='rm{$table}{$id}' type='hidden' name='table' value='$table'>
<input form='rm{$table}{$id}' type='hidden' name='id' value='$id'>
<input form='rm{$table}{$id}' class='reportRmButton' type='submit' value='rm $table'>
</form>
</div>
		";
	}


	public static function echoUpdateNameButton($table, $id, $currentName) {
		global $root_DB_main_HTML, $root_reportPersonalized_HTML, $reportID;
		echo "
<div class='reportAlterButton'>
<form id='alter{$table}{$id}' class=''  action='$root_DB_main_HTML'>
<input form='alter{$table}{$id}' type='hidden' name='action' value='update'>
<input form='alter{$table}{$id}' type='hidden' name='parameters[redirectTo]' value='$root_reportPersonalized_HTML?reportID=$reportID'>
<input form='alter{$table}{$id}' type='hidden' name='parameters[table]' value='$table'>
<input form='alter{$table}{$id}' type='hidden' name='parameters[id]' value='$id'>
<input form='alter{$table}{$id}' type='hidden' name='parameters[columnName]' value='name'>
<input form='alter{$table}{$id}' type='text' name='parameters[newValue]' value='$currentName' class='' placeholder='$currentName'>
<input form='alter{$table}{$id}' style='visibility:hidden;' type='submit' value='alter $table$id'>
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

// just for easier readibility and re-usage of code:
$table_values = 'report_caption_tr_td_values';

// - - - get each report component linked to that report ID  - - - 
// - - - Basics of report  - - - 
$reportRaw = db::get('reports', array('id' => $reportID));
$report_captionsRaw = db::get('report_captions', array('reports.id' => $reportID));
echo "
<div class='table center'> Editing report {$reportRaw[0]['name']} </div>

<!-- needed for the creation of the report with js --> 
<div id='report' class='table'>
";

// - - - CAPTIONS - - - 
if(!isset($report_captionsRaw['error'])) {
	foreach($report_captionsRaw as $caption) {
		report::echoRmButton($caption['table'], $caption['id']);
		echo "<div id='caption{$caption['id']}' class='report_captions'>";
//		echo "<div class='caption'> {$caption['name']} </div>";
		report::echoUpdateNameButton('report_capions', $caption['id'], $caption['name']);


// - - - TRs - - - 
		$report_caption_trsRaw = db::get('report_caption_trs', array('report_captions.id' => $caption['id']));
		if(!isset($report_caption_trsRaw['error'])) {
			foreach($report_caption_trsRaw as $tr) {
				report::echoRmButton($tr['table'], $tr['id']);
				echo "<div class='report_caption_trs' id='tr{$tr['id']}'>";
//		       		echo "<div class='caption'> {$tr['name']} </div>";
// - - - TDs - - - 
				$report_caption_tr_tdsRaw = db::get('report_caption_tr_tds', array('report_caption_trs.id' => $tr['id']));
				if(!isset($report_caption_tr_tdsRaw['error'])) {
					foreach($report_caption_tr_tdsRaw as $td) {
						echo "<div class='report_caption_tr_tdsContainer' id='containerOftd{$td['id']}'>";
						report::echoRmButton($td['table'], $td['id']);
						echo "<div class='report_caption_tr_tds' id='td{$td['id']}'>";

// - - - TD Values - - - 
						$report_caption_tr_td_valuesRaw = db::get($table_values, array('report_caption_tr_tds.id' => $td['id']));
						if(!isset($report_caption_tr_td_valuesRaw['error'])) {
							foreach($report_caption_tr_td_valuesRaw as $value) {
								report::echoRmButton($value['table'], $value['id']);
								echo "<div class='$table_values' id='value{$value['id']}'>";
								report::echoUpdateNameButton($table_values, $value['id'], $value['name']);
//								echo $value['name'];

								echo "</div> <!-- / value{$value['id']} -->";
							}
						}
						// add TD value 
						report::echoAddButton('report_caption_tr_td_values', 'report_caption_tr_tds', $td['id']);
						echo "</div> <!-- /td {$td['id']} -->";
						echo "</div> <!-- /tdContainer {$td['id']} -->";
					}
				}
				// add TD (table, tableFrom, tableFromID, nameHidden)
				report::echoAddButton('report_caption_tr_tds', 'report_caption_trs', $tr['id'], true);
				echo "</div> <!-- /tr {$tr['id']} -->";
			}
		}
		// add TR (table, tableFrom, tableFromID, nameHidden)
		report::echoAddButton('report_caption_trs', 'report_captions', $caption['id'], true);
		echo "</div> <!-- /caption{$caption['id']} -->";
	}
}
// add Caption
report::echoAddButton('report_captions', 'reports', $reportID);
echo "</div> <!-- /report -->";

echo "<a href='$root_reportPersonalizedDownload_HTML?reportID=$reportID'> download report </a>";
?>
<body>
</html>
