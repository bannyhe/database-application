<?php
require_once "pdo.php";
session_start();

if ( ! isset($_SESSION['name']) ) {
    die('ACCESS DENIED');
}

if ( isset($_POST['add'])) {
    if ( strlen($_POST['make']) === 0 || strlen($_POST['model']) === 0 || strlen($_POST['year']) === 0 || strlen($_POST['mileage'] === 0 )) {
      $_SESSION['error'] = "All fields are required";
    }
    elseif(!is_numeric($_POST['year']) || !is_numeric($_POST['mileage'])) {
      $_SESSION['error'] = "Mileage and year must be numeric";
    }
    else {
      $sql = "INSERT INTO autos
      (make, model, year, mileage) VALUES (:mk, :md, :yr, :mi)";
      $stmt = $pdo->prepare($sql);
      $stmt->execute(array(
          ':mk' => $_POST['make'],
          ':md' => $_POST['model'],
          ':yr' => $_POST['year'],
          ':mi' => $_POST['mileage'])
      );
      $_SESSION['success'] = 'Record added.';
      header( 'Location: index.php' ) ;
      return;
    }
}

// Flash pattern
if ( isset($_SESSION['error']) ) {
    echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
    unset($_SESSION['error']);
}

if (isset($_POST['logout'])) {
    header("Location: logout.php");
    return;
}

?>

<!DOCTYPE html>
<html>
<head>
<title>Mu He - Automobile Tracker</title>
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
    <input type="text" name="make" size="60"></p>
  <p>Model:
    <input type="text" name="model" size-"30"></p>
  <p>Year:
    <input type="text" name="year" size="30"></p>
  <p>Mileage:
    <input type="text" name="mileage"></p>
  <input type="submit" name="add" value="Add"/>
  <input type="submit" name="logout" value="Cancel"/>
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
