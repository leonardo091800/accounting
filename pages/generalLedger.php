<?php

// - - - DEBUGGING - - -
// echo "<br> accounts: "; print_r($_SESSION['accounts']);
// echo "<br> transactions: "; print_r($_SESSION['transactions']);
// - - - DEBUGGING - - -


echo "
";

// ------------------------------------------------------
// 1st Line of Table
// possibility to create account first
echo "
<div id='generalLedgerCaption' class='caption'> General Ledger </div>
<div id='generalLedger' class='table'>
  <div class='tr'>
    <div class='th2'>
    <form id='createAccount' action='$root_DB_add_HTML' method='GET'>
      <input form='createAccount' type='hidden' name='table' value='accounts'>
      <input form='createAccount' type='text' name='name' placeholder='e.g. revenue or cash or espenses' required>
      <br>
      <input form='createAccount' type='submit' value='create Account'>
    </form>
    </div> <!-- /th2 createAccount -->
";

// then echo all accounts
foreach($_SESSION['accounts'] as $acc) {
	echo "
    <div class='th2'>
      <div class='accName'> ".$acc['name']."</div>
	";

	// Edit Button
	echo "
<a href='$root_accountsManagement_HTML'>
  <button class=''> modify Account </button>
</a>
	";

	// Rm Button
	/*
	echo "
    <div>
    <form id='rmAccount{$acc['id']}' action='$root_DB_rm_HTML' method='get'>
      <input form='rmAccount{$acc['id']}' type='hidden' name='table' value='accounts'>
      <input form='rmAccount{$acc['id']}' type='hidden' name='id' value='{$acc['id']}'>
      <button form='rmAccount{$acc['id']}' type='submit' class='rmButton'> remove Account</button> 
    </form>
    </div>
	";
	 */
	echo "
    </div> <!-- /th2 {$acc['name']} -->
	";

}

echo "
    <div class='th2'> Note </div>
  </div> <!-- /tr -->
";



// ------------------------------------------------------
// 2nd Line of Table
// writing the headers of the second line of table:
echo "
  <div class='tr'>
    <div class='th2'></div> <!-- new account -->
";
foreach($_SESSION['accounts'] as $acc) {
	echo "<div class='th3'> Enter </div><div class='th3'> Exit </div>";
}

echo "
    <div class='th2'></div> <!-- note -->
  </div> <!-- /tr -->
";


// ------------------------------------------------------
// 3rd->(end-1) Line of Table
// then echo all transactions of each account
// (I'll also do the sum of each account, in order to not do the same for loop later)

// from v.1.1.0 every transaction can change multiple accounts, each transactions is defined by 'id'
$currentTrID = 0;
foreach($_SESSION['transactions'] as $tr) {
	if($currentTrID != $tr['id']) {
		// if trID is different then
		// create new <tr>
		// echo <th> with timestamp and rm button
		$currentTrID = $tr['id'];
		echo "
<div class='tr'>
  <div class='th2'>
    <div id='tr{$currentTrID}date'>
";
//		echo date('d/m/Y H:i:s', strtotime($tr['timestamp']));
		echo $tr['timestamp'];
			
		echo "
    </div>
    <script>
    // convert to datetimelocal
    $(document).ready(function() {
      var unformattedDate = $(\"#tr{$currentTrID}date\").html();
      var formattedDate = new Date(unformattedDate);
      var formattedDateLocal = formattedDate.toLocaleString();
//      var formattedTimeLocal = formattedDate.toLocaleDateString();
      var formattedTimeLocal = formattedDate.toLocaleTimeString();
      $(\"#tr{$currentTrID}date\").html(formattedDateLocal);
//      $(\"#tr{$currentTrID}date\").html(formattedDateLocal + ' ' + formattedTimeLocal);

/*
	console.log('unformattedDate = '+unformattedDate);
	console.log('formattedDate = '+formattedDate);
	console.log('formattedDateLocal = '+formattedDateLocal);
	console.log('formattedTimeLocal = '+formattedTimeLocal);
*/
    });
    </script>
		";


		// rm button for transaction
		echo "
    <form id='rmTransaction{$tr['id']}' action='$root_DB_rm_HTML' method='get'>
    <input form='rmTransaction{$tr['id']}' type='hidden' name='table' value='transactions'>
    <input form='rmTransaction{$tr['id']}' type='hidden' name='id' value='{$tr['id']}'>
    <button form='rmTransaction{$tr['id']}' type='submit' class='rmButton'> remove transaction</button> 
    </form>
  </div> <!-- /th2 timestamp -->
		";
	
		// also echo all <td> for all the accounts
		foreach($_SESSION['accounts'] as $acc) {
			$accID = $acc['id'];
			echo "
  <div class='td enter' id='t{$currentTrID}acc{$accID}enter'></div>
  <div class='td exit' id='t{$currentTrID}acc{$accID}exit'></div>
			";
		}

		// also echo the <th> note column
		echo "
  <div class='th2 stickRight'> ".$tr['note']."</div>
</div> <!-- /tr -->";
	}

	// now we need to populate the <td> we just created with the correct amounts
	// AND
	// create the SUM of all the accounts
	$accID = $tr['accounts.id'];
	if($tr['exit0orenter1'] == 0) {
		// if the amount exited from the account:
		echo '<script> $(document).ready(function() { $("#t'.$currentTrID.'acc'.$accID.'exit").html("'.style::toMoney($tr['amount']).'"); }); </script>';
		$_SESSION['sums'][$accID] -= $tr['amount'];
	} else {
		// if the amount entered the account:
		echo '<script> $(document).ready(function() { $("#t'.$currentTrID.'acc'.$accID.'enter").html("'.style::toMoney($tr['amount']).'"); }); </script>';
		$_SESSION['sums'][$accID] += $tr['amount'];
	}
}



// ------------------------------------------------------
// end Line of Table
// echo totals of each account

echo "
  <div class='tr'>
    <div class='th2 stickLeft'> SUM: </div>
";

foreach($_SESSION['accounts'] as $acc) {
	$accID = $acc['id'];
	echo "<div class='th2 sum'> ".style::toMoney($_SESSION['sums'][$accID])."</div>";
}

echo "
    <div class='th2 void stickRigth'></div>
  </div> <!-- /tr -->
</div> <!-- /table -->
";

echo "</div> <!-- /generalLedger -->";
?>

