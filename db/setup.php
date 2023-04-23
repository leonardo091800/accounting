<?php
require_once $root_Errors_main;
require_once $root_DB_main; 		// patch v.1.1.0 use the function db::add
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

$sql_accountsGroups = "CREATE TABLE `accounting_db`.`accounts_groups` (`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT , `users.id` INT(11) UNSIGNED , `name` VARCHAR(50) NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;";
$sql_accountsGroups_users_constraint = "ALTER TABLE `accounting_db`.`accounts_groups` ADD CONSTRAINT `accounts_groups-users` FOREIGN KEY (`users.id`) REFERENCES `users`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;";
$sql_accountXGroup = "CREATE TABLE `accounting_db`.`account_x_group` (`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT , `accounts.id` INT(11) UNSIGNED , `accounts_groups.id` INT(11) UNSIGNED ,  PRIMARY KEY (`id`)) ENGINE = InnoDB;";
$sql_accounts = "CREATE TABLE `accounting_db`.`accounts` (`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT , `account_types.id` INT(11) UNSIGNED , `users.id` INT(11) UNSIGNED , `name` VARCHAR(50) NOT NULL , `date_open` DATE , `date_close` DATE , PRIMARY KEY (`id`)) ENGINE = InnoDB;";
$sql_accounts_users_constraint = "ALTER TABLE `accounting_db`.`accounts` ADD CONSTRAINT `accounts-users` FOREIGN KEY (`users.id`) REFERENCES `users`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;";
$sql_accountXGroup_accounts_constraint = "ALTER TABLE `accounting_db`.`account_x_group` ADD CONSTRAINT `account_x_group-accounts` FOREIGN KEY (`accounts.id`) REFERENCES `accounts`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;";
$sql_accountXGroup_accountsGroups_constraint = "ALTER TABLE `accounting_db`.`account_x_group` ADD CONSTRAINT `account_x_group-accounts_groups` FOREIGN KEY (`accounts_groups.id`) REFERENCES `accounts_groups`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;";

$sql_transactionTypes = "CREATE TABLE `accounting_db`.`transaction_types` (`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT , `name` VARCHAR(30) NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;";
$sql_transactionAccountsInvolved = "CREATE TABLE `accounting_db`.`transaction_accounts_involved` (`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT , `transactions.id` INT(11) UNSIGNED NOT NULL , `accounts.id` INT(11) UNSIGNED NOT NULL ,`exit0orenter1` BOOLEAN NOT NULL , `amount` DECIMAL(10,4) NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;";
$sql_transactions = "CREATE TABLE `accounting_db`.`transactions` (`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT , `transaction_types.id` INT(11) UNSIGNED , `timestamp` TIMESTAMP NOT NULL , `note` VARCHAR(100), PRIMARY KEY (`id`)) ENGINE = InnoDB;";
$sql_transactions_transactionTypes_constraint = "ALTER TABLE `accounting_db`.`transactions` ADD CONSTRAINT `transactions-transaction_types` FOREIGN KEY (`transaction_types.id`) REFERENCES `transaction_types`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;";
$sql_transactionAccountsInvolved_transactions_constraint = "ALTER TABLE `accounting_db`.`transaction_accounts_involved` ADD CONSTRAINT `transactionAccountsInvolved-transactions` FOREIGN KEY (`transactions.id`) REFERENCES `transactions`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;";
$sql_transactionAccountsInvolved_accounts_constraint = "ALTER TABLE `accounting_db`.`transaction_accounts_involved` ADD CONSTRAINT `transactionAccountsInvolved-accounts` FOREIGN KEY (`accounts.id`) REFERENCES `accounts`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;";

		$tablesSQL = array(
			$sql_users, 

			$sql_accountsGroups, 
			$sql_accountsGroups_users_constraint,
			$sql_accountXGroup, 
			$sql_accounts, 
			$sql_accounts_users_constraint, 
			$sql_accountXGroup_accounts_constraint, 
			$sql_accountXGroup_accountsGroups_constraint, 

			$sql_transactionTypes,
			$sql_transactionAccountsInvolved,
			$sql_transactions,
			$sql_transactions_transactionTypes_constraint,
			$sql_transactionAccountsInvolved_transactions_constraint,
			$sql_transactionAccountsInvolved_accounts_constraint,
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
			db_setup::doSQL($conn, $sql1, "patch20230406");
			db_setup::doSQL($conn, $sql2, "patch20230406");
			return 0;
		} 
		catch(PDOException $e) {
			echo "<br> error in patch 2023 04 06" . $e->getMessage();
			exit;
		}
	}

	/*
	 * 2023-04-20: 1 transactions can affect multiple accounts
	 */
	public static function patchv110($conn) {
		try {
			// create table transaction_accounts_involved 
$sql_transactionAccountsInvolved = "CREATE TABLE `accounting_db`.`transaction_accounts_involved` (`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT , `transactions.id` INT(11) UNSIGNED NOT NULL , `accounts.id` INT(11) UNSIGNED NOT NULL ,`exit0orenter1` BOOLEAN NOT NULL , `amount` DECIMAL(10,4) NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;";
$sql_transactionAccountsInvolved_transactions_constraint = "ALTER TABLE `accounting_db`.`transaction_accounts_involved` ADD CONSTRAINT `transactionAccountsInvolved-transactions` FOREIGN KEY (`transactions.id`) REFERENCES `transactions`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;";
$sql_transactionAccountsInvolved_accounts_constraint = "ALTER TABLE `accounting_db`.`transaction_accounts_involved` ADD CONSTRAINT `transactionAccountsInvolved-accounts` FOREIGN KEY (`accounts.id`) REFERENCES `accounts`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;";
			db_setup::doSQL($conn, $sql_transactionAccountsInvolved, "patch-v.1.1.0 - adding transaction_accounts_involved");
			db_setup::doSQL($conn, $sql_transactionAccountsInvolved_transactions_constraint, "patch-v.1.1.0 - adding constraint to transactions");
			db_setup::doSQL($conn, $sql_transactionAccountsInvolved_accounts_constraint, "patch-v.1.1.0 - adding constraint to accounts");
	
			// get all transactions
			$query = $conn->prepare("SELECT * from transactions");
			$query->execute();
			$transactions = $query->fetchAll(PDO::FETCH_ASSOC);
//			echo "<br> transactions: <br>"; print_r($transactions);
	
			foreach($transactions as $t) {
				// migrate accounts.in/out of table transactions to transaction_accounts_involved 
				$parameters1 = array(
					'transactions.id' => $t['id'],
					'accounts.id' => $t['accounts.in.id'],
					'exit0orenter1' => 1,
					'amount' => $t['amount']
				);
				$parameters2 = array(
					'transactions.id' => $t['id'],
					'accounts.id' => $t['accounts.out.id'],
					'exit0orenter1' => 0,
					'amount' => $t['amount']
				);
				$result1 = db::add("transaction_accounts_involved", $parameters1);
				$result2 = db::add("transaction_accounts_involved", $parameters2);
			}
	
			// remove constraints and columns in transactions
			$sql_rm_constraint_accounts_in  = "ALTER TABLE transactions DROP CONSTRAINT `transactions-accounts.in`";
			$sql_rm_constraint_accounts_out = "ALTER TABLE transactions DROP CONSTRAINT `transactions-accounts.out`";
			$sql_alter_column_accounts_in  = "ALTER TABLE transactions MODIFY COLUMN `accounts.in.id` INT(11) UNSIGNED";
			$sql_alter_column_accounts_out = "ALTER TABLE transactions MODIFY COLUMN `accounts.out.id` INT(11) UNSIGNED";
			$sql_alter_column_amount = "ALTER TABLE transactions MODIFY COLUMN `amount` DECIMAL(10,4)";
			$sql_rm_column_accounts_in  = "ALTER TABLE transactions DROP COLUMN `accounts.in.id`";
			$sql_rm_column_accounts_out = "ALTER TABLE transactions DROP COLUMN `accounts.out.id`";
			$sql_rm_column_amount = "ALTER TABLE transactions DROP COLUMN `amount`";
			db_setup::doSQL($conn, $sql_rm_constraint_accounts_in,  "patch-v.1.1.0 - rm constraint to accounts in of transactions table");
			db_setup::doSQL($conn, $sql_rm_constraint_accounts_out, "patch-v.1.1.0 - rm constraint to accounts out of transactions table");
			db_setup::doSQL($conn, $sql_alter_column_accounts_in,  "patch-v.1.1.0 - alter accounts.in of transactions table");
			db_setup::doSQL($conn, $sql_alter_column_accounts_out, "patch-v.1.1.0 - alter accounts.out of transactions table");
			db_setup::doSQL($conn, $sql_alter_column_amount, "patch-v.1.1.0 - alter amount of transactions table");
			db_setup::doSQL($conn, $sql_rm_column_accounts_in,  "patch-v.1.1.0 - rm accounts.in.id of transactions table");
			db_setup::doSQL($conn, $sql_rm_column_accounts_out, "patch-v.1.1.0 - rm accounts.out.id of transactions table");
			db_setup::doSQL($conn, $sql_rm_column_amount, "patch-v.1.1.0 - rm amount of transactions table");
		
			return 0;
		} 
		catch(PDOException $e) {
			echo "<br> error in patch 2023 04 06" . $e->getMessage();
			echo "<br> <pre>"; var_dump($e->errorInfo);
			exit;
		}
	}
}
?>
