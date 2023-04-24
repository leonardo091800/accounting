<?php
echo "
<div class='caption'> Accounts Management page </div>
<div id='accountsManagement' class='table secondaryTable managementTable'>
";

// Add Account
echo "
<div id='addAccount' class='table'>
  <div class='tr'>
    <div class='th2'>
      Create new Account:
    </div>
  </div>
  <div class='tr'>
    <div class='td'>
      Name:
    </div>
    <div class='td'>
      <form id='createAccount' action='$root_DB_add_HTML' method='GET'>
        <input form='createAccount' type='hidden' name='table' value='accounts'>
        <input form='createAccount' type='text' name='name' placeholder='e.g. revenue or cash or espenses' required>
      </form>
    </div>
  </div>
  <div class='tr'>
    <div id='createAccountButton' class='th2 menu' tabindex='0'>
      Create Account
    </div>
  </div>
</div>
<script>
$(document).ready(function() { 
  // Subimt form when div is pressed with keyboard 
  $(\"#createAccountButton\").keypress(function() {
    $(\"#createAccount\").submit();
  });
  // Submit Form when div is pressed with mouse
  $(\"#createAccountButton\").click(function() {
    $(\"#createAccount\").submit();
   });
}); 
</script>
";

// list all accounts as slideshow with a RM button on top and MODIFY button on bottom
foreach($_SESSION['accounts'] as $a) {
	$accID = $a['id'];
	$accName = $a['name'];
	echo "
<div id='account{$accID}' class='table'>
";


	// Remove Button
	echo "
  <div class='tr'>
    <form id='rmAccount{$accID}' action='$root_DB_rm_HTML' method='get'>
      <input form='rmAccount{$accID}' type='hidden' name='table' value='accounts'>
      <input form='rmAccount{$accID}' type='hidden' name='id' value='{$accID}'>
    </form>

    <div id='rmAccount{$accID}Button' class='th2 menu' tabindex='0'> Remove account </div>
  </div> <!-- /tr -->

<script>
$(document).ready(function() { 
  // Subimt form when div is pressed with keyboard 
  $(\"#rmAccount{$accID}Button\").keypress(function() {
    $(\"#rmAccount{$accID}\").submit();
  });
  // Submit Form when div is pressed with mouse
  $(\"#rmAccount{$accID}Button\").click(function() {
    $(\"#rmAccount{$accID}\").submit();
   });
}); 
</script>
	";

	// Name
	echo "
  <div class='tr'>
    <div class='td'> Name </div>
    <div class='td'>
      <form id='updateAcc{$accID}' action='$root_DB_main_HTML' method='GET'>
        <input form='updateAcc{$accID}' type='hidden' name='action' value='update'>
        <input form='updateAcc{$accID}' type='hidden' name='parameters[table]' value='accounts'>
        <input form='updateAcc{$accID}' type='hidden' name='parameters[redirectTo]' value='$root_Pages_HTML'>
        <input form='updateAcc{$accID}' type='hidden' name='parameters[id]' value='$accID'>
        <input form='updateAcc{$accID}' type='text' name='parameters[name]' value='$accName'> 
      </form> <!-- /updateAcc{$accID} -->
    </div> <!-- /td -->
  </div> <!-- /tr -->
	";


	// groups
	echo "
    <div class='tr'>
      <div class='td'> Group </div>
      <div class='td'> 
	";
	foreach($_SESSION['accountsGroups'] as $accGroup) {
		$accGroupID = $accGroup['id'];
		// if account already in group 
		if($a['accGroupID'] == $accGroupID) {
			echo "
        <form id='AxG{$accID}' action='$root_DB_rm_HTML' method='GET'>
          <input form='AxG{$accID}' type='hidden' name='table' value='account_x_group'>
          <input form='AxG{$accID}' type='hidden' name='id' value='{$a['axgID']}'>
        </form>
<script>
$(document).ready(function() { 
  $(\"#AxG{$accID}Button\").addClass('selected');
});
</script>
			";
		} else {
		// if account is NOT in group 
			echo "
        <form id='AxG{$accID}' action='$root_DB_add_HTML' method='GET'>
          <input form='AxG{$accID}' type='hidden' name='table' value='account_x_group'>
          <input form='AxG{$accID}' type='hidden' name='redirectTo' value='$root_Pages_HTML'>
          <input form='AxG{$accID}' type='hidden' name='accID' value='$accID'>
          <input form='AxG{$accID}' type='hidden' name='agID' value='$accGroupID'>
        </form>
<script>
$(document).ready(function() { 
  $(\"#AxG{$accID}Button\").addClass('unselected');
});
</script>
			";
		}

		echo "
        <div id='AxG{$accID}Button' class='button' tabindex='0'> {$accGroup['name']} </div>
<script>
$(document).ready(function() { 
  // Subimt form when div is pressed with keyboard 
  $(\"#AxG{$accID}Button\").keypress(function() {
    $(\"#AxG{$accID}\").submit();
  });
  // Submit Form when div is pressed with mouse
  $(\"#AxG{$accID}Button\").click(function() {
    $(\"#AxG{$accID}\").submit();
   });
}); 
</script>
		";

	}
	echo "
       </div> <!-- /td -->
    </div> <!-- /tr -->
	";


	// Update Button
	echo "
    <div class='tr'>
      <div id='updateAcc{$accID}Button' class='th2 menu' tabindex='0'> UPDATE </div>
    </div> <!-- /tr -->
  </div>

<script>
$(document).ready(function() { 
  // Subimt form when div is pressed with keyboard 
  $(\"#updateAcc{$accID}Button\").keypress(function() {
    $(\"#updateAcc{$accID}\").submit();
  });
  // Submit Form when div is pressed with mouse
  $(\"#updateAcc{$accID}Button\").click(function() {
    $(\"#updateAcc{$accID}\").submit();
   });
}); 
</script>
";
}
echo "</div> <!-- /accountsManagement -->";
?>
