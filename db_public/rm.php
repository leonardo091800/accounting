<html>
<body>
<?php
require_once '/var/www/html/accounting/z.scripts/root.php';
require_once $root_Scripts_showErrors;
require_once $root_Scripts_cleanInput;
require_once $root_DB_main;
require_once $root_Errors_main;

if(isset($_GET['table'])) {
	if(isset($_GET['id'])) {
		$table = cleanInput($_GET['table']);
		$id = cleanInput($_GET['id']);

		// extra for redirecting
		if(isset($_GET['redirectTO'])) {
			$redirectTO = cleanInput($_GET['redirectTO']);
		} else {
			$redirectTO = $root_Pages_HTML;
		}
		// extra param for redirecting
		/*
		if(isset($_GET['redirectTO'])) {
			$redirectTOparameters = cleanInputArr($_GET['redirectTOparameters']);
			$redirectTO = $redirectTO."?";
			foreach($redirectTOparameters as $p) {
				$redirectTO = $redirectTO."?";
		} else {
			$redirectTOparameters = array();
		}
		 */

		if(db::rm($table, $id) == '0') {
			alerts::echo_success();
			redirect::to_page($redirectTO);
		}
 	} 
	else {
		errors::echo_error('fieldNotGiven', 'id');
	} 
} 
else {
  errors::echo_error('fieldNotGiven', 'table');
}
/*
 */
?>
</body>
</html>
