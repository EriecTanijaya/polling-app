<?php
include 'functions.php';
session_start();

if (!isset($_SESSION['loggedin'])) {
    header('Location: login.php');
    exit;
}

if (!$_SESSION['su']) {
    $_SESSION['msg'] = 'Sorry, hanya admin yang bisa akses page ini';
    header('Location: index.php');
    exit;
}

$msg = "";
if (isset($_SESSION['msg'])) {
    $msg = $_SESSION['msg'];
    $_SESSION['msg'] = "";
}

$pdo = pdo_connect_mysql();

if (!isset($_GET['id'])) {
    exit('No ID specified!');
}

$stmt = $pdo->prepare('SELECT * FROM accounts WHERE id = ?');
$stmt->execute([$_GET['id']]);
$user = $stmt->fetch();

$get_polls = $pdo->prepare('SELECT * FROM polls WHERE creator_id = ?');
$get_polls->execute([$_GET['id']]);
$polls = $get_polls->fetchAll(PDO::FETCH_ASSOC);

?>

<?=template_header('View')?>

<div class="container-fluid content home">
    <h2><i class="fa fa-file-alt fa-lg" style="margin-right: 10px"></i>Report</h2>
    <div>
		<p>Account details are below:</p>
		<table class="table table-bordered">
			<tr>
				<td scope="col">Nama Lengkap:</td>
				<td><?=$user['name']?></td>
			</tr>
			<tr>
				<td scope="col">NPM:</td>
				<td><?=$user['npm']?></td>
			</tr>
			<tr>
				<td scope="col">Prodi:</td>
				<td><?=$user['prodi']?></td>
			</tr>
			<tr>
				<td scope="col">Username:</td>
				<td><?=$user['username']?></td>
			</tr>
			<tr>
				<td scope="col">Email:</td>
				<td><?=$user['email']?></td>
			</tr>
			<tr>
				<td scope="col">IP address:</td>
				<td><?=$user['ip']?></td>
			</tr>
		</table>
    </div>

    <div>
        <table class="table table-bordered">
            <thead class="thead-dark">
                <tr>
                    <td scope="col">#</td>
                    <td scope="col">Title</td>
                    <td scope="col">Description</td>
                    <td scope="col">Action</td>
                </tr>
            </thead>
            <tbody>
                <?php $num = 1; ?>
                <?php foreach ($polls as $poll): ?>
                <?php
                if ($poll['desc'] == '') {
                    $poll['desc'] = 'No description';
                }
                ?>
                <tr>
                    <th scope="row"><?=$num?></td>
                    <td><?=$poll['title']?></td>
                    <td><?=$poll['desc']?></td>
                    <td class="actions">
                        <a href="vote.php?id=<?=$poll['id']?>" class="view" title="View Poll"><i class="fas fa-eye fa-xs"></i></a>
                        <?php
                        if ($_SESSION['id'] == $poll['creator_id'] || $_SESSION['su']) {
                            echo '<a href="delete.php?id=' . $poll['id'] . '" class="trash" title="Delete Poll"><i class="fas fa-trash fa-xs"></i></a>';
                        }
                        ?>
                    </td>
                </tr>
                <?php $num++; ?>
                <?php endforeach;?>
            </tbody>
        </table>
    </div>
</div>

<?=template_footer()?>