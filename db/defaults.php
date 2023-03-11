<?php
class db_defaults {

	/* 
	 * Persi - fallimento
	 */
	public static function createDefaultAccounts($userID) {
		// creating default account Persi
		$db_defaultAccount_persi_par = array(
			'name'=>'persi - fallimento',
			'account_types.id'=>1,
			'users.id'=>$userID
		);

		if(db::add('accounts', $db_defaultAccount_persi_par) == 0) {
			alerts::echo_success();
		} else {
			die("in function db_defaults::defaultAccountPersi cannot create account");
		}


		
		// creating default account apparsi 
		$db_defaultAccount_trovati_par = array(
			'name'=>'trovati - apparsi',
			'account_types.id'=>2,
			'users.id'=>$userID
		);

		if(db::add('accounts', $db_defaultAccount_trovati_par) == 0) {
			alerts::echo_success();
		} else {
			die("in function db_defaults::defaultAccountPersi cannot create account");
		}



		// creating default account Stato
		$db_defaultAccount_Stato_par = array(
			'name'=>'Stato - governo',
			'account_types.id'=>3,
			'users.id'=>$userID
		);

		if(db::add('accounts', $db_defaultAccount_Stato_par) == 0) {
			alerts::echo_success();
		} else {
			die("in function db_defaults::defaultAccountPersi cannot create account");
		}
	}
}
?>			
