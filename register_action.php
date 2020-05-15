<?php
// Change this to your connection info.
$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = '';
$DATABASE_NAME = 'phppoll';
// Try and connect using the info above.
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if (mysqli_connect_errno()) {
    // If there is an error with the connection, stop the script and display the error.
    exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}

// Now we check if the data was submitted, isset() function will check if the data exists.
if (!isset($_POST['username'], $_POST['password'], $_POST['email'])) {
    // Could not get the data that should have been sent.
    exit('Please complete the registration form!');
}
// Make sure the submitted registration values are not empty.
if (empty($_POST['username']) || empty($_POST['password']) || empty($_POST['email'])) {
    // One or more values are empty.
    exit('Please complete the registration form');
}

// We need to check if the account with that username exists.
if ($stmt = $con->prepare('SELECT id, password FROM accounts WHERE username = ?')) {
    // Bind parameters (s = string, i = int, b = blob, etc), hash the password using the PHP password_hash function.
    $stmt->bind_param('s', $_POST['username']);
    $stmt->execute();
    
    // Store the result so we can check if the account exists in the database.
    $stmt->store_result();

    session_start();

    if ($stmt->num_rows > 0) {
        // Username already exists
        $_SESSION['msg'] = "Username exists, please choose another!";
        header('Location: register.php');
        exit;
    } else {
        // check NPM
        if ($stmt = $con->prepare('SELECT id FROM accounts WHERE npm = ?')) {
            $stmt->bind_param('s', $_POST['npm']);
            $stmt->execute();
            
            // Store the result so we can check if the account exists in the database.
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $_SESSION['msg'] = "NPM exists, please login with existing account!";
                header('Location: register.php');
                exit;
            }
        }

        // Insert new account
        // Email validation
        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            exit('Email is not valid!');
        }

        // Password length validation
        if (strlen($_POST['password']) > 20 || strlen($_POST['password']) < 5) {
            $_SESSION['msg'] = "Password must be between 5 and 20 characters long!";
            header('Location: register.php');
        }

        // Username doesnt exists, insert new account
        if ($stmt = $con->prepare('INSERT INTO accounts (username, password, email, npm, ip, prodi) VALUES (?, ?, ?, ?, ?, ?)')) {
            // We do not want to expose passwords in our database, so hash the password and use password_verify when a user logs in.
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

            if (!empty($_SERVER['HTTP_CLIENT_IP'])){
                $ip = $_SERVER['HTTP_CLIENT_IP'];
                //Is it a proxy address
            } else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            } else {
                $ip = $_SERVER['REMOTE_ADDR'];
            }

            $stmt->bind_param('ssssss', $_POST['username'], $password, $_POST['email'], $_POST['npm'], $ip, $_POST['prodi']);
            $stmt->execute();
            $stmt->store_result();

            session_start();

            $_SESSION['loggedin'] = true;
            $_SESSION['name'] = $_POST['username'];
            $_SESSION['id'] = mysqli_insert_id($con);
            $_SESSION['ip'] = $ip;
            header('Location: index.php');
        } else {
            // Something is wrong with the sql statement, check to make sure accounts table exists with all 3 fields.
            echo 'Could not prepare statement!';
        }
    }
    $stmt->close();
} else {
    // Something is wrong with the sql statement, check to make sure accounts table exists with all 3 fields.
    echo 'Could not prepare statement!';
}
$con->close();

?>
