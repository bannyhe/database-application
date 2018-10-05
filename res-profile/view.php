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
<title>Banny's Resume Registry</title>
<meta http-equiv="X-UA-Compatible" content="IE=9; IE=8; IE=7; IE=EDGE"/>
<meta charset="UTF-8">
</head>
<body>
<div class="container">
<?php
if ( isset($_SESSION['error']) ) {
    echo('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
    unset($_SESSION['error']);
}
?>
</div>
<h1>Banny's Resume Registry</h1>
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

    $stmt = $pdo->query("SELECT user_id FROM Profile");
    if ($row = $stmt->fetch(PDO::FETCH_ASSOC) === FALSE){
        echo('No rows found');
    }else{
        echo('<thead><tr><th>Name</th><th></th><th>Headline</th><th>Action</th></tr></thead>');
        $stmt = $pdo->query("SELECT user_id, first_name, last_name, headline FROM Profile");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){

            echo("<tr><td>");
            echo(htmlentities($row['first_name'].' '.$row['last_name']));
            echo("</td><td>");
            echo(htmlentities($row['headline']));
            echo("</td><td>");
            echo('<a href="edit.php?user_id='.$row['user_id'].'">Edit</a> / ');
            echo('<a href="delete.php?user_id='.$row['user_id'].'">Delete</a>');
            echo("</td></tr>\n");
        }
    }

    echo('</table>');
}else{
    echo('<p><a href="login.php">Please Log In</a></p>');
}
    $stmt = $pdo->query("SELECT user_id FROM Profile");
?>
<br /><a href="add.php">Add New Entry</a>
<br /><br />
<a href="logout.php">Logout</a>
</body>
</html>
