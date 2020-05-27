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

?>

<?=template_header('Report')?>

<div class="container-fluid content poll-vote">
    <h2><i class="fa fa-file-alt fa-lg" style="margin-right: 10px"></i>Report</h2>
    <p>tampilin seluruh data pengguna disini, juga ada berapa user, berapa poll yang dibuat</p>
</div>

<?=template_footer()?>