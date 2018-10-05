<?php
require_once "pdo.php";
session_start();

if ( isset($_POST['delete']) && isset($_POST['profile_id']) ) {
    $sql = "DELETE FROM Profile WHERE profile_id = :zip";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(':zip' => $_POST['profile_id']));
    $_SESSION['success'] = 'Profile deleted';
    header( 'Location: index.php' ) ;
    return;
}

if ( ! isset($_GET['profile_id']) ) {
  $_SESSION['error'] = "Missing profile_id";
  header('Location: index.php');
  return;
}

$stmt = $pdo->prepare("SELECT profile_id, first_name, last_name FROM Profile WHERE
  profile_id = :xyz");
$stmt->execute(array(":xyz" => $_GET['profile_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ( $row === false ) {
    $_SESSION['error'] = 'Bad value for profile_id';
    header( 'Location: index.php' ) ;
    return;
}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Deleting...</title
    <meta http-equiv="X-UA-Compatible" content="IE=9; IE=8; IE=7; IE=EDGE"/>
    <meta charset="utf-8">
  </head>
  <body>
    <h1>Deleting Profile</h1>
    <p>First Name: Deleting <?= htmlentities($row['first_name']) ?></p>
    <p>Last Name: Deleting <?= htmlentities($row['last_name']) ?></p>
    <form method="post">
      <input type="hidden" name="profile_id" value="<?= $row['profile_id'] ?>">
      <input type="submit" value="Delete" name="delete">
      <input type="button" value="Cancel" name="cancel" onclick="location.href='index.php'"/>
    </form>
  </body>
</html>
