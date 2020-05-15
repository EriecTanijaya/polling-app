<?php
// We need to use sessions, so you should always start sessions using the below code.
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
    header('Location: index.html');
    exit;
}

include 'functions.php';
$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = '';
$DATABASE_NAME = 'phppoll';
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if (mysqli_connect_errno()) {
    exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}
// We don't have the password or email info stored in sessions so instead we can get the results from the database.
$stmt = $con->prepare('SELECT password, email, npm, prodi FROM accounts WHERE id = ?');
// In this case we can use the account ID to get the account info.
$stmt->bind_param('i', $_SESSION['id']);
$stmt->execute();
$stmt->bind_result($password, $email, $npm, $prodi);
$stmt->fetch();
$stmt->close();
?>

<?=template_header('Polls')?>

<div class="content">
	<h2>Profile Page</h2>
	<div>
		<p>Your account details are below:</p>
		<table>
			<tr>
				<td>NPM:</td>
				<td><?=$npm?></td>
			</tr>
			<tr>
				<td>Prodi:</td>
				<td><?=$prodi?></td>
			</tr>
			<tr>
				<td>Username:</td>
				<td><?=$_SESSION['name']?></td>
			</tr>
			<tr>
				<td>Password:</td>
				<td><?=$password?></td>
			</tr>
			<tr>
				<td>Email:</td>
				<td><?=$email?></td>
			</tr>
			<tr>
				<td>IP address:</td>
				<td><?=$_SESSION['ip']?></td>
			</tr>
		</table>
	</div>
</div>

<?=template_footer()?>