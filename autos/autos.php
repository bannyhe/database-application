<?php

session_start();
require_once "pdo.php";

if ( ! isset($_SESSION['name']) ) {
    die('Name parameter missing');
}

if (isset($_POST['add'])) {
    if (strlen($_POST['make']) === 0||strlen($_POST['year']) === 0||strlen($_POST['mileage']) === 0){
        $_SESSION['error'] = "Make is required";
    }
    elseif (!is_numeric($_POST['year'])||!is_numeric($_POST['mileage'])){
        $_SESSION['error'] = "Mileage and year must be numeric";
    }
    else{
        $stmt = $pdo->prepare('INSERT INTO autos
        (make, year, mileage) VALUES ( :mk, :yr, :mi)');
        $stmt->execute(array(
                ':mk' => $_POST['make'],
                ':yr' => $_POST['year'],
                ':mi' => $_POST['mileage'])
        );
        $_SESSION['success'] = "Record inserted";
    }
}
if (isset($_POST['logout'])) {
    header("Location: logout.php");
    return;
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
<form method="post">
  <p>Make:
      <input type="text" name="make" size="60"/></p>
  <p>Year:
      <input type="text" name="year" size="30"/></p>
  <p>Mileage:
      <input type="text" name="mileage" size="30"/></p>
  <input type="submit" name="add" value="Add">
  <input type="submit" name="logout" value="Logout">
  </form>
  </div>
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
?>
</body>
</html>
