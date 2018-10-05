<?php
require_once "pdo.php";
session_start();

if ( isset($_POST['delete']) && isset($_POST['wizard_id']) ) {
    $sql = "DELETE FROM wizard WHERE wizard_id = :zip";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(':zip' => $_POST['wizard_id']));
    $_SESSION['success'] = 'Record deleted';
    header( 'Location: index.php' ) ;
    return;
}

// Guardian: Make sure that wizard_id is present
if ( ! isset($_GET['wizard_id']) ) {
  $_SESSION['error'] = "Missing wizard_id";
  header('Location: index.php');
  return;
}

$stmt = $pdo->prepare("SELECT name, wizard_id FROM wizard where wizard_id = :xyz");
$stmt->execute(array(":xyz" => $_GET['wizard_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ( $row === false ) {
    $_SESSION['error'] = 'Bad value for wizard_id';
    header( 'Location: index.php' ) ;
    return;
}

?>
<!DOCTYPE html>
<html>
  <head>
    <title>Deleting...</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=9; IE=8; IE=7; IE=EDGE"/>
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
    <p>Confirm: Deleting <?= htmlentities($row['name']) ?></p>
    <form method="post">
    <input type="hidden" name="wizard_id" value="<?= $row['wizard_id'] ?>">
    <input type="submit" value="Delete" name="delete">
    <a href="index.php">Cancel</a>
    </form>
  </div>
  </body>
</html>
