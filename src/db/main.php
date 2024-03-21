<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/z.scripts/root.php';
require_once $root_DB_setup;

// for debugging:
require_once $root_Scripts_showErrors;
// 

class db {

	/* 
	 * Connect
	 */
	public static function connect() {
		global $root_DB_setup;

		$servername=getenv("DB_HOST");
		$username=getenv("DB_USR");
		$password=getenv("DB_PSW");

		if ( $servername === false ) {
#			alerts::echo_alert("variable DB_HOST not set! Using default 'localhost'");
			$servername="localhost";
		}
		if ( $username === false ) {
#			alerts::echo_alert("variable DB_USR not set! Using default 'accountingAdmin'");
			$username='accountingAdmin';
		}
		if ( $password === false ) {
#			alerts::echo_alert("variable DB_PSW not set! Using default ");
			$password='q0+vyBLb4AmGKusU';
		}

		$db="accounting_db";

		try {
			$conn = new PDO("mysql:host=$servername;dbname=$db", $username, $password);
			// set the PDO error mode to exception
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			return $conn;
		} 
		catch(PDOException $e) {
			// if connection failed because no correct user
			if($e->errorInfo[1] == 1698) {
				echo "you need to create the user for mysql: 
				<br> sudo mysql -u root -p
				<br> CREATE DATABASE '$db';
				<br> GRANT ALL PRIVILEGES ON $db.* TO $username@localhost IDENTIFIED BY '$password';
				<br> FLUSH PRIVILEGES;
				<br> ";
			}

			elseif($e->errorInfo[1] == 1045) {
				echo "Password is wrong, try to update the password: 
				<br> sudo mysql -u root -p
				<br> CREATE DATABASE '$db';
				<br> GRANT ALL PRIVILEGES ON $db.* TO $username@localhost IDENTIFIED BY '$password';
				<br> FLUSH PRIVILEGES;
				<br> ";
			}

			// if connection failed because no database name:
			elseif($e->errorInfo[1] == 1049) {
				require_once $root_DB_setup;
				db_setup::setup($servername, $username, $password, $db);
			}

#			// if connection failed because wrong username and psw:
#			if($e->errorInfo[1] == 1044) {
#			}

			// if error is neither of those specified above:
			else {
				echo "Connection failed, send this to the admin: <br> Message: ". $e->getMessage() . "<br> Code: " . $e->getCode() . "<br> Trace: " . var_dump($e->getTrace());
			}
		}
	}





	/* 
	 * Add
	 */
	// parameters must be an array of [key -> value]
	public static function add($table, $parameters) {
		if(empty($parameters)) {
			die("<br> in function db::add parameters need to be an array of [key->value] <br>");
		}
		if(empty($table) || $table=='') {
			die("<br> in function db::add table needs to be given <br>");
		}

		// SUPER UGLYYY, need to change this, usually is good to not have doublel things
		// but in reports is quite common so..
		if($table!='report_caption_tr_td_values') {
			$maybe = new db();
			if($maybe->checkIfExist($table, $parameters) == 1) {
				die("<br> id in $table already exist! <br>");
			}
		} 

		// if checks are good
		$conn = db::connect();

		// creating query with first parameter
		$firstKey=array_key_first($parameters);
		$firstParameter=$parameters[$firstKey];

		$sql_0 = "INSERT INTO $table (`$firstKey`";
		$sql_1 = " VALUES ('$firstParameter'";

		// rm first parameter from array
		unset($parameters[$firstKey]);
//		echo "<br> "; print_r($parameters);

		// printing other possible parameters with "comma" in between
		foreach($parameters as $key=>$value) {
			$sql_0 = $sql_0.",`$key`";
			$sql_1 = $sql_1.",'$value'";
		}

		// putting together the query
		$sql = $sql_0.")".$sql_1.");";

		try {
//			echo "<br> sql = $sql";
			$q=$conn->prepare($sql);
			$rows = $q->execute();
			return 0;
		} catch (Exception $e) {
//			echo "<br> <pre>";  var_dump($e->errorInfo);
			if($e->errorInfo[2] == "Data too long for column 'note' at row 1") {
				alerts::echo_alert('note is too long!');
			}
			if($e->errorInfo[2] == "Data too long for column 'name' at row 1") {
				alerts::echo_alert('name is too long!');
			}
			return $e;
//			die('error in db::add error: '.$e);
		}

	}







	/* 
	 * Rm
	 */
	public static function rm($table, $id) {
		if(empty($id) || $id=='') {
			die("<br> in function db::rm id needs to be given <br>");
		}
		if(empty($table) || $table=='') {
			die("<br> in function db::rm table needs to be given <br>");
		}

		$parameters = array('id'=>$id);

		if(db::checkIfExist($table, $parameters) == 0) {
			die("<br> the id in $table does NOT exist! <br>");
		}

		// if checks are good
		$conn = db::connect();

		try {
			$sql = "DELETE FROM $table WHERE id='$id'";
			$q=$conn->prepare($sql);
			$rows = $q->execute();
		} catch (Exception $e) {
//			echo "<br> <br>"; print_r($e);
//			echo "<br> <br>"; var_dump($e->errorInfo);
			if($e->errorInfo[1] == 1451) {
				alerts::echo_alert('you need to remove everything this container has before removing');
				return 1;
			} else {
				die(" in db::rm server error, report this to the admin: $e");
			}
		}

		return 0;
	}


	/* 
	 * Alter
	 */
	public static function update($table, $id, $column, $newValue) {
		if(empty($id) || $id=='') {
			die("<br> in function db::alter id needs to be given <br>");
		}
		if(empty($table) || $table=='') {
			die("<br> in function db::alter table needs to be given <br>");
		}
		if(empty($column) || $column=='') {
			die("<br> in function db::alter column needs to be given <br>");
		}
		if(empty($newValue) || $newValue==='') {
			die("<br> in function db::alter newValue needs to be given <br> newValue = $newValue");
		}

		$parameters = array('id'=>$id);
		if(db::checkIfExist($table, $parameters) == 0) {
			die("<br> the id in $table does NOT exist! <br>");
		}

		// if checks are good
		$conn = db::connect();

		try {
			$sql = "UPDATE $table SET `$column` = '$newValue' WHERE `id`='$id';";
			$q=$conn->prepare($sql);
			$rows = $q->execute();
		} catch (Exception $e) {
//			echo "<br> <br>"; print_r($e);
//			echo "<br> <br>"; var_dump($e->errorInfo);
			if($e->errorInfo[2] == "Data too long for column 'name' at row 1") {
				alerts::echo_alert('name is too long!');
			}
			return $e;
//			die(" in db::alter server error, report this to the admin: $e");
		}

		return 0;
	}






	/* 
	 * getID
	 */
	public static function getID($table, $parameters) {
		$rows = db::get($table, $parameters);
		if(isset($rows['error'])) {
			echo "<br> error in db::getID <br>";
			echo "<br> in db::getID rows = <br>"; print_r($rows);
			echo "<br> in db::getID table = $table, parameters = <br>"; print_r($parameters);
		} else {
			// returning ID as integer
			return intval($rows[0]['id']);
		}
	}



	/* 
	 * get from Table given parameters 
	 */
	public static function get($table, $parameters) {
		if(empty($parameters)) {
			die("<br> in function db::get parameters need to be an array of [key->value] <br>");
		}
		if(empty($table) || $table=='') {
			die("<br> in function db::get table needs to be given <br>");
		}

		$maybe = new db();
		if($maybe->checkIfExist($table, $parameters) == 0) {
			return(array('error' => 1, 'type' => 'isset'));
//			die("<br> id in $table does NOT exist! <br>");
		}

		// if checks are good
		$conn = db::connect();

		// creating query with first parameter
		$firstKey=array_key_first($parameters);
		$firstParameter=$parameters[$firstKey];

		$sql = "SELECT * FROM $table WHERE `$firstKey` = '$firstParameter'";

		// rm first parameter from array
		unset($parameters[$firstKey]);

		// uncomment for testing
		// echo "<br> "; print_r($parameters);

		// printing other possible parameters with "comma" in between
		foreach($parameters as $key=>$value) {
			$sql = $sql." AND `$key`='$value'";
		}

		try {
			$q=$conn->prepare($sql);
			$rows = $q->execute();
			$rows = $q->fetchAll(PDO::FETCH_ASSOC);
		} catch (Exception $e) {
			die(" in db::get server error, report this to the admin: $e");
		}

//		echo "in db::selectID id = "; print_r($rows);

		return $rows;

	}





	/* 
	 * Check If Exist
	 */
	// parameters must be an array of [key -> value]
	public static function checkIfExist($table, $parameters) {
		global $root_DB_setup;

		$global_tables = array('users', 'accounts', 'account_x_group', 'accounts_groups', 'transactions', 'transaction_accounts_involved',
		'reports', 'report_captions', 'report_caption_trs', 'report_caption_tr_tds', 'report_caption_tr_td_values');


		if(empty($parameters)) {
			echo "<br> in function db::checkIfExist parameters need to be an array of [key->value] <br>";
			exit;
		}
		if(empty($table) || $table=='') {
			echo "<br> in function db::checkIfExist table needs to be given <br>";
			exit;
		}

		if(!in_array($table, $global_tables)) {
			echo "<br> in function db::checkIfExist table is not recognized <br>";
			exit;
		}

		// if checks are ok...
		$conn = db::connect();

		// creating query with first parameter
		$firstKey=array_key_first($parameters);
		$firstParameter=$parameters[$firstKey];
		$sql = "SELECT id FROM $table WHERE `$firstKey`='$firstParameter'";

		// rm first parameter from array
		unset($parameters[$firstKey]);

		// printing other possible parameters with "AND" in between
		foreach($parameters as $key=>$value) {
			$sql = $sql." AND `$key`='$value'";
		}
		try {
//			echo "<br> checkIfExist SQL = $sql <br>";
			$q=$conn->prepare($sql);
			$rows = $q->execute();
			$rows = $q->fetchAll(PDO::FETCH_ASSOC);
		} catch (Exception $e) {
			echo "<br> <br>"; print_r($e->errorInfo);

			// patch 20230604: need to create mail and psw
			// array(3) { [0]=> string(5) "42S22" [1]=> int(1054) [2]=> string(39) "Unknown column 'mail' in 'where clause'" }
			if($e->errorInfo[1] == '1054' && $e->errorInfo[2] == "Unknown column 'mail' in 'where clause'") {
				echo "<br> need to add mail and psw in users table, starting the patch... <br>";
				echo "<br> but first I need to change the add accounts to include the user ID";
				db_setup::patch20230406($conn);
				redirect::rediretTo($pages);
				exit;
			}
		}

		if(empty($rows)) {
//			echo "<br> array empty <BR>";
			return 0;
		} else {
//			echo "<br> array NOT empty <BR>";
			return 1;
		}
	}




	/*
	 * getSum (of account)
	 * it will search all transactions of that account and add them together
	 */
	public static function getSum($accID, $date) {
		if(empty($accID)) {
			echo "<br> in function db::getSum accID is empty <br>";
			exit;
		}
		if(empty($date)) {
			echo "<br> in function db::getSum date is empty <br>";
			exit;
		}

		// if checks are ok...
		$conn = db::connect();

		$sqlEntrate = "SELECT amount FROM transaction_accounts_involved WHERE `accounts.id`='$accID' AND `exit0orenter1`='1' AND `transactions.id` IN (SELECT id FROM transactions WHERE DATE(timestamp) <= '$date')";
		$sqlUscite = "SELECT amount FROM transaction_accounts_involved WHERE `accounts.id`='$accID' AND `exit0orenter1`='0' AND `transactions.id` IN (SELECT id FROM transactions WHERE DATE(timestamp) <= '$date')";

		try {
			$q=$conn->prepare($sqlEntrate);
			$entrate = $q->execute();
			$entrate = $q->fetchAll(PDO::FETCH_ASSOC);
		} catch (Exception $e) {
			die(" in db::getSum server error in entrate, report this to the admin: $e");
		}
		try {
			$q=$conn->prepare($sqlUscite);
			$uscite= $q->execute();
			$uscite= $q->fetchAll(PDO::FETCH_ASSOC);
		} catch (Exception $e) {
			die(" in db::getSum server error in uscite, report this to the admin: $e");
		}

		/*
		echo "<br><br> entrate:"; print_r($entrate);
		echo "<br><br> uscite:"; print_r($uscite);
		 */

		$sum = 0.0000;
		foreach($entrate as $e) {
			$sum += (float)$e['amount'];
		}
		foreach($uscite as $u) {
			$sum -= (float)$u['amount'];
		}
		return $sum;

			/*
		if(empty($rows)) {
//			echo "<br> array empty <BR>";
			return 0;
		} else {
//			echo "<br> array NOT empty <BR>";
			return 1;
		}
			 */
	}


}
?>
