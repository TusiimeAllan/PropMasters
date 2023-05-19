<?php
session_start();

include_once("config/connection.php");

if (isset($_POST["register"])) {
	if (isset($_POST["username"]) && isset($_POST["password"])) {
		$name = $_POST["username"];
		$pass = $_POST["password"];
		$contact = $_POST["contact"];
		$role = $_POST["account-type"];

		// Checking to see if the username hasn't been taken already
		$name_query = "SELECT * FROM users where username='$name'";
		$result = mysqli_query($conn, $name_query);
		$num = mysqli_num_rows($result);

		if ($num == 1) {
			$_SESSION['message'] = "This username is already taken, try using a different one";
			$_SESSION['color'] = "red";
			header('location:index.php');
		} else {
			// Encrypting the password
			$hashedPassword = password_hash($pass, PASSWORD_DEFAULT);

			$ins_query = "INSERT INTO users(username, password, contact, role) VALUES ('$name','$hashedPassword', $contact, '$role')";
			mysqli_query($conn, $ins_query);

			$_SESSION['message'] = "Registration Successful, Please Login";
			$_SESSION['color'] = "green";
			header('location:index.php');
		}
	} else {
		$_SESSION['message'] = "Please fill in the required fields";
		$_SESSION['color'] = "red";
		header('location:index.php');
	}
} else if (isset($_POST["login"])) {
	if (isset($_POST["username"]) && isset($_POST["password"])) {
		$name = $_POST["username"];
		$pass = $_POST["password"];

		$q = "SELECT * FROM users where username='$name'";

		$result = mysqli_query($conn, $q);

		$num = mysqli_num_rows($result);

		if ($num == 1) {
			$user = mysqli_fetch_assoc($result);
			$hashedPassword = $user['password'];

			// Verify the password
			if (password_verify($pass, $hashedPassword)) {
				if ($user['role'] == 'user') {
					$_SESSION['logged_in'] = true;
					$_SESSION['user_data'] = $user;

					$_SESSION['message'] = "Welcome, " . $user['username'];
					$_SESSION['color'] = "green";
					header('location:./../tenant/');
				} else if ($user['role'] == 'landlord') {
					$_SESSION['logged_in'] = true;
					$_SESSION['user_data'] = $user;

					$_SESSION['message'] = "Welcome, " . $user['username'];
					$_SESSION['color'] = "green";
					header('location:./../landlord/');
				} else if ($user['role'] == 'admin') {
					$_SESSION['logged_in'] = true;
					$_SESSION['user_data'] = $user;

					$_SESSION['message'] = "Welcome, " . $user['username'];
					$_SESSION['color'] = "green";
					header('location:./../admin/');
				}
			} else {
				$_SESSION['logged_in'] = false;
				$_SESSION['message'] = "Incorrect Password";
				$_SESSION['color'] = "red";
				header('location:index.php');
			}
		} else {
			$_SESSION['logged_in'] = false;
			$_SESSION['message'] = "User Doesn't Exist";
			$_SESSION['color'] = "red";
			header('location:index.php');
		}
	} else {
		$_SESSION['logged_in'] = false;
		$_SESSION['message'] = "Please fill in your credentials";
		$_SESSION['color'] = "red";
		header('location:index.php');
	}
} else {
	header('location:index.php');
}
