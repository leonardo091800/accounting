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


		try {
			echo "initiliasing database...";
			$conn->exec("CREATE DATABASE $db");

			return 0;
		} 
		catch(PDOException $e) {
			echo "<br> Cannot create DB: " . $e->getMessage();
			exit;
		}

	}



	/*
	 * Create Tables
	 */
	public static function createTables($conn) {
		try {
			echo "initiliasing tables...";
			$sql_users = "CREATE TABLE `accounting_db`.`users` (`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT , `name` VARCHAR(30) NOT NULL , `surname` VARCHAR(30) NOT NULL , `admin` BOOLEAN NOT NULL DEFAULT '0', `date_creation` DATE NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`)) ENGINE = InnoDB;";
			$sql_accountTypes = "CREATE TABLE `accounting_db`.`account_types` (`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT , `name` VARCHAR(30) NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;";
			$sql_accounts = "CREATE TABLE `accounting_db`.`accounts` (`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT , `account_types.id` INT(11) UNSIGNED , `users.id` INT(11) UNSIGNED , `name` VARCHAR(30) NOT NULL , `date_open` DATE , `date_close` DATE , PRIMARY KEY (`id`)) ENGINE = InnoDB;";
			$sql_accounts_users_constraint = "ALTER TABLE `accounting_db`.`accounts` ADD CONSTRAINT `accounts-users` FOREIGN KEY (`users.id`) REFERENCES `users`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;";
			$sql_accounts_accountTypes_constraint = "ALTER TABLE `accounting_db`.`accounts` ADD CONSTRAINT `accounts-account_types` FOREIGN KEY (`account_types.id`) REFERENCES `account_types`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;";

			$sql_transactionTypes = "CREATE TABLE `accounting_db`.`transaction_types` (`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT , `name` VARCHAR(30) NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;";
			$sql_transactions = "CREATE TABLE `accounting_db`.`transactions` (`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT , `transaction_types.id` INT(11) UNSIGNED , `accounts.in.id` INT(11) UNSIGNED NOT NULL , `accounts.out.id` INT(11) UNSIGNED NOT NULL , `timestamp` TIMESTAMP NOT NULL , `amount` DECIMAL(10,4) NOT NULL , `note` VARCHAR(50), PRIMARY KEY (`id`)) ENGINE = InnoDB;";
			$sql_transactions_transactionTypes_constraint = "ALTER TABLE `accounting_db`.`transactions` ADD CONSTRAINT `transactions-transaction_types` FOREIGN KEY (`transaction_types.id`) REFERENCES `transaction_types`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;";
			$sql_transactions_accounts_in_constraint = "ALTER TABLE `accounting_db`.`transactions` ADD CONSTRAINT `transactions-accounts.in` FOREIGN KEY (`accounts.in.id`) REFERENCES `accounts`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;";
			$sql_transactions_accounts_out_constraint = "ALTER TABLE `accounting_db`.`transactions` ADD CONSTRAINT `transactions-accounts.out` FOREIGN KEY (`accounts.out.id`) REFERENCES `accounts`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;";

			$conn->exec($sql_users);
			$conn->exec($sql_accountTypes);
			$conn->exec($sql_accounts);
			$conn->exec($sql_accounts_users_constraint);
			$conn->exec($sql_accounts_accountTypes_constraint);
			$conn->exec($sql_transactionTypes);
			$conn->exec($sql_transactions);
			$conn->exec($sql_transactions_transactionTypes_constraint);
			$conn->exec($sql_transactions_accounts_in_constraint);
			$conn->exec($sql_transactions_accounts_out_constraint);

			return 0;
		} 
		catch(PDOException $e) {
			echo "<br> in db_setup::createTables Cannot create tables: " . $e->getMessage();
			exit;
		}
	}




	/*
	 * Populate Tables
	 */
	public static function populateTables($conn) {
		// defining default accounts 
		$accounts_arr = array();

		return 0;
	}
}
?>
