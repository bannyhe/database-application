<?php
require_once "pdo.php";
session_start();

if ( ! isset($_SESSION['name']) ) {
    die('ACCESS DENIED');
}

if ( isset($_POST['add'])) {
    if ( strlen($_POST['name']) === 0 || strlen($_POST['email']) === 0 || strlen($_POST['house']) === 0 || strlen($_POST['wand']) === 0 || strlen($_POST['age']) === 0 ) {
      $_SESSION['error'] = "All fields are required";
    }
    elseif(!is_numeric($_POST['age'])) {
      $_SESSION['error'] = "Age must be an integer";
    }
    elseif (strpos($_POST['email'], '@') == false) {
      $_SESSION['error'] = "Email must have an at-sign (@)";
    }
    else {
      $sql = "INSERT INTO wizard
      (name, email, house, wand, age) VALUES (:nm, :em, :hs, :wd, :ag)";
      $stmt = $pdo->prepare($sql);
      $stmt->execute(array(
          ':nm' => $_POST['name'],
          ':em' => $_POST['email'],
          ':hs' => $_POST['house'],
          ':wd' => $_POST['wand'],
          ':ag' => $_POST['age'])
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
<title>Mu He - Wizard Registry Tracker</title>
<meta http-equiv="X-UA-Compatible" content="IE=9; IE=8; IE=7; IE=EDGE"/>
<meta charset="UTF-8">
<!-- Latest compiled and minified CSS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"
  integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

  <!-- Optional theme -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css"
  integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">
</head>
</head>
<body>
<div class="container">
<h1>Tracking Wizard Registry for <?= htmlentities($_SESSION['name'])?></h1>
<?php
if ( isset($_SESSION['error']) ) {
    echo('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
    unset($_SESSION['error']);
}
?>
<form method="post">
  <p>Name:
    <input type="text" name="name" size="60"></p>
  <p>Email:
    <input type="text" name="email" size="60"></p>
  <p>House:
    <input type="text" name="house" size="60"></p>
  <p>Wand:
    <input type="text" name="wand" size="60"></p>
  <p>Age:
    <input type="text" name="age" size="60"></p>
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
