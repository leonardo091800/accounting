<html>
<body>
<?php
require_once '/var/www/html/accounting/z.scripts/root.php';
require_once $root_Scripts_showErrors;
require_once $root_DB_main;
require_once $root_DB_defaults;
require_once $root_Scripts_cleanInput;
require_once $root_Errors_main;

/*
 * These commands must be given in the form $_GET['action']['parameters']
 *
 * action='update' or '...'
 * parameters = array(table='users'or'accounts', id='...', columnName='...', newValue='...', redirectTo='..._HTML')
 */
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
	unset($parameters['table']);
} else {
	die('in db_public/main.php no table found');
}

// always at least ID must be defined
if(isset($parameters['id'])) {
	$id = $parameters['id'];
	unset($parameters['id']);
} else {
	die('in db_public/main.php no id given');
}

// Redirecting Page must be defined
if(isset($parameters['redirectTo'])) {
	$redirectTo = $parameters['redirectTo'];
	unset($parameters['redirectTo']);
} else {
	die('where should I redirect you?');
//	$redirectTo = $root_Pages_HTML;
}

// if main values were given:
switch($action) {
case 'update':
	// THESE MUST BE CHANGED!! But let's keep it until Reports are not modified: (they use this function)
	if(isset($parameters['columnName'])) {
		if(db::update($table, $id, $parameters['columnName'], $parameters['newValue']) === 0) {
			alerts::echo_success();
			redirect::to_page($redirectTo);
		}
	} else {
		// for any parameters remaining...
		foreach($parameters as $columnName => $newValue) {
			if(db::update($table, $id, $columnName, $newValue) === 0) {
				alerts::echo_success();
				redirect::to_page($redirectTo);
			} else {
				alerts::echo_alert('something wrong with db_public::update');
				redirect::to_page($redirectTo);
			}
		}
	}

	break;

default:
	die("in db_public/main.php action $action not found");
	break;
}
?>
</body>
</html>
