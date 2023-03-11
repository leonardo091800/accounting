<?php
session_start();


$root = "/var/www/html/accounting/";

$root_Scripts = $root."z.scripts/";
$root_Scripts_showErrors = $root_Scripts."show_errors.php";
$root_Scripts_cleanInput = $root_Scripts."cleanInput.php";
$root_Scripts_checkSessionVariables = $root_Scripts."checkSessionVariables.php";

// since we're still developing:
require_once $root_Scripts_showErrors;

$root_Pages = $root."pages/";
$root_Pages_users = $root_Pages."users.php";
$root_Pages_accounts = $root_Pages."accounts.php";
$root_Pages_transactions = $root_Pages."transactions.php";

$root_DB = $root."db/";
$root_DB_setup = $root_DB."setup.php";
$root_DB_main = $root_DB."main.php";
$root_DB_defaults = $root_DB."defaults.php";

$root_Errors = $root."errors/";
$root_Errors_main = $root_Errors."main.php";

$root_Style = $root."style/";
$root_Style_main = $root_Style."main.css";

$root_Modals= $root."modals/";
$root_Modals_main = $root_Modals."main.php";


$root_HTML = "/accounting/";

$root_Pages_HTML = $root_HTML."pages/";
$root_Pages_storeSessionVariables_HTML = $root_Pages_HTML."storeSessionVariables.php";

$root_DB_HTML = $root_HTML."db_public/";
$root_DB_add_HTML = $root_DB_HTML."add.php";
$root_DB_rm_HTML = $root_DB_HTML."rm.php";

?>
