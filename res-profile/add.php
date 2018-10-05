<?php
require_once "pdo.php";
session_start();

if ( isset($_POST['add'])) {
    if ( strlen($_POST['first_name']) <1 || strlen($_POST['last_name']) <1 ||
    strlen($_POST['email']) <1 || strlen($_POST['headline']) <1 ||
    strlen($_POST['summary']) <1 ) {
      $_SESSION['error'] = "All fields are required";
    }elseif(strpos($_POST['email'], '@') === false) {
        $_SESSION['error'] = "Email must have an at-sign (@)";
        header("Location: add.php");
        return;
    }else{
      $sql = "INSERT INTO Profile
      (user_id, first_name, last_name, email, headline, summary) VALUES (:uid, :fn, :lm, :em, :he, :su)";
      $stmt = $pdo->prepare($sql);
      $stmt->execute(array(
          ':uid' => $_SESSION['user_id'],
          ':fn' => $_POST['first_name'],
          ':lm' => $_POST['last_name'],
          ':em' => $_POST['email'],
          ':he' => $_POST['headline'],
          ':su' => $_POST['summary']));
      $_SESSION['success'] = 'Profile added.';
      header( 'Location: index.php' ) ;
      return;
    }
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
<h1>Adding Profile for UMSI</h1>
<?php
if ( isset($_SESSION['error']) ) {
    echo('<p style="color: red">'.htmlentities($_SESSION['error'])."</p>\n");
    unset($_SESSION['error']);
}
?>
<form method="post">
  <p>First Name:
    <input type="text" name="first_name" size="60"></p>
  <p>Last Name:
    <input type="text" name="last_name" size="60"></p>
  <p>Email:
    <input type="text" name="email" size="30"></p>
  <p>Headline:
    <br /><input type="text" name="headline" size="90"></p>
  <p>Summary:
    <br /><textarea name="summary" rows="8" cols="90"></textarea></p>
  <input type="submit" name="add" value="Add">
  <input type="button" onclick="location.href='index.php'" value="Cancel">
</form>
</div>
</body>
</html>
