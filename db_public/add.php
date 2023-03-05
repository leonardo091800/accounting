<html>
<body>
<?php
require_once '/var/www/html/accounting/z.scripts/root.php';
require_once $root_Scripts_showErrors;
require_once $root_DB_main;
require_once $root_Scripts_cleanInput;
require_once $root_Errors_main;
$conn = db::connect();

if(isset($_GET['table'])) {
  if(isset($_GET['name'])) {
    if(isset($_GET['surname'])) {
	$table = cleanInput($_GET['table']);
	$name = cleanInput($_GET['name']);
	$surname = cleanInput($_GET['surname']);

	switch ($table) {
	case 'users':
		$parameters = array('name'=>$name, 'surname'=>$surname,);
		if(db::checkIfExist('users', $parameters)) {
			errors::echo_error('userAlreadyExist', "$name $surname");
		} else {
			if(db::add('users', $parameters) == 0) {
				alerts::echo_success();
				redirect::to_page($root_Pages_HTML);
			}
		}
		break;
	default:
      		errors::echo_error('tableNotExist', "$table");
	}
    } 
    else {
      errors::echo_error('fieldNotGiven', 'Name');
    }
  } 
  else {
    errors::echo_error('fieldNotGiven', 'Surname');
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
