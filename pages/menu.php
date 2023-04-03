<div id='menu'> 
<!-- 
<div id='menuGeneralLedger' class='menu' onclick="toggleDisplay($('#generalLedger'))">  General Ledger </div>
<div id='menuReports' class='menu' onclick="toggleDisplay($('#reports'))"> Reports </div>
-->
<div id='menuGeneralLedger' class='menu' onclick="javascript:$('#menuSelectGeneralledger').submit();">
<?php
  echo "
<form id='menuSelectGeneralledger' action='$root_Pages_storeSessionVariables_HTML' method='get'>
<input form='menuSelectGeneralledger' type='hidden' name='SESSION_what' value='menuSelected'>
<input form='menuSelectGeneralledger' type='hidden' name='SESSION_value' value='generalLedger'>
</form>
";
?>
 General Ledger </div>
<div id='menuReports' class='menu' onclick="javascript:$('#menuSelectReports').submit();"> 
<?php
  echo "
<form id='menuSelectReports' action='$root_Pages_storeSessionVariables_HTML' method='get'>
<input form='menuSelectReports' type='hidden' name='SESSION_what' value='menuSelected'>
<input form='menuSelectReports' type='hidden' name='SESSION_value' value='reports'>
</form>
";
?>
Reports </div>
</div> <!-- /menu -->

