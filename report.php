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

<?=template_header('Report')?>

<div class="container-fluid content home">
    <h2><i class="fa fa-file-alt fa-lg" style="margin-right: 10px"></i>Report</h2>
    
    <?php
    if ($msg != '') {
        echo '<p>'. $msg .'</p>';
    }
    ?>

    <!-- <p>tampilin seluruh data pengguna disini, juga ada berapa user, berapa poll yang dibuat, dari prodi apa</p> -->

    <div>
    <form action="report.php" method="get">
        <div class="form-group">
            <label for="query">Search</label>
            <input type="text" class="form-control" name="query" placeholder="Cari berdasarkan nama, npm dan prodi" required>
        </div>
        <input type="submit" class="btn btn-info" value="Search">
    </form>
    </div>
    
    <?php
    if (isset($_GET['query'])) {
        $pdo = pdo_connect_mysql();
        $query = $_GET['query'];
        $sql = "SELECT name, npm, prodi FROM accounts WHERE (name LIKE '%". $query ."%') OR (prodi LIKE '%". $query ."%') OR (npm LIKE '%". $query ."%')";
        $stmt = $pdo->query($sql);

        if ($stmt) {
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (count($users) == 0) {
                $_SESSION['msg'] = 'Data tidak ditemukan';
                header('Location: report.php');
                exit;
            } else {
                echo '<table class="table table-bordered">
                        <thead class="thead-dark">
                            <tr>
                                <td scope="col">#</td>
                                <td scope="col">Name</td>
                                <td scope="col">NPM</td>
                                <td scope="col">Prodi</td>
                            </tr>
                        </thead>
                        <tbody>';

                        $num = 1;
                        foreach ($users as $user) {
                            echo '<tr>
                                <th scope="row">'. $num .'</td>
                                <td>'. $user['name'] .'</td>
                                <td>'. $user['npm'] .'</td>
                                <td>'. $user['prodi'] .'</td>
                            </tr>';
                        $num++;
                        }
                echo '</tbody>
                    </table>';   
            }

        } else {
            $_SESSION['msg'] = 'Samting wen wrong sar';
            header('Location: report.php');
            exit;
        }
    }
    ?>

</div>

<?=template_footer()?>