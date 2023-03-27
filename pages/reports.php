<?php
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
	echo "<div class='table'> <div class='caption'> Quali account mettere nelle entrate?</div> <div class='tr'>";
foreach($_SESSION['accounts'] as $acc) {
	$accName = $acc['name'];
	echo "<div class='td'> <input type='checkbox' name='entrate[]' value='$accName'>$accName</input> </div>";
}
echo "</div> <!-- /tr -->";
echo "</div> <!-- /table -->";

// Uscite
	echo "<div class='table'> <div class='caption'> Quali account mettere nelle uscite?</div> <div class='tr'>";
foreach($_SESSION['accounts'] as $acc) {
	$accName = $acc['name'];
	echo "<div class='td'> <input type='checkbox' name='uscite[]' value='$accName'>$accName</input> </div>";
}
echo "</div> <!-- /tr -->";
echo "</div> <!-- /table -->";

// Conti Correnti
	echo "<div class='table'> <div class='caption'>Di quali account vuoi mostrare la balance di inizio e fine periodo? </div> <div class='tr'>";
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
