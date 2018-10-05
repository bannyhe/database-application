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
<title>Mu He - 8d8f733a</title>
<meta http-equiv="X-UA-Compatible" content="IE=9; IE=8; IE=7; IE=EDGE"/>
<meta charset="UTF-8">
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
<label for="nam">User Name</label>
<input type="text" name="email" id="email"><br/>
<label for="id_1723">Password</label>
<input type="text" name="pass" id="id_1723"><br/>
<input type="submit" name="submit" value="Log In">
<input type="submit" name="cancel" value="Cancel">
</form>
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
