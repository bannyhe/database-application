<?php

session_start();
require_once "pdo.php";

if ( ! isset($_SESSION['name']) ) {
    die('ACCESS DENIED');
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Mu He - Wizard Registry</title>
<meta http-equiv="X-UA-Compatible" content="IE=9; IE=8; IE=7; IE=EDGE"/>
<meta charset="UTF-8">
<!-- Latest compiled and minified CSS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"
  integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

  <!-- Optional theme -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css"
  integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">
</head>
<body>
<div class="container">
<h1>Tracking Wizard for <?= htmlentities($_SESSION['name'])?></h1>
<?php
if ( isset($_SESSION['error']) ) {
    echo('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
    unset($_SESSION['error']);
}
?>
</div>
<div class="container">
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

    $stmt = $pdo->query("SELECT wizard_id FROM wizard");
    if ($row = $stmt->fetch(PDO::FETCH_ASSOC) === FALSE){
        echo('No rows found');
    }else{
        echo('<thead><tr><th>Name</th><th>Email</th><th>House</th><th>Wand</th><th>Age</th><th>Action</th></tr></thead>');
        $stmt = $pdo->query("SELECT wizard_id, name, email, house, wand, age FROM wizard");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){

            echo("<tr><td>");
            echo(htmlentities($row['name']));
            echo("</td><td>");
            echo(htmlentities($row['email']));
            echo("</td><td>");
            echo(htmlentities($row['house']));
            echo("</td><td>");
            echo(htmlentities($row['wand']));
            echo("</td><td>");
            echo(htmlentities($row['age']));
            echo("</td><td>");
            echo('<a href="edit.php?wizard_id='.$row['wizard_id'].'">Edit</a> / ');
            echo('<a href="delete.php?wizard_id='.$row['wizard_id'].'">Delete</a>');
            echo("</td></tr>\n");
        }
    }

    echo('</table>');
}else{
    echo('<p><a href="login.php">Please Log In</a></p>');
    echo('<p>Attempt to <a href="add.php">Add data</a> without logging in will be denied.</p>');
}
    $stmt = $pdo->query("SELECT wizard_id FROM wizard");
?>
<br /><a href="add.php">Add New Entry</a>
<br /><br />
<a href="logout.php">Logout</a>
</div>
</body>
</html>
