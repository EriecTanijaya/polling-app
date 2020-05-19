<?php
session_start();
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

$stmt = $con->prepare('SELECT username, password, email, npm, prodi FROM accounts WHERE id = ?');

// pake id nya
$stmt->bind_param('i', $_SESSION['id']);
$stmt->execute();
$stmt->bind_result($username, $password, $email, $npm, $prodi);
$stmt->fetch();
$stmt->close();

$stmt = $con->prepare('SELECT id FROM polls WHERE creator_id = ?');
$stmt->bind_param('i', $_SESSION['id']);
$stmt->execute();
$stmt->store_result();
$poll_count = $stmt->num_rows;
?>

<?=template_header('Profile')?>

<div class="container-fluid content">
	<h2><i class="fa fa-user-circle fa-lg" style="margin-right: 10px;"></i>Profile Page</h2>
	<div>
		<p>Your account details are below:</p>
		<table class="table table-bordered">
			<tr>
				<td scope="col">Nama Lengkap:</td>
				<td><?=$_SESSION['name']?></td>
			</tr>
			<tr>
				<td scope="col">NPM:</td>
				<td><?=$npm?></td>
			</tr>
			<tr>
				<td scope="col">Prodi:</td>
				<td><?=$prodi?></td>
			</tr>
			<tr>
				<td scope="col">Username:</td>
				<td><?=$username?></td>
			</tr>
			<tr>
				<td scope="col">Email:</td>
				<td><?=$email?></td>
			</tr>
			<tr>
				<td scope="col">IP address:</td>
				<td><?=$_SESSION['ip']?></td>
			</tr>
			<tr>
				<td scope="col">Poll created:</td>
				<td><?=$poll_count?></td>
			</tr>
		</table>
	</div>
</div>

<?=template_footer()?>