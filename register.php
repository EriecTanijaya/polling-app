<?php
    session_start();

    if (isset($_SESSION['loggedin'])) {
        header('Location: index.php');
        exit;
    }

    $msg = "";
    if (isset($_SESSION['msg'])) {
        $msg = $_SESSION['msg'];
        $_SESSION['msg'] = "";
    }
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Register</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="shortcut icon" href="favicon.ico" />
    <link href="style.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.13.0/css/all.css">
</head>

<body>
    <div class="register">
        <h1><i class="fab fa-wpforms fa-sm" style="margin-right: 10px"></i>Register</h1>
        <?php
            if ($msg != "") {
                echo '<div class="error-form"><i class="fas fa-exclamation-circle"></i> ' . $msg . '</div>';
            }
        ?>
        <form action="register_action.php" method="post" autocomplete="off">
            <label for="name">
                <i class="fas fa-user-alt"></i>
            </label>
            <input type="text" name="name" placeholder="Nama Lengkap" id="name" required>

            <label for="npm">
                <i class="fas fa-id-card"></i>
            </label>
            <input type="text" name="npm" placeholder="NPM" id="npm" required>

            <label for="prodi">
                <i class="fas fa-user-graduate"></i>
            </label>
            <select name="prodi" required>
                <option value="">Pilih Prodinyaa</option>
                <?php
                    $options = array('Sistem Informasi', 'Manajemen', 'Akuntansi', 'Pariwisata', 'Ilmu Hukum', 'Teknik Elektro', 'Teknologi Informasi', 'Teknik Sipil', 'Arsitektur', 'Pendidikan Bahasa Inggris');
                    foreach($options as $option) {
                        echo '<option value="'. $option .'">'. $option .'</option>';
                    }
                ?>
            </select>

            <label for="username">
                <i class="fas fa-user"></i>
            </label>
            <input type="text" name="username" placeholder="Username" id="username" required>
            <label for="password">
                <i class="fas fa-lock"></i>
            </label>
            <input type="password" name="password" placeholder="Password" id="password" required>
            <label for="email">
                <i class="fas fa-envelope"></i>
            </label>
            <input type="email" name="email" placeholder="Email" id="email" required>
            <p>dah punya akun? <a href="login.php">sini login</a></p>
            <input type="submit" value="Register">
        </form>
    </div>
</body>

</html>