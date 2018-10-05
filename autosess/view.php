<?php

session_start();
require_once "pdo.php";

if ( ! isset($_SESSION['name']) ) {
    die('Not logged in');
}
?>


<!DOCTYPE html>
<html>
<head>
<title>Mu He's Automobile Tracker</title>
<meta http-equiv="X-UA-Compatible" content="IE=9; IE=8; IE=7; IE=EDGE"/>
<meta charset="UTF-8">
</head>
<body>
<div class="container">
<h1>Tracking Autos for <?= htmlentities($_SESSION['name'])?></h1>
<?php
if ( isset($_SESSION['error']) ) {
    echo('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
    unset($_SESSION['error']);
}
?>
</div>
<h1>Automobiles</h1>
<?php
if (isset($_SESSION['name'])){
    if ( isset($_SESSION['error']) ) {
        echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
        unset($_SESSION['error']);
    }
    if ( isset($_SESSION['success']) ) {
        echo '<p style="color:green">'.$_SESSION['success']."</p>\n";
        unset($_SESSION['success']);
    }
    echo('<table border="1">'."\n");

    $stmt = $pdo->query("SELECT auto_id FROM autos");
    if ($row = $stmt->fetch(PDO::FETCH_ASSOC) === FALSE){
        echo('No rows found');
    }else{
        echo('<thead><tr><th>Make</th><th>Year</th><th>Mileage</th><th>Action</th></tr></thead>');
        $stmt = $pdo->query("SELECT auto_id, make, year, mileage FROM autos");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){

            echo("<tr><td>");
            echo(htmlentities($row['make']));
            echo("</td><td>");
            echo(htmlentities($row['year']));
            echo("</td><td>");
            echo(htmlentities($row['mileage']));
            echo("</td><td>");
            echo('<a href="edit.php?auto_id='.$row['auto_id'].'">Edit</a> / ');
            echo('<a href="delete.php?auto_id='.$row['auto_id'].'">Delete</a>');
            echo("</td></tr>\n");
        }
    }

    echo('</table>');
}else{
    echo('<p><a href="login.php">Please Log In</a></p>');
    echo('<p>Attempt to <a href="autos.php">Add data</a> without logging in will be denied.</p>');
}
    $stmt = $pdo->query("SELECT auto_id FROM autos");
?>
<br /><a href="add.php">Add New</a>
<a href="logout.php">Logout</a>
</body>
</html>
