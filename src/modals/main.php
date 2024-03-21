<?php
class modals {
	// what = remove/add, $data = info on the data given
	public static function echo_confirmation($what, $data) {
		switch ($data) {
		case 'rm':
			echo "<button onclick=''> REMOVE </button>";
			$id = $data['id'];
			require 'confirmationBeforeRemove.php';
			break;
		default:
			echo "<br> smt wrong with modals::echo_confirmation() no parameters <br>";
			exit;
		}
	}

	public static function confirmationBeforeRemove($id) {
		echo "
<style>
/*
body {font-family: Arial, Helvetica, sans-serif;}
*/

* {box-sizing: border-box;}

/* Set a style for all buttons */
button {
/*	background-color: #04AA6D;
	color: white;
	padding: 14px 20px;
	margin: 8px 0;
	border: none;
	cursor: pointer;
	width: 100%;
	opacity: 0.9;
*/
}

button:hover {
	opacity:1;
}

/* Float cancel and delete buttons and add an equal width */
.cancelbtn, .deletebtn { 
	float: left;
	width: 50%;
}


/* Add a color to the delete button */
.deletebtn {
	  background-color: #f44336;
}

/* Add padding and and center-align text to the container */
.container {
  padding: 16px;
  text-align: center;
}

/* The Modal (background) */
.modal {
	display: none; /* Hidden by default */
	position: fixed; /* Stay in place */
	z-index: 1; /* Sit on top */
	left: 0;
	top: 0;
	width: 100%; /* Full width */
	height: 100%; /* Full height */
	overflow: auto; /* Enable scroll if needed */
	background-color: #474e5d;
	padding-top: 50px;
}

/* Modal Content/Box */
.modal-content {
	background-color: #fefefe;
	margin: 5% auto 15% auto; /* 5% from the top, 15% from the bottom and centered */
	border: 1px -bottom: 25px;
	width: 80%; /* Could be more or less, depending on screen size */
}
 
/* The Modal Close Button (x) */
.close {
	position: absolute;
	right: 35px;
	top: 15px;
	font-size: 40px;
	font-weight: bold;
	color: #f1f1f1;
}

.close:hover, .close:focus {
	color: #f44336;
	cursor: pointer;
}

/* Clear floats */
.clearfix::after {
	content: "";
	clear: both;
	display: table;
}

/* Change styles for cancel button and delete button on extra small screens */
@media screen and (max-width: 300px) {
	.cancelbtn, .deletebtn {
		width: 100%;
	}
}
</style>


<div id="modal$id" class="modal">
	<span onclick="document.getElementById('modal$id').style.display='none'" class="close" title="Close Modal">×</span>
	<form class="modal-content" action="/action_page.php">
	<div class="container">
		<h1>Delete Account</h1>
		<p>Are you sure you want to delete your account?</p>
						        
		<div class="clearfix">
			<button type="button" onclick="document.getElementById('modal$id').style.display='none'" class="cancelbtn">Cancel</button>
			<button type="button" onclick="document.getElementById('modal$id').style.display='none'" class="deletebtn">Delete</button>
		</div>
	</div>
	</form>
</div>

<script>
	// Get the modal
	var modal = document.getElementById('modal$id');

	// When the user clicks anywhere outside of the modal, close it
	window.onclick = function(event) {
		if (event.target == modal) {
			modal.style.display = "none";
		}
	}
</script>

";
	}
}
