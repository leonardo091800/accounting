<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/z.scripts/root.php';
require_once $root_Errors_main;

session_destroy();
redirect::to_page($root_Pages_HTML, "3000");
echo "logged out, redirecting in 3 seconds...";
?>
