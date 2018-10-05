<?php
require_once "pdo.php";
session_start();

if ( isset($_SESSION['edit_error'])) {
  echo '<p style="color:red">'.$_SESSION['edit_error']."</p>\n";
  unset($_SESSION['edit_error']);
}

if ( isset($_POST['submit'])) {
  if (strlen($_POST['first_name']) < 1 || strlen($_POST['last_name'])< 1 ||
  strlen($_POST['email'])< 1 || strlen($_POST['headline'])< 1 ||
  strlen($_POST['summary'])< 1) {
    $_SESSION['edit_error'] = "All fields are required";
    header("Location: edit.php?profile_id=".$_REQUEST['profile_id']);
    return;
  }elseif(strpos($_POST['email'], '@') === false) {
    $_SESSION['edit_error'] = "Email address must contain @";
    header("Location: edit.php?profile_id=".$_REQUEST['profile_id']);
    return;
  }else{
    $sql = "UPDATE Profile SET first_name = :fn, last_name = :lm,
            email = :em, headline = :he, summary = :su
            WHERE profile_id = :profile_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        ':fn' => $_POST['first_name'],
        ':lm' => $_POST['last_name'],
        ':em' => $_POST['email'],
        ':he' => $_POST['headline'],
        ':su' => $_POST['summary'],
        ':profile_id' => $_POST['profile_id']));
    $_SESSION['success'] = 'Profile Saved';
    header( 'Location: index.php' ) ;
    return;
  }
}

$stmt = $pdo->prepare("SELECT * FROM Profile WHERE profile_id = :xyz");
$stmt->execute(array(
    ":xyz" => $_GET['profile_id']));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row === false) {
      $_SESSION['error'] = 'Bad value for profile_id';
      header('Location: index.php');
      return;
    }

$first = htmlentities($row['first_name']);
$last = htmlentities($row['last_name']);
$email = htmlentities($row['email']);
$headline = htmlentities($row['headline']);
$summary = htmlentities($row['summary']);
$profile_id = $row['profile_id'];
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Banny's Resume Registry - Edit Profile</title>
    <meta http-equiv="X-UA-Compatible" content="IE=9; IE=8; IE=7; IE=EDGE"/>
    <meta charset="utf-8">
  </head>
  <body>
    <h1>Editing Profile for UMSI</h1>
    <form method="post">
      <input type="hidden" name="profile_id" value="<?= $profile_id ?>">
    <p>First Name:
    <input type="text" name="first_name" value="<?= $first ?>"></p>
    <p>Last Name:
    <input type="text" name="last_name" value="<?= $last ?>"></p>
    <p>Email:
    <input type="text" name="email" value="<?= $email ?>"></p>
    <p>Headline:
    <input type="text" name="headline" value="<?= $headline ?>"></p>
    <p>Summary:</p>
    <textarea name="summary" rows="8" cols="80"><?= $summary ?>"></textarea>

    <p><input type="submit" name="submit" value="Save"/>
    <input type="button" name="submit" value="Cancel" onclick="location.href='index.php'"/></p>
    </form>
  </body>
</html>
