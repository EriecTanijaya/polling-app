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

// Get exists data
$stmt = $pdo->prepare('SELECT name, npm, prodi, username, email FROM accounts WHERE id = ?');
$stmt->execute([$_GET['id']]);
$user = $stmt->fetch();

if (!empty($_POST)) {

    if ($_SESSION['id'] != $_GET['id']){
        $_SESSION['msg'] = 'Invalid ID!';
        header('Location: edit_profile.php');
        exit;
    }

    $stmt = $pdo->prepare('UPDATE accounts SET name = ?, npm = ?, prodi = ?, username = ?, email = ? WHERE id = ?');
    if ($stmt->execute([$_POST['name'], $_POST['npm'], $_POST['prodi'], $_POST['username'], $_POST['email'], $_GET['id']])) {
        $_SESSION['msg'] = 'Profile update successfully!';
        header('Location: profile.php');
        exit;
    } else {
        exit('Samting wen wrong sar');
    }

}
?>

<?=template_header('Edit Profile')?>

<div class="container-fluid content">
    <?php if ($msg): ?>
        <p><?=$msg?></p>
    <?php endif;?>

    <h2><i class="fa fa-user-edit fa-lg" style="margin-right: 10px;"></i>Edit Profile</h2>
    <div>
        <form action="edit_profile.php?id=<?=$_SESSION['id']?>" method="post">
            <div class="form-group">
                <label for="name">Nama Lengkap</label>
                <input type="text" class="form-control" name="name" id="name" value="<?=$user['name']?>" required>
            </div>
            <div class="form-group">
                <label for="npm">NPM</label>
                <input type="text" class="form-control" name="npm" id="npm" value="<?=$user['npm']?>" required>
            </div>
            <div class="form-group">
                <label for="prodi">Prodi</label>
                <select class="form-control" name="prodi" required>
                    <?php
                        $options = array('Sistem Informasi', 'Manajemen', 'Pariwisata', 'Ilmu Hukum', 'Teknik Elektro', 'Teknologi Informasi', 'Teknik Sipil', 'Arsitektur', 'Pendidikan Bahasa Inggris');
                        foreach($options as $option) {
                            echo '<option value="'. $option .'"'. ($option == $user['prodi'] ? 'selected' : '') .'>'. $option .'</option>';
                        }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" class="form-control" name="username" id="username" value="<?=$user['username']?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" name="email" id="email" value="<?=$user['email']?>" required>
            </div>
            <input type="submit" class="btn btn-success" value="Update">
        </form>
    </div>
</div>

<?=template_footer()?>