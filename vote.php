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
// Connect to MySQL
$pdo = pdo_connect_mysql();
// If the GET request "id" exists (poll id)...
if (isset($_GET['id'])) {
    // MySQL query that selects the poll records by the GET request "id"
    $stmt = $pdo->prepare('SELECT * FROM polls WHERE id = ?');
    $stmt->execute([$_GET['id']]);
    // Fetch the record
    $poll = $stmt->fetch(PDO::FETCH_ASSOC);
    // Check if the poll record exists with the id specified
    if ($poll) {
        // MySQL query that selects all the poll answers
        $stmt = $pdo->prepare('SELECT * FROM poll_answers WHERE poll_id = ?');
        $stmt->execute([$_GET['id']]);

        // Fetch all the poll anwsers
        $poll_answers = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // check if user already vote
        $stmt = $pdo->prepare('SELECT account_id FROM poll_commit WHERE poll_id = ?');
        $stmt->execute([$_GET['id']]);
        $exists_voter = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        for ($i = 0; $i < count($exists_voter); $i++) {
            if ($exists_voter[$i]["account_id"] == $_SESSION["id"]) {
                header('Location: result.php?id=' . $poll['id']);
                exit;
            }
        }

        // If the user clicked the "Vote" button...
        if (isset($_POST['poll_answer'])) {
            // check if user already vote
            $stmt = $pdo->prepare('SELECT account_id FROM poll_commit WHERE poll_id = ?');
            $stmt->execute([$_GET['id']]);
            $exists_voter = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            for ($i = 0; $i < count($exists_voter); $i++) {
                if ($exists_voter[$i]["account_id"] == $_SESSION["id"]) {
                    $_SESSION['msg'] = 'Kamu sudah vote pada polling "'. $poll['title'] .'"';
                    header('Location: index.php');
                    exit;
                }
            }

            // Update and increase the vote for the answer the user voted for
            $stmt = $pdo->prepare('UPDATE poll_answers SET votes = votes + 1 WHERE id = ?');
            $stmt->execute([$_POST['poll_answer']]);

            // Update poll_commit table
            if ($stmt = $pdo->prepare('INSERT INTO poll_commit (account_id, poll_id) VALUES (?, ?)')) {

                $stmt->execute([$_SESSION['id'], $_GET['id']]);

                // Redirect user to the result page
                header('Location: result.php?id=' . $_GET['id']);
                exit;
            } else {
                echo 'Could not prepare statement!';
            }
        }
    } else {
        die('Poll with that ID does not exist.');
    }
} else {
    die('No poll ID specified.');
}
?>

<?=template_header('Poll Vote')?>

<div class="container-fluid content poll-vote">
    <h2><?=$poll['title']?></h2>
    <?php
        if ($poll['desc'] != "") {
            echo '<p>' . $poll['desc'] . '</p>';
        }
    ?>
    <?php
    $stmt = $pdo->query('SELECT username FROM accounts WHERE id = ' . $poll['creator_id']);
    $creator = $stmt->fetch(PDO::FETCH_ASSOC);
    $creator_name = $creator['username'];
    ?>
    <p>Poll created by <?=$creator_name?></p>
    <form action="vote.php?id=<?=$_GET['id']?>" method="post">
        <?php for ($i = 0; $i < count($poll_answers); $i++): ?>
        <label>
            <input type="radio" name="poll_answer" value="<?=$poll_answers[$i]['id']?>"<?=$i == 0 ? ' checked' : ''?>>
            <?=$poll_answers[$i]['title']?>
        </label>
        <?php endfor;?>
        <div>
            <input type="submit" value="Vote">
            <a href="result.php?id=<?=$poll['id']?>">View Result</a>
        </div>
    </form>
</div>

<?=template_footer()?>