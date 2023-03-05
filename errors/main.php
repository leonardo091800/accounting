<?php
class errors {
	public static function echo_error($error_type, $error_value) {
		switch ($error_type) {
		case 'tableNotExist':
			echo "<br> the table $error_value does not exist, r u an hacker? <br>";
			break;
		case 'userAlreadyExist':
			echo "<br> the user $error_value does already exist, need glasses? <br>";
			break;

		case 'fieldNotGiven':
			echo "<br> $error_value not given !!<br>";
			break;
		case 'fieldNotExist':
			echo "<br> $error_value does not exist :)<br>";
			break;
		default:
			echo "<br> wrong parameter in functino db::echo_error <br>";
		}
	}
}

class alerts {
	public static function echo_success() {
		echo "<script> alert('success'); </script>";
	}
}

class redirect {
	public static function to_page($page) {
		echo "<script> window.location.href = '$page' </script>";
	}
}
?>
