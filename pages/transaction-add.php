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
      <input form='add{$tableTMP}Form' type='number' name='ta[exit{$i}][amount]' class='addInput exitAmount' step='0.01' required>
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
      <input form='add{$tableTMP}Form' type='number' name='ta[enter{$i}][amount]' class='addInput enterAmount' step='0.01' required>
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
    <div class='th2'>
      <textarea form='add{$tableTMP}Form' type='text' name='transaction[note]' class='addInput'></textarea>
    </div> <!-- /th2 -->
  </div> <!-- /tr -->
  <div class='tr'>
    <div class='th2 center'>
      <div id='add{$tableTMP}FormSubmit' class='menu'> ADD Transaction </div> 
    </div> <!-- /th2 -->
  </div> <!-- /tr -->
</form>
";

// script for checking if the sum of all accounts exiting are the same as the account entering
echo "
<script>
$(document).ready(function() { 
  $(\"#add{$tableTMP}FormSubmit\").click(function() {
    
    // check if the sum is ok
    var sumExit  = 0;
    var sumEnter = 0;
    $(\".exitAmount\").each(function() {
      sumExit += parseFloat($(this).val());
    });
    $(\".enterAmount\").each(function() {
      sumEnter += parseFloat($(this).val());
    });

 //   alert('sumExit='+sumExit+' sumEnter='+sumEnter);

    // if sum of enter and exit are same: submit the form
    if(sumExit==sumEnter) {
      $(\"#add{$tableTMP}Form\").submit();
    } else {
      alert('sum of Exit and Enter do not match! sumExit='+sumExit+' sumEnter='+sumEnter);
    }
  });
}); 
</script>
";
/*
 * - - - - - - - - - - END OF : add transaction box - - - - - - - - - - -
 */
?>
