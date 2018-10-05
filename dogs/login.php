<?php
$salt = 'XyZzy12*_';
$stored_hash = '1a52e17fa899cf40fb04cfc42e6352f1';  // Pw is php123

session_start();
if(isset($_POST['submit'])){
    $salted_attempt = hash('md5', $salt.$_POST['pass']);
  // Check to see if email and password are filled
    if (strlen($_POST['email']) === 0 || strlen($_POST['pass']) === 0 ) {
      $_SESSION['error'] = "Email and password are required";
      header("Location: login.php");
      return;
// If filled, then convert hash code and see if it matches the answer
    }
    elseif (strpos($_POST['email'], '@') == false) {
      error_log("Login fail ".$_POST['email'].$salted_attempt);
      $_SESSION['error'] = "Email must have an at-sign (@)";
      header("Location: login.php");
      return;
    }
    elseif($salted_attempt !== $stored_hash){
      error_log("Login fail ".$_POST['email'].$salted_attempt);
      $_SESSION['error'] = "Incorrect password";
      header("Location: login.php");
      return;
    }
    else{
      error_log("Login success ".$_POST['email']);
      $_SESSION['name'] = $_POST['email'];
      header("Location: view.php");
      return;
    }
}

if ( isset($_POST['cancel'] ) ) {
    // Redirect the browser to view.php
    header("Location: index.php");
    return;
}

?>

<!DOCTYPE html>
<html>
<head>
<title>Mu He - Dog Clients</title>
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
<div class="container">
<h1>Please Log In</h1>

<form method="POST">
<?php
if ( isset($_SESSION['error']) ) {
    echo('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
    unset($_SESSION['error']);
}
?>
<p>User Name:
  <input type="text" name="email" id="email"><br/></p>
<p>Password:
  <input type="password" name="pass" id="pass"><br/></p>
  <input type="submit" name="submit" value="Log In" onclick="validate()">
  <input type="submit" name="cancel" value="Cancel" onclick="location.href='index.php'"
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
<p>
For a password hint, view source and find a password hint
in the HTML comments.
<!-- Hint: The password is the three character name of the
programming language used in this class (all lower case)
followed by 123. -->
</p>
</div>
</body>
</html>
