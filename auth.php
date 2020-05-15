<?php
session_start();
$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = '';
$DATABASE_NAME = 'phppoll';

$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if (mysqli_connect_errno()) {
    exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}

if (!isset($_POST['username'], $_POST['password'])) {
    $_SESSION['msg'] = "Please fill both the username and password fields!";
    header('Location: login.php');
}

// To prevent sql injection
if ($stmt = $con->prepare('SELECT id, password, ip FROM accounts WHERE username = ?')) {
    // Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
    $stmt->bind_param('s', $_POST['username']);
    $stmt->execute();

    // Store the result so we can check if the account exists in the database.
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $password, $ip);
        $stmt->fetch();
        // Account exists, now we verify the password.
        // Note: remember to use password_hash in your registration file to store the hashed passwords.
        // Kalo ga mau di hash pake ini
        //if ($_POST['password'] === $password) {
        if (password_verify($_POST['password'], $password)) {
            // Create sessions so we know the user is logged in, they basically act like cookies but remember the data on the server.
            session_regenerate_id();
            $_SESSION['loggedin'] = true;
            $_SESSION['name'] = $_POST['username'];
            $_SESSION['id'] = $id;

            // update the ip
            if (!empty($_SERVER['HTTP_CLIENT_IP'])){
                $current_ip = $_SERVER['HTTP_CLIENT_IP'];
                //Is it a proxy address
            } else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
                $current_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            } else {
                $current_ip = $_SERVER['REMOTE_ADDR'];
            }

            $_SESSION['ip'] = $current_ip;

            // update ip in db
            if ($current_ip != $ip) {
                $stmt = $con->prepare('UPDATE accounts SET ip = ? WHERE id = ?');
                $stmt->bind_param('si', $current_ip, $id);
                $stmt->execute();
            }

            // dev
            if ($_SESSION['name'] == "admin") { //ganti ke eriectan nanti
                $_SESSION['su'] = true;
            }

            header('Location: index.php');
        } else {
            $_SESSION['msg'] = "Incorrect password!";
            header('Location: login.php');
        }
    } else {
        $_SESSION['msg'] = "There is no account with that username!";
        header('Location: login.php');
    }

    $stmt->close();
}

?>