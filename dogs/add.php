<?php
require_once "pdo.php";
session_start();

if ( ! isset($_SESSION['name']) ) {
    die('ACCESS DENIED');
}

if ( isset($_POST['add'])) {
    if ( strlen($_POST['name']) === 0 || strlen($_POST['breed']) === 0 || strlen($_POST['owner']) === 0 || strlen($_POST['email']) === 0 || strlen($_POST['age']) === 0 ) {
      $_SESSION['error'] = "All fields are required";
    }
    elseif(!is_numeric($_POST['age'])) {
      $_SESSION['error'] = "Age must be numeric";
    }
    else {
      $sql = "INSERT INTO dogs
      (name, breed, owner, email, age) VALUES (:nm, :br, :ow, :em, :ag)";
      $stmt = $pdo->prepare($sql);
      $stmt->execute(array(
          ':nm' => $_POST['name'],
          ':br' => $_POST['breed'],
          ':ow' => $_POST['owner'],
          ':em' => $_POST['email'],
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
<title>Mu He - Dog Clients Tracker</title>
<meta http-equiv="X-UA-Compatible" content="IE=9; IE=8; IE=7; IE=EDGE"/>
<meta charset="UTF-8">
</head>
<body>
<div class="container">
<h1>Tracking Dog Clients for <?= htmlentities($_SESSION['name'])?></h1>
<?php
if ( isset($_SESSION['error']) ) {
    echo('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
    unset($_SESSION['error']);
}
?>
<form method="post">
  <p>Name:
    <input type="text" name="name" size="60"></p>
  <p>Breed:
    <input type="text" name="breed" size-"60"></p>
  <p>Owner Name:
    <input type="text" name="owner" size="60"></p>
  <p>Owner Email:
    <input type="text" name="email" size="60"></p>
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
