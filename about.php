<?php
include 'functions.php';
session_start();

if (!isset($_SESSION['loggedin'])) {
    header('Location: login.php');
    exit;
}

?>

<?=template_header('Poll Vote')?>

<div class="container-fluid content poll-vote">
    <h2><i class="fa fa-user-circle fa-lg" style="margin-right: 10px"></i>About this webssie</h2>
    <p>Website ini adalah tugas untuk mata kuliah Pemograman Web, dimana berguna untuk polling / voting sesuai
    dengan pertanyaan yang ada. Thanks to Pak Daniel yang membimbing agar terbuatnya website ini.</p>
    <p>Website ini powered up by Bootstrap, Color scheme pake Nord, dan thanks buat stackoverflow, W3School dkk.</p>
    <p>Website ini open source juga, bisa cek di <a href="https://github.com/EriecTanijaya/polling-app" target="_blank">github saye</a>
    </p>
</div>

<?=template_footer()?>