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

<div class="container-fluid content home">
    <?php if ($msg): ?>
        <div class="notif-warning"><p><i class="fas fa-exclamation-circle"></i> <?=$msg?></p></div>
    <?php endif;?>
	<h2><i class="fa fa-poll-h fa-lg" style="margin-right: 10px;"></i>Polls</h2>
    <p>Hi <?=$_SESSION['name']?>, you can view the list of polls below. You also can create your own poll!</p>
    <a href="create.php" class="create-poll">Create Poll</a>
	<table class="table table-bordered">
        <thead class="thead-dark">
            <tr>
                <td scope="col">#</td>
                <td scope="col">Title</td>
                <td scope="col">Answers</td>
                <td scope="col">Creator</td>
                <td scope="col">Action</td>
            </tr>
        </thead>
        <tbody>
            <?php $num = 1; ?>
            <?php foreach ($polls as $poll): ?>
            <?php 
            $stmt = $pdo->query('SELECT username FROM accounts WHERE id = ' . $poll['creator_id']);
            $creator = $stmt->fetch(PDO::FETCH_ASSOC);
            $creator_name = $creator['username'];
            ?>
            <tr>
                <th scope="row"><?=$num?></td>
                <td style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap; max-width: 600px"><?=$poll['title']?></td>
                <td><?=$poll['answers']?></td>
                <td><?=$creator_name?></td>
                <td class="actions">
                    <a href="vote.php?id=<?=$poll['id']?>" class="view" title="View Poll"><i class="fas fa-eye fa-xs"></i></a>
                    <?php
                    if ($_SESSION['id'] == $poll['creator_id']) {
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