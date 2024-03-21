<?php
session_start();


$root=$_SERVER[DOCUMENT_ROOT]."/";

$root_Scripts = $root."z.scripts/";
$root_Scripts_showErrors = $root_Scripts."show_errors.php";
$root_Scripts_cleanInput = $root_Scripts."cleanInput.php";
$root_Scripts_checkSessionVariables = $root_Scripts."checkSessionVariables.php";
$root_Scripts_redirect = $root_Scripts."redirect.php";
$root_Scripts_style = $root_Scripts."style.php";
$root_Scripts_js = $root_Scripts."js.php";

// since we're still developing:
require_once $root_Scripts_showErrors;

$root_Pages = $root."pages/";
$root_Pages_menu = $root_Pages."menu.php";
$root_Pages_users = $root_Pages."users.php";
$root_Pages_accounts = $root_Pages."accounts.php";
$root_accountsManagement = $root_Pages."accountsManagement.php";
$root_accountsGroupsManagement = $root_Pages."accountsGroupsManagement.php";
$root_transactionAdd = $root_Pages."transaction-add.php";
$root_generalLedger = $root_Pages."generalLedger.php";
$root_reports = $root_Pages."reports.php";
$root_login = $root_Pages."login.php";
$root_signup = $root_Pages."signup.php";
$root_profile = $root_Pages."profile.php";

$root_DB = $root."db/";
$root_DB_setup = $root_DB."setup.php";
$root_DB_main = $root_DB."main.php";
$root_DB_defaults = $root_DB."defaults.php";

$root_DB_Public = $root."db_public/";
$root_getAccounts = $root_DB_Public."getAccounts.php";
$root_getAccountsGroups = $root_DB_Public."getAccountsGroups.php";
$root_getTransactions = $root_DB_Public."getTransactions.php";
$root_getReportsPersonalized = $root_DB_Public."getReportsPersonalized.php";

$root_Errors = $root."errors/";
$root_Errors_main = $root_Errors."main.php";

$root_Style = $root."style/";
$root_Style_main1 = $root_Style."main1.css";
$root_Style_main = $root_Style."main.css";
$root_Style_report = $root_Style."report.css";
$root_Style_reportDownload = $root_Style."reportDownload.css";

$root_js = $root."z.scripts/js/";
$root_js_toggleDisplay = $root_js."toggleDisplay.js";
$root_js_setStyle = $root_js."setStyle.js";
$root_js_jquery = $root_js."jquery.js";

$root_updates = $root."updates/";
$root_checkUpdate = $root_updates."checkUpdate.php";


$root_HTML='/';

$root_Pages_HTML = $root_HTML."pages/";
$root_storeSessionVariable_HTML = $root_Pages_storeSessionVariables_HTML = $root_Pages_HTML."storeSessionVariables.php";
$root_accountsManagement_HTML = $root_storeSessionVariable_HTML."?SESSION_what=menuSelected&SESSION_value=profile";
$root_login_HTML = $root_Pages_HTML."login.php";
$root_logout_HTML = $root_Pages_HTML."logout.php";
$root_signup_HTML= $root_Pages_HTML."signup.php";
$root_reportPersonalized_HTML = $root_Pages_HTML."reports/reportPersonalized.php";
$root_reportPersonalizedDownload_HTML = $root_Pages_HTML."reports/reportPersonalizedDownload.php";
$root_reportSumInsOutsCCs_HTML = $root_Pages_HTML."reports/reportSumInsOutsCCs.php";

$root_DB_HTML = $root_HTML."db_public/";
$root_DB_main_HTML = $root_DB_HTML."main.php";
$root_DB_add_HTML = $root_DB_HTML."add.php";
$root_DB_rm_HTML = $root_DB_HTML."rm.php";

$root_Scripts_HTML = $root_HTML."z.scripts/";
$root_Scripts_html2pdf_HTML = $root_Scripts_HTML."html2pdf/dist/html2pdf.bundle.min.js";


function echo_success($msg) {
	echo "<span class='echo_success'>$msg</span>";
}
function echo_success_ln($msg) {
	echo "<br>";
	echo_success($msg);
}

function echo_reloadLocation() {
	echo "<script> location.reload() </script>";
}
?>
