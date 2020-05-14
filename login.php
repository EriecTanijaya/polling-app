<?php
    session_start();

    if (isset($_SESSION['loggedin'])) {
        header('Location: index.php');
        exit;
    }
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Login</title>
    <link href="style.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.13.0/css/all.css">
</head>

<body>
    <div class="login">
        <h1>Login</h1>
        <form action="auth.php" method="post">
            <label for="username">
                <i class="fas fa-user"></i>
            </label>
            <input type="text" name="username" placeholder="Username" id="username" required>
            <label for="password">
                <i class="fas fa-lock"></i>
            </label>
            <input type="password" name="password" placeholder="Password" id="password" required>
            <p>Belom punya akun? <a href="register.php">Register gih</a></p>
            <input type="submit" value="Login">
        </form>
    </div>
</body>

</html>