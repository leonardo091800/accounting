<script src="https://kit.fontawesome.com/7e7c2e71c9.js" crossorigin="anonymous"></script>

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
<i class="fa-solid fa-book"></i> General Ledger </div>

<div id='menuReports' class='menu' onclick="javascript:$('#menuSelectReports').submit();"> 
<?php
  echo "
<form id='menuSelectReports' action='$root_Pages_storeSessionVariables_HTML' method='get'>
<input form='menuSelectReports' type='hidden' name='SESSION_what' value='menuSelected'>
<input form='menuSelectReports' type='hidden' name='SESSION_value' value='reports'>
</form>
";
?>
<i class="fa-solid fa-sheet-plastic"></i> Reports </div>

<div id='menuProfile' class='menu' onclick="javascript:$('#menuSelectProfile').submit();">
<?php
  echo "
<form id='menuSelectProfile' action='$root_Pages_storeSessionVariables_HTML' method='get'>
<input form='menuSelectProfile' type='hidden' name='SESSION_what' value='menuSelected'>
<input form='menuSelectProfile' type='hidden' name='SESSION_value' value='profile'>
</form>
";
?>
<i class="fa-solid fa-user"></i> Profile </div>
</div> <!-- /menu -->
