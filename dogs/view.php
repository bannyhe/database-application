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
<title>Mu He - Dog Clients</title>
<meta http-equiv="X-UA-Compatible" content="IE=9; IE=8; IE=7; IE=EDGE"/>
<meta charset="UTF-8">
</head>
<body>
<div class="container">
<h1>Tracking Dogs for <?= htmlentities($_SESSION['name'])?></h1>
<?php
if ( isset($_SESSION['error']) ) {
    echo('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
    unset($_SESSION['error']);
}
?>
</div>
<h1>Dogs</h1>
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

    $stmt = $pdo->query("SELECT dogs_id FROM dogs");
    if ($row = $stmt->fetch(PDO::FETCH_ASSOC) === FALSE){
        echo('No rows found');
    }else{
        echo('<thead><tr><th>Name</th><th>Breed</th><th>Owner Name</th><th>Owner Email</th><th>Age</th><th>Action</th></tr></thead>');
        $stmt = $pdo->query("SELECT dogs_id, name, breed, owner, email, age FROM dogs");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){

            echo("<tr><td>");
            echo(htmlentities($row['name']));
            echo("</td><td>");
            echo(htmlentities($row['breed']));
            echo("</td><td>");
            echo(htmlentities($row['owner']));
            echo("</td><td>");
            echo(htmlentities($row['email']));
            echo("</td><td>");
            echo(htmlentities($row['age']));
            echo("</td><td>");
            echo('<a href="edit.php?dogs_id='.$row['dogs_id'].'">Edit</a> / ');
            echo('<a href="delete.php?dogs_id='.$row['dogs_id'].'">Delete</a>');
            echo("</td></tr>\n");
        }
    }

    echo('</table>');
}else{
    echo('<p><a href="login.php">Please Log In</a></p>');
    echo('<p>Attempt to <a href="dogs.php">Add data</a> without logging in will be denied.</p>');
}
    $stmt = $pdo->query("SELECT dogs_id FROM dogs");
?>
<br /><a href="add.php">Add New Entry</a>
<br /><br />
<a href="logout.php">Logout</a>
</body>
</html>
