<?php

session_start();

if (!isset($_SESSION['loggedin'])) {
    header('Location: login.php');
    exit;
}

$msg = "";
if (isset($_SESSION['msg'])) {
    $msg = $_SESSION['msg'];
    $_SESSION['msg'] = "";
}

include 'functions.php';

$pdo = pdo_connect_mysql();

$stmt = $pdo->query('SELECT p.*, GROUP_CONCAT(pa.title ORDER BY pa.id) AS answers FROM polls p LEFT JOIN poll_answers pa ON pa.poll_id = p.id GROUP BY p.id');
$polls = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?=template_header('Polls')?>

<div class="content home">
    <?php if ($msg): ?>
        <div class="notif-warning"><p><i class="fas fa-exclamation-circle"></i> <?=$msg?></p></div>
    <?php endif;?>
	<h2>Polls</h2>
	<p>Hi <?=$_SESSION['name']?>, you can view the list of polls below.</p>
    <?php
    if (isset($_SESSION['su'])) {
        echo '<a href="create.php" class="create-poll">Create Poll</a>';
    }
    ?>
	<table>
        <thead>
            <tr>
                <td>#</td>
                <td>Title</td>
				<td>Answers</td>
                <td></td>
            </tr>
        </thead>
        <tbody>
            <?php $num = 1; ?>
            <?php foreach ($polls as $poll): ?>
            <tr>
                <td><?=$num?></td>
                <td><?=$poll['title']?></td>
				<td><?=$poll['answers']?></td>
                <td class="actions">
				    <a href="vote.php?id=<?=$poll['id']?>" class="view" title="View Poll"><i class="fas fa-eye fa-xs"></i></a>
                    <?php
                    if (isset($_SESSION['su'])) {
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

<?=template_footer()?>