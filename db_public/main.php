<html>
<body>
<?php
require_once '/var/www/html/accounting/z.scripts/root.php';
require_once $root_Scripts_showErrors;
require_once $root_DB_main;
require_once $root_DB_defaults;
require_once $root_Scripts_cleanInput;
require_once $root_Errors_main;

// always give what to do
if(isset($_GET['action'])) {
	$action = cleanInput($_GET['action']);
} else {
	die('in db_public/main.php no action found');
}

// always have parameters
if(isset($_GET['parameters'])) {
	$a = filter_input_arraY(INPUT_GET);
	$parameters = cleanInputArr($a['parameters']);
	print_r($parameters);
} else {
	die('in db_public/main.php no parameters found');
}

// always at least table name must be defined
if(isset($parameters['table'])) {
	$table = $parameters['table'];
} else {
	die('in db_public/main.php no table found');
}

// if main values were given:
switch($action) {
case 'update':
	if(db::update($table, $parameters['id'], $parameters['columnName'], $parameters['newValue']) == 0) {
		alerts::echo_success();
		if(isset($parameters['redirectTo'])) {
			$redirectTo = $parameters['redirectTo'];
		} else {
			die('where should I redirect you?');
//			$redirectTo = $root_Pages_HTML;
		}
		redirect::to_page($redirectTo);
	}
	break;

default:
	die("in db_public/main.php action $action not found");
	break;
}
?>
</body>
</html>
