<?php
require_once "pdo.php";
session_start();

if ( isset($_POST['delete']) && isset($_POST['dogs_id']) ) {
    $sql = "DELETE FROM dogs WHERE dogs_id = :zip";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(':zip' => $_POST['dogs_id']));
    $_SESSION['success'] = 'Record deleted';
    header( 'Location: index.php' ) ;
    return;
}

// Guardian: Make sure that dogs_id is present
if ( ! isset($_GET['dogs_id']) ) {
  $_SESSION['error'] = "Missing dogs_id";
  header('Location: index.php');
  return;
}

$stmt = $pdo->prepare("SELECT name, dogs_id FROM dogs where dogs_id = :xyz");
$stmt->execute(array(":xyz" => $_GET['dogs_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ( $row === false ) {
    $_SESSION['error'] = 'Bad value for dogs_id';
    header( 'Location: index.php' ) ;
    return;
}

?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Deleting...</title>
  </head>
  <body>
    <p>Confirm: Deleting <?= htmlentities($row['name']) ?></p>
    <form method="post">
    <input type="hidden" name="dogs_id" value="<?= $row['dogs_id'] ?>">
    <input type="submit" value="Delete" name="delete">
    <a href="index.php">Cancel</a>
    </form>
  </body>
</html>
