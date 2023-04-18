<?php
/*
 * - - - - - - - - - - add transaction box - - - - - - - - - - -
 */
$tableTMP = "transactions";
echo "
<form class='table' id='add{$tableTMP}Form' action='$root_DB_add_HTML' method='get'>
  <div class='caption'> Add Transaction </div>
  <input form='add{$tableTMP}Form' type='hidden' name='table' value='{$tableTMP}'>
  <div class='tr'>
    <div class='th2'> Amount &euro; </div>
    <div class='th2'> Date & Time </div>
    <div class='th2'> From Account </div>
    <div class='th2 arrow'> &rarr; </div>
    <div class='th2'> To Account </div>
    <div class='th2'> Note </div>
  </div>
  <div class='tr'>
    <div class='th2'> <input form='add{$tableTMP}Form' type='number' name='amount' class='addInput amount' step='0.01' required>
    </div><div class='th2'> <input form='add{$tableTMP}Form' type='datetime-local' name='timestamp' value='".date('Y-m-d\TH:i:s')."' class='addInput' required>
    </div><div class='th2'> <select form='add{$tableTMP}Form' class='addInput' name='accounts_out_id' required> 
	<option disabled selected value> -- select an option -- </option>
	";
	foreach($_SESSION['accounts'] as $acc) {
		echo "<option value='".$acc['id']."'>".$acc['name']."</option>";
	}
echo "
      </select>
    </div><div class='th2 arrow'> &rarr;
    </div><div class='th2'> <select form='add{$tableTMP}Form' class='addInput' name='accounts_in_id' required> 
	<option disabled selected value> -- select an option -- </option>
	";
	foreach($_SESSION['accounts'] as $acc) {
		echo "<option value='".$acc['id']."'>".$acc['name']."</option>";
	}
echo "
      </select>
    </div><div class='th2'> <input form='add{$tableTMP}Form' type='text' name='note' class='addInput'>
    </div> <!-- /th2 -->
  </div> <!-- /tr -->
  <div class='tr'>
    <div class='th2 center' style='width:100%'>
      <button form='add{$tableTMP}Form' type='submit' class='addButton'> ADD Transaction </button> 
    </div> <!-- /th2 -->
  </div> <!-- /tr -->
</form>
";
/*
 * - - - - - - - - - - END OF : add transaction box - - - - - - - - - - -
 */
?>
