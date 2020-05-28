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

?>

<?=template_header('View')?>

<div class="container-fluid content home">
    <h2><i class="fa fa-file-alt fa-lg" style="margin-right: 10px"></i>Report</h2>
</div>

<?=template_footer()?>