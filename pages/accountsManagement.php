<?php
echo "
<div class='caption'> Accounts Management page </div>
<div id='accountsManagement' class='table secondaryTable'>
";
// list all accounts as slideshow with a RM button on top and MODIFY button on bottom
foreach($_SESSION['accounts'] as $a) {
	$accID = $a['id'];
	$accName = $a['name'];
	echo "
<div id='account{$accID}' class='table'>
  <div class='tr'>
";
	// Remove Button
	echo "
    <div class='th2'>
    <form id='rmAccount{$accID}' action='$root_DB_rm_HTML' method='get'>
      <input form='rmAccount{$accID}' type='hidden' name='table' value='accounts'>
      <input form='rmAccount{$accID}' type='hidden' name='id' value='{$accID}'>
      <button form='rmAccount{$accID}' type='submit' class='rmButton'> remove Account</button> 
    </form>
    </div> <!-- /th2 {$accName} -->
	";

	echo "
  </div> <!-- /tr -->
  <form id='updateAcc{$accID}' action='$root_DB_main_HTML' method='GET'>
  <input form='updateAcc{$accID}' type='hidden' name='action' value='update'>
  <input form='updateAcc{$accID}' type='hidden' name='parameters[table]' value='accounts'>
  <input form='updateAcc{$accID}' type='hidden' name='parameters[redirectTo]' value='$root_Pages_HTML'>
  <input form='updateAcc{$accID}' type='hidden' name='parameters[id]' value='$accID'>
    <div class='tr'>
      <div class='td'> Name </div>
      <div class='td'> <input form='updateAcc{$accID}' type='text' name='parameters[name]' value='$accName'> </input> </div>
    </div> <!-- /tr -->
    <div class='tr'>
      <div class='th2'> <input form='updateAcc{$accID}' type='submit' name='submit' value='UPDATE'> </input> </div>
    </div> <!-- /tr -->
  </form> <!-- /updateAcc{$accID} -->
</div>";
}
echo "</div> <!-- /accountsManagement -->";
?>
