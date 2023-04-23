<?php
/*
 * 2023-04-23: accounts can be divided into groups (expenses, revenues, etc)
 */
function patchv112($conn) {
	try {
		// create table accounts_groups and account_x_group
$sql_accountsGroups = "CREATE TABLE `accounting_db`.`accounts_groups` (`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT , `users.id` INT(11) UNSIGNED , `name` VARCHAR(50) NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;";
$sql_accountsGroups_users_constraint = "ALTER TABLE `accounting_db`.`accounts_groups` ADD CONSTRAINT `accounts_groups-users` FOREIGN KEY (`users.id`) REFERENCES `users`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;";
$sql_accountXGroup = "CREATE TABLE `accounting_db`.`account_x_group` (`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT , `accounts.id` INT(11) UNSIGNED , `accounts_groups.id` INT(11) UNSIGNED ,  PRIMARY KEY (`id`)) ENGINE = InnoDB;";
$sql_accountXGroup_accounts_constraint = "ALTER TABLE `accounting_db`.`account_x_group` ADD CONSTRAINT `account_x_group-accounts` FOREIGN KEY (`accounts.id`) REFERENCES `accounts`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;";
$sql_accountXGroup_accountsGroups_constraint = "ALTER TABLE `accounting_db`.`account_x_group` ADD CONSTRAINT `account_x_group-accounts_groups` FOREIGN KEY (`accounts_groups.id`) REFERENCES `accounts_groups`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;";
		db_setup::doSQL($conn, $sql_accountsGroups, "patch-v.1.1.2 - adding accounts_groups");
		db_setup::doSQL($conn, $sql_accountsGroups_users_constraint, "patch-v.1.1.2 - adding constraint from accounts_groups to users");
		db_setup::doSQL($conn, $sql_accountXGroup, "patch-v.1.1.2 - adding account_x_group");
		db_setup::doSQL($conn, $sql_accountXGroup_accounts_constraint, "patch-v.1.1.2 - added constraint from account_x_group to accounts");
		db_setup::doSQL($conn, $sql_accountXGroup_accountsGroups_constraint, "patch-v.1.1.2 - added constraint from account_x_group to accounts_groups");

		// remove constraints and columns in accounts 
		$sql_rm_constraint_accountTypes = "ALTER TABLE `accounting_db`.`accounts` DROP CONSTRAINT `accounts-account_types`";
		$sql_drop_column_accountTypes = "ALTER TABLE `accounting_db`.`accounts` DROP COLUMN `account_types.id`;";
		db_setup::doSQL($conn, $sql_rm_constraint_accountTypes,  "patch-v.1.1.2 - rm constraint to account_types");
		db_setup::doSQL($conn, $sql_drop_column_accountTypes, "patch-v.1.1.2 - dropped column account_types.id");
	
		// let's also increase size for notes and names
		$sql_increase_note_size = "ALTER TABLE `accounting_db`.`transactions` MODIFY COLUMN `note` VARCHAR(100);";
		$sql_increase_name_size = "ALTER TABLE `accounting_db`.`accounts` MODIFY COLUMN `name` VARCHAR(50) NOT NULL;";
		db_setup::doSQL($conn, $sql_increase_note_size, "patch-v.1.1.2 - increase column transactions.note to VARCHAR(100)");
		db_setup::doSQL($conn, $sql_increase_name_size, "patch-v.1.1.2 - increase column accounts.name to VARCHAR(50)");

		// let's also create a table called technicals to keep track of versions
		$sql_technicals = "CREATE TABLE `accounting_db`.`technicals` (`accounting_version` VARCHAR(50)) ENGINE = InnoDB;";
		$sql_technicals_v111 = "INSERT INTO `accounting_db`.`technicals` (`accounting_version`) VALUES ('1.1.1') ;";
		db_setup::doSQL($conn, $sql_technicals, "patch-v.1.1.2 - created table technicals");
		db_setup::doSQL($conn, $sql_technicals_v111, "patch-v.1.1.2 - patch is not finished yet, so value is still 1.1.1");

		return 0;
	} 
	catch(PDOException $e) {
		echo "<br> error in patch v.1.1.2" . $e->getMessage();
		echo "<br> <pre>"; var_dump($e->errorInfo);
		exit;
	}
}
?>
