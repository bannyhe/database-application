<?php

session_start();
require_once "pdo.php";

if ( ! isset($_SESSION['name']) ) {
    die('Not logged in');
}

if (isset($_POST['add'])) {
    if (strlen($_POST['make']) === 0||strlen($_POST['year']) === 0||strlen($_POST['mileage']) === 0){
        $_SESSION['error'] = "Make is required";
        header("Location: add.php");
        return;
    }
    elseif (!is_numeric($_POST['year'])||!is_numeric($_POST['mileage'])){
        $_SESSION['error'] = "Mileage and year must be numeric";
        header("Location: add.php");
        return;
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
        header("Location: view.php");
        return;
    }
}

if (isset($_POST['cancel'])) {
    header("Location: view.php");
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
  <input type="submit" name="logout" value="Cancel">
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
}
?>
</body>
</html>
