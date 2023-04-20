<?php
/*
 * - - - - - - - - - - add transaction box - - - - - - - - - - -
 */
$tableTMP = "transactions";

/*
 * from v.1.1.0 there can be multiple accountsi nvolved in 1 transaction so, number of accounts involved in Add Transaction is defined by 
 * $_SESSION['transactionAdd']['accountsExitInvolved'] , set to default 1 at login
 * $_SESSION['transactionAdd']['accountsEnterInvolved'] , set to default 1 at login
 */ 
echo "
<form class='table' id='add{$tableTMP}Form' action='$root_DB_add_HTML' method='get'>
  <div class='caption'> Add Transaction </div>
  <input form='add{$tableTMP}Form' type='hidden' name='table' value='{$tableTMP}'>
  <div class='tr'>
    <div class='th2'> Amount &euro; From Account </div>
    <div class='th2 arrow'> &rarr; </div>
    <div class='th2'> Amount &euro; To Account </div>
    <div class='th2'> Date & Time </div>
    <div class='th2'> Note </div>
  </div>

  <div class='tr'>
    <div class='accountsInvolved'>
";

// echo input for amount and select possibile account (money exiting from it)
for($i=0; $i<$_SESSION['transactionAdd']['accountsExitInvolved']; $i++) {
	echo "
  <div class='accountInvolved'>
    <input form='add{$tableTMP}Form' type='hidden' name='ta[exit{$i}][exit0orenter1]' value='0'>
    <div id='amountExit{$i}' class='th3'> 
      <input form='add{$tableTMP}Form' type='number' name='ta[exit{$i}][amount]' class='addInput amount' step='0.01' required>
    </div>
    <div class='th2'> 
      <select form='add{$tableTMP}Form' class='addInput' name='ta[exit{$i}][accID]' required> 
	<option disabled selected value> -- select an option -- </option>
	";
	foreach($_SESSION['accounts'] as $acc) {
		echo "<option value='".$acc['id']."'>".$acc['name']."</option>";
	}
echo "
      </select>
    </div> <!-- / accExit{$i} -->
  </div> <!-- / accountInvolved -->
";
}

// also echo a button to add an account where money is exiting from:
echo "
      <div class='accountInvolved'> 
        <div id='addAccountExit' onclick=\"location.href='$root_storeSessionVariable_HTML?SESSION_what=transactionAddaccountsExitInvolved&SESSION_value=".($_SESSION['transactionAdd']['accountsExitInvolved']+1)."'\" class='menu'>
          Add Account
        </div> 
      </div>
    </div> <!-- / accountsInvolved -->
";

echo "
    <div class='th2 arrow'> &rarr; </div>
      <div class='accountsInvolved'>
";

// echo input for amount and select possibile account (money entering it)
for($i=0; $i<$_SESSION['transactionAdd']['accountsEnterInvolved']; $i++) {
	echo "
  <div class='accountInvolved'>
    <input form='add{$tableTMP}Form' type='hidden' name='ta[enter{$i}][exit0orenter1]' value='1'>
    <div id='amountEnter{$i}' class='th3'> 
      <input form='add{$tableTMP}Form' type='number' name='ta[enter{$i}][amount]' class='addInput amount' step='0.01' required>
    </div>
    <div class='th2'> 
      <select form='add{$tableTMP}Form' class='addInput' name='ta[enter{$i}][accID]' required> 
	<option disabled selected value> -- select an option -- </option>
	";
	foreach($_SESSION['accounts'] as $acc) {
		echo "<option value='".$acc['id']."'>".$acc['name']."</option>";
	}
echo "
      </select>
    </div> <!-- / accEnter{$i} -->
  </div> <!-- / accountInvolved -->
";
}

// also echo a button to add an account where money is exiting from:
echo "
      <div class='accountInvolved'> 
        <div id='addAccountEnter' onclick=\"location.href='$root_storeSessionVariable_HTML?SESSION_what=transactionAddaccountsEnterInvolved&SESSION_value=".($_SESSION['transactionAdd']['accountsEnterInvolved']+1)."'\" class='menu'>
          Add Account
        </div> 
      </div>
    </div> <!-- / accountsInvolved -->
";

echo "
    <div class='th2'> 
       <input form='add{$tableTMP}Form' type='datetime-local' name='transaction[timestamp]' value='".date('Y-m-d\TH:i:s')."' class='addInput' required>
    </div>
    <div class='th2'> <input form='add{$tableTMP}Form' type='text' name='transaction[note]' class='addInput'>
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
