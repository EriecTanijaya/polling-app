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

}

?>

<?=template_header('Edit Profile')?>

<div class="container-fluid content">

</div>

<?=template_footer()?>