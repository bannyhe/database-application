<?php
require_once "pdo.php";
session_start();

if(isset($_POST['submit'])){
    if (strpos($_POST['email'], '@') == false) {
      $_SESSION['error'] = "Email must have an at-sign (@)";
      header("Location: login.php");
      return;
// If filled, then convert hash code and see if it matches the answer
    }else{
      $salt = 'XyZzy12*_';
      $salted_attempt = hash('md5', $salt.$_POST['pass']);
      $stmt = $pdo->prepare('SELECT user_id, name FROM users WHERE email = :em AND
        password = :pw');
      $stmt->execute(array( ':em'=> $_POST['email'], ':pw' => $salted_attempt));
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      if ( $row !== false) {
        $_SESSION['user_id'] = $row['user_id'];
        header("Location: index.php");
        return;
      }else{
        $_SESSION['error'] = 'Invalid email or incorrect password';
        header("Location: login.php");
        return;
      }
   }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Banny's Resume Registry</title>
<meta http-equiv="X-UA-Compatible" content="IE=9; IE=8; IE=7; IE=EDGE"/>
<meta charset="UTF-8">
<!-- Latest compiled and minified CSS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"
  integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

  <!-- Optional theme -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css"
  integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">
</head>

<body>
<h1>Please Log In</h1>

<form method="POST">
<?php
if ( isset($_SESSION['error']) ) {
    echo('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
    unset($_SESSION['error']);
}
?>
<p>Email:
  <input type="text" name="email" id="email" /></p>
<p>Password:
  <input type="password" name="pass" id="pass"></p>
<p><input type="submit" name="submit" value="Log In" onclick="validate()"/></p>
<p><input type="submit" name="cancel" value="Cancel" onclick="location.href='index.php'"/></p>
</form>
<script type="text/javascript">
  function validate() {
    console.log('Validating...');
    try {
      em = document.getElementById('email').value;
      pw = document.getElementById('pass').value;
      console.log("Validating email=" + em);
      console.log("Validating password=" + pw);
      if (em == null || em == "" || pw == null || pw == "") {
        alert("Both fields must be filled out");
        return false;
      } else if (!(em.includes("@"))) {
        alert("Email address must contain @");
        return false;
      }
      return true;
    } catch(e) {
      return false;
    }
  }
</script>
For a password hint, view source and find a password hint
in the HTML comments.
<!-- Hint: The password is the three character name of the
programming language used in this class (all lower case)
followed by 123. -->
</body>
</html>
