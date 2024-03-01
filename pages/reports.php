<?php
require_once $root_getReportsPersonalized;

echo "<div id='reports'>";

/* ------------------------------------------------------
 * Personalized Reports
 * ------------------------------------------------------
 */
echo "
<div id='personalizedReports' class='center'>
<div class='caption'> Create Personalized Report: </div>
";
// show each Report (with just name in a button)
foreach($_SESSION['reportsPersonalized'] as $report) {
        $reportName = $report['name'];
        $reportID = $report['id'];
        echo "<div class='button'> <a href='$root_reportPersonalized_HTML?reportID=$reportID' target='_blank'> $reportName </a> </div>";
}

// create new Report form (with name, description and submit button)
echo "
<form id='createReportPersonalized' action='$root_DB_add_HTML' method='GET'>
     <input form='createReportPersonalized' type='hidden' name='table' value='reports'>
     <input form='createReportPersonalized' class='reportGeneric' type='text' name='parameters[name]' placeholder='name of New Report (maximum 30 characters)
'>
<br> <textarea form='createReportPersonalized' class='reportGeneric' name='parameters[description]' placeholder='description of New Report (maximum 100 chara
cters)'></textarea>
<br> <input form='createReportPersonalized' type='submit' value='create New Personalized Report'>
</form>
";

echo "</div> <!-- / personalized reports  -->";


/* ------------------------------------------------------
 * Reports Sums of Ins & Outs,  &&  CCs
 * ------------------------------------------------------
 */
echo "
<div id='reportsInsOutsCCs'>
<form class='formDiv' id='createResoconto' method='get' action='$root_reportSumInsOutsCCs_HTML'>
<div class='center'>
<label for='beginDate'> Data inizio:
<input type='date' name='beginDate' required>
<label for='endDate'> Data fine:
<input type='date' name='endDate' required>
</div>

<div class='table'>
";

//now for each account let's decide if it's an in, out, or must not be considered:
// entrate
// jquery "toggle all" for each in,out,cc
?>
<script>
$(document).ready(function(){
	// Function to toggle checkboxes for each group
	function toggleCheckboxes(groupName) {
		// get the checkboxes
		var checkboxes = $(`input[name='${groupName}[]']`);

		// check if all checkboxes are the same
		var areAllChecked = checkboxes.filter(':checked').length === checkboxes.length;

		if(areAllChecked) {
			// if they are all the same just toggle them
			checkboxes.prop('checked', !areAllChecked);
		} else {
			// if they are not the same, just toggle them all on
			checkboxes.prop('checked', true);
		}
	}

    // Event listeners for toggle buttons
    $('#toggleEntrateButton').on('click', function(){
      toggleCheckboxes('entrate');
    });

    $('#toggleUsciteButton').on('click', function(){
      toggleCheckboxes('uscite');
    });

    $('#toggleContiButton').on('click', function(){
      toggleCheckboxes('conti');
    });
  });
</script>

<?php
echo "
<div class='table'> 
	<div class='caption'> Quali account mettere nelle entrate?</div> 
	<div class='button' id='toggleEntrateButton'>Toggle All</div>
	<div class='tr'>";
foreach($_SESSION['accounts'] as $acc) {
	$accName = $acc['name'];
	echo "<div class='td'> <input type='checkbox' name='entrate[]' value='$accName'>$accName</input> </div>";
}
echo "</div> <!-- /tr -->";
echo "</div> <!-- /table -->";

// Uscite
echo "
<div class='table'>
	<div class='caption'> Quali account mettere nelle uscite?</div>
	<div class='button' id='toggleUsciteButton'>Toggle All</div>
	<div class='tr'>";
foreach($_SESSION['accounts'] as $acc) {
	$accName = $acc['name'];
	echo "<div class='td'> <input type='checkbox' name='uscite[]' value='$accName'>$accName</input> </div>";
}
echo "</div> <!-- /tr -->";
echo "</div> <!-- /table -->";

// Conti Correnti
echo "
<div class='table'>
	<div class='caption'>Di quali account vuoi mostrare la balance di inizio e fine periodo? </div>
	<div class='button' id='toggleContiButton'>Toggle All</div>
	<div class='tr'>";
foreach($_SESSION['accounts'] as $acc) {
	$accName = $acc['name'];
	echo "<div class='td'> <input type='checkbox' name='conti[]' value='$accName'>$accName</input></div>";
}
echo "</div> <!-- /tr -->";
echo "</div> <!-- /table -->";


echo "
<div class='center'> <input type='submit' value='Crea resoconto'> </div>
</form>
</div> <!-- / reports sums ins outs ccs -->";



echo "</div> <!-- / reports -->";
?>
