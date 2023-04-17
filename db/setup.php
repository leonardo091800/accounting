<?php
class db_setup {

	public static function setup($s, $u, $p, $db) {
		global $root_Pages_HTML;

		if(db_setup::createDatabase($s, $u, $p, $db) != 0) {
			die('in db_setup::setup smt wrong with db creation');
		}

		$conn = db::connect();
		
		if(db_setup::createTables($conn) != 0) {
			die('in db_setup::setup smt wrong with tables creation');
		}
		if(db_setup::createReportTables($conn) != 0) {
			die('in db_setup::setup smt wrong with tables population');
		}
		if(db_setup::populateTables($conn) != 0) {
			die('in db_setup::setup smt wrong with tables population');
		}

		// if setup is correct:
		header("Location: /accounting");
	}




	/* 
	 * Create DB
	 */
	public static function createDatabase($s, $u, $p, $db) {
		try {
			$conn = new PDO("mysql:host=$s", $u, $p);
			// set the PDO error mode to exception
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} 
		catch(PDOException $e) {
			echo "Connection failed: " . $e->getMessage();
			exit;
		}


		// creating database
		try {
//			echo "initiliasing database...";
			$conn->exec("CREATE DATABASE $db");

			return 0;
		} 
		catch(PDOException $e) {
			echo "<br> Cannot create DB: " . $e->getMessage();
			exit;
		}


		// giving privileges to user over db
		try {
//			echo "giving privileges to user over db...";
			$conn->exec("GRANT ALL PRIVILEGES ON $db.* TO '$u'@'localhost';");
			$conn->exec("FLUSH PRIVILEGES;");

			return 0;
		} 
		catch(PDOException $e) {
			echo "<br> Cannot give privileges to user over db: " . $e->getMessage();
			exit;
		}
	}



	/*
	 * Create Tables
	 */
	public static function createTables($conn) {
//		echo "initiliasing tables...";
$sql_users = "CREATE TABLE `accounting_db`.`users` (`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT , `mail` VARCHAR (50) NOT NULL , `psw` CHAR (128) NOT NULL , `name` VARCHAR(30) NOT NULL , `surname` VARCHAR(30) NOT NULL , `admin` BOOLEAN NOT NULL DEFAULT '0', `date_creation` DATE NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`)) ENGINE = InnoDB;";
$sql_accountTypes = "CREATE TABLE `accounting_db`.`account_types` (`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT , `name` VARCHAR(30) NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;";
$sql_accounts = "CREATE TABLE `accounting_db`.`accounts` (`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT , `account_types.id` INT(11) UNSIGNED , `users.id` INT(11) UNSIGNED , `name` VARCHAR(30) NOT NULL , `date_open` DATE , `date_close` DATE , PRIMARY KEY (`id`)) ENGINE = InnoDB;";
$sql_accounts_users_constraint = "ALTER TABLE `accounting_db`.`accounts` ADD CONSTRAINT `accounts-users` FOREIGN KEY (`users.id`) REFERENCES `users`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;";
$sql_accounts_accountTypes_constraint = "ALTER TABLE `accounting_db`.`accounts` ADD CONSTRAINT `accounts-account_types` FOREIGN KEY (`account_types.id`) REFERENCES `account_types`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;";

$sql_transactionTypes = "CREATE TABLE `accounting_db`.`transaction_types` (`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT , `name` VARCHAR(30) NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;";
$sql_transactions = "CREATE TABLE `accounting_db`.`transactions` (`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT , `transaction_types.id` INT(11) UNSIGNED , `accounts.in.id` INT(11) UNSIGNED NOT NULL , `accounts.out.id` INT(11) UNSIGNED NOT NULL , `timestamp` TIMESTAMP NOT NULL , `amount` DECIMAL(10,4) NOT NULL , `note` VARCHAR(50), PRIMARY KEY (`id`)) ENGINE = InnoDB;";
$sql_transactions_transactionTypes_constraint = "ALTER TABLE `accounting_db`.`transactions` ADD CONSTRAINT `transactions-transaction_types` FOREIGN KEY (`transaction_types.id`) REFERENCES `transaction_types`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;";
$sql_transactions_accounts_in_constraint = "ALTER TABLE `accounting_db`.`transactions` ADD CONSTRAINT `transactions-accounts.in` FOREIGN KEY (`accounts.in.id`) REFERENCES `accounts`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;";
$sql_transactions_accounts_out_constraint = "ALTER TABLE `accounting_db`.`transactions` ADD CONSTRAINT `transactions-accounts.out` FOREIGN KEY (`accounts.out.id`) REFERENCES `accounts`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;";

		$tablesSQL = array(
			$sql_users, 
			$sql_accountTypes, 
			$sql_accounts, 
			$sql_accounts_users_constraint, 
			$sql_accounts_accountTypes_constraint, 
			$sql_transactionTypes,
			$sql_transactions,
			$sql_transactions_transactionTypes_constraint,
			$sql_transactions_accounts_in_constraint,
			$sql_transactions_accounts_out_constraint
		);

		foreach($tablesSQL as $tableSQL) {
			if(db_setup::doSQL($conn, $tableSQL, "createTables") == 0) {
//				echo "tables created successfully";
			}
			else {
				die('error in db_setup::createTables, sql = '.$tableSQL);
			}
		}
	return 0;
	} 


	/*
	 * Create Reports
	 */
	public static function createReportTables($conn) {
		$reportTablesSQL = array(
'sql_reports' => "CREATE TABLE `accounting_db`.`reports` (`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT , `name` VARCHAR(30) NOT NULL ,`description` VARCHAR(100) , PRIMARY KEY (`id`)) ENGINE = InnoDB;",

'sql_report_captions' => "CREATE TABLE `accounting_db`.`report_captions` (`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT , `table` VARCHAR(30) DEFAULT 'report_captions' , `tableFrom` VARCHAR(30) DEFAULT 'reports' , `reports.id` INT(11) UNSIGNED , `name` VARCHAR(30) , `description` VARCHAR(100) , PRIMARY KEY (`id`)) ENGINE = InnoDB;",
'sql_reportCaptions_reports_constraint' => "ALTER TABLE `accounting_db`.`report_captions` ADD CONSTRAINT `reports.id-reports` FOREIGN KEY (`reports.id`) REFERENCES `reports`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;",

'sql_report_caption_trs' => "CREATE TABLE `accounting_db`.`report_caption_trs` (`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT , `table` VARCHAR(30) DEFAULT 'report_caption_trs' , `tableFrom` VARCHAR(30) DEFAULT 'report_captions' , `report_captions.id` INT(11) UNSIGNED , `name` VARCHAR(30) , `description` VARCHAR(100) , PRIMARY KEY (`id`)) ENGINE = InnoDB;",
'sql_reportCaptionTrs_reports_constraint' => "ALTER TABLE `accounting_db`.`report_caption_trs` ADD CONSTRAINT `report_captions.id-report_captions` FOREIGN KEY (`report_captions.id`) REFERENCES `report_captions`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;",

'sql_report_caption_tr_tds' => "CREATE TABLE `accounting_db`.`report_caption_tr_tds` (`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT , `table` VARCHAR(30) DEFAULT 'report_caption_tr_tds' , `tableFrom` VARCHAR(30) DEFAULT 'report_caption_trs' , `report_caption_trs.id` INT(11) UNSIGNED , `name` VARCHAR(30) , `description` VARCHAR(100) , `class` VARCHAR(30) , PRIMARY KEY (`id`)) ENGINE = InnoDB;",
'sql_reportCaptionTrTds_reportTrs_constraint' => "ALTER TABLE `accounting_db`.`report_caption_tr_tds` ADD CONSTRAINT `report_caption_trs.id-report_caption_trs` FOREIGN KEY (`report_caption_trs.id`) REFERENCES `report_caption_trs`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;",

'sql_report_caption_tr_td_values' => "CREATE TABLE `accounting_db`.`report_caption_tr_td_values` (`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT , `table` VARCHAR(30) DEFAULT 'report_caption_tr_td_values' , `tableFrom` VARCHAR(30) DEFAULT 'report_caption_tr_tds' , `report_caption_tr_tds.id` INT(11) UNSIGNED , `name` VARCHAR(1000) , PRIMARY KEY (`id`)) ENGINE = InnoDB;",
'sql_reportCaptionTrTdValues_reportCaptionTrTds_constraint' => "ALTER TABLE `accounting_db`.`report_caption_tr_td_values` ADD CONSTRAINT `report_caption_tr_td_values.id-report_tr_tds` FOREIGN KEY (`report_caption_tr_tds.id`) REFERENCES `report_caption_tr_tds`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;"
		);

		foreach($reportTablesSQL as $i=>$sql) {
			if(db_setup::doSQL($conn, $sql, $i) == 0) {
//				echo "sql $i sent successfully";
			}
			else {
				die("error in db_setup::createReportTables, <br> $i <br> sql = $sql");
			}
		}
	return 0;
	} 



	/*
	 * Populate Tables
	 */
	public static function populateTables($conn) {
		// defining default accounts 
		$accounts_arr = array();

		return 0;
	}



	/*
	 * generic function do SQL
	 */
	public static function doSQL($conn, $sqlCommand, $from='doSQL') {
		try {
			$conn->exec($sqlCommand);
			return 0;
		} 
		catch(PDOException $e) {
			echo "<br> error in $from" . $e->getMessage();
			exit;
		}
	}


	/*
	 * PATCHES
	 */

	/*
	 * 2023-04-06: add mail and password to users
	 */
	public static function patch20230406($conn) {
		$sql1 = "ALTER TABLE users ADD psw CHAR (128) AFTER id;";
		$sql2 = "ALTER TABLE users ADD mail VARCHAR (50) AFTER id;";
		try {
			doSQL($conn, $sql1, "patch20230406");
			doSQL($conn, $sql2, "patch20230406");
			return 0;
		} 
		catch(PDOException $e) {
			echo "<br> error in patch 2023 04 06" . $e->getMessage();
			exit;
		}
	}
}
?>
