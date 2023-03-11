<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class db {

	/* 
	 * Connect
	 */
	public static function connect() {
		$servername = "localhost";
		$username = "accountingAdmin";
		$password = "mT_13TKv\"$^^;iG|F9pYS{n4#";
		$db = "accounting_db";

		try {
			$conn = new PDO("mysql:host=$servername;dbname=$db", $username, $password);
			// set the PDO error mode to exception
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			return $conn;
		} 
		catch(PDOException $e) {
			echo "Connection failed: " . $e->getMessage();
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

		$maybe = new db();
		if($maybe->checkIfExist($table, $parameters) == 1) {
			die("<br> id in $table already exist! <br>");
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
		echo "<br> "; print_r($parameters);

		// printing other possible parameters with "comma" in between
		foreach($parameters as $key=>$value) {
			$sql_0 = $sql_0.",`$key`";
			$sql_1 = $sql_1.",'$value'";
		}

		// putting together the query
		$sql = $sql_0.")".$sql_1.");";

		$q=$conn->prepare($sql);
		$rows = $q->execute();

		return 0;
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
	 * getID
	 */
	public static function getID($table, $parameters) {
		if(empty($parameters)) {
			die("<br> in function db::add parameters need to be an array of [key->value] <br>");
		}
		if(empty($table) || $table=='') {
			die("<br> in function db::add table needs to be given <br>");
		}

		$maybe = new db();
		if($maybe->checkIfExist($table, $parameters) == 0) {
			die("<br> id in $table does NOT exist! <br>");
		}

		// if checks are good
		$conn = db::connect();

		// creating query with first parameter
		$firstKey=array_key_first($parameters);
		$firstParameter=$parameters[$firstKey];

		$sql = "SELECT `id` FROM $table WHERE `$firstKey` = '$firstParameter'";

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
			die(" in db::getID server error, report this to the admin: $e");
		}

		echo "in db::selectID id = "; print_r($rows);
		// returning ID as integer
		return intval($rows[0]['id']);
	}





	/* 
	 * Check If Exist
	 */
	// parameters must be an array of [key -> value]
	public static function checkIfExist($table, $parameters) {
		$global_tables = array('users', 'accounts', 'account_types');


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
		$q=$conn->prepare($sql);
		$rows = $q->execute();
		$rows = $q->fetchAll(PDO::FETCH_ASSOC);
		print_r($rows);

		if(empty($rows)) {
//			echo "<br> array empty <BR>";
			return 0;
		} else {
//			echo "<br> array NOT empty <BR>";
			return 1;
		}
	}
}
?>
