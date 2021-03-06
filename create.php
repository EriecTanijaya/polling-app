<?php

session_start();

if (!isset($_SESSION['loggedin'])) {
    header('Location: login.php');
    exit;
}

include 'functions.php';
$pdo = pdo_connect_mysql();
$msg = '';

if (isset($_SESSION['msg'])) {
    $msg = $_SESSION['msg'];
    $_SESSION['msg'] = '';
}

if (!empty($_POST)) {
    $title = isset($_POST['title']) ? $_POST['title'] : '';
    $desc = isset($_POST['desc']) ? $_POST['desc'] : '';

    if ($_SESSION['id'] != $_POST['creator_id']){
        $_SESSION['msg'] = 'Invalid ID!';
        header('Location: create.php');
        exit;
    }

    // Get the answers and convert the multiline string to an array, so we can add each answer to the "poll_answers" table
    $answers = isset($_POST['answers']) ? explode(PHP_EOL, $_POST['answers']) : '';

    $filled_answer = 0;
    foreach ($answers as $answer) {
        if (strlen(trim($answer)) > 0) {
            $filled_answer++;
        }
    }

    if ($filled_answer < 2) {
        $_SESSION['msg'] = 'Masukkan setidaknya 2 pilihan jawaban';
        header('Location: create.php');
        exit;
    }

    if ($filled_answer > 5) {
        $_SESSION['msg'] = 'Max pilihan berganda adalah 5 pilihan';
        header('Location: create.php');
        exit;
    }

    $stmt = $pdo->prepare('INSERT INTO polls VALUES (NULL, ?, ?, ?)');
    $stmt->execute([$title, $desc, $_POST['creator_id']]);
    
    $poll_id = $pdo->lastInsertId();
    
    foreach ($answers as $answer) {
        // If the answer is empty there is no need to insert
        if (strlen(trim($answer)) <= 0 || empty($answer)) {
            continue;
        }

        $stmt = $pdo->prepare('INSERT INTO poll_answers VALUES (NULL, ?, ?, 0)');
        $stmt->execute([$poll_id, $answer]);
    }
    
    $msg = 'Created Successfully!';
}
?>

<?=template_header('Create Poll')?>
    
<div class="container content">
    <?php if ($msg): ?>
        <p><?=$msg?></p>
    <?php endif;?>

	<h2>Create Poll</h2>
    <form action="create.php" method="post">
        <input type="hidden" name="creator_id" id="creator_id" value="<?=$_SESSION['id']?>">
        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" class="form-control" name="title" id="title" required>
        </div>
        <div class="form-group">
            <label for="desc">Description</label>
            <input type="text" class="form-control" name="desc" id="desc">
        </div>
        <div class="form-group">
            <label for="answers">Answers (per line), Max 5 answers</label>
            <textarea name="answers" class="form-control" id="answers" required></textarea>
        </div>
        <input type="submit" class="btn btn-success" value="Create">
    </form>
</div>

<?=template_footer()?>
