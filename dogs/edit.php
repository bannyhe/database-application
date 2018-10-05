<?php
require_once "pdo.php";
session_start();

if ( isset($_POST['name']) && isset($_POST['breed'])
     && isset($_POST['owner']) && isset($_POST['email']) && isset($_POST['age']) ) {

    // Data validation
    if ( strlen($_POST['name']) < 1 || strlen($_POST['breed']) < 1 || strlen($_POST['owner']) < 1
    || strlen($_POST['email']) < 1 || strlen($_POST['age']) < 1 ) {
        $_SESSION['error'] = 'All fields are required';
        header("Location: edit.php?dogs_id=".$_POST['dogs_id']);
        return;
    }

    $sql = "UPDATE dogs SET name = :nm,
            breed = :br, owner = :ow, email = :em, age = :ag
            WHERE dogs_id = :dogs_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        ':nm' => $_POST['name'],
        ':br' => $_POST['breed'],
        ':ow' => $_POST['owner'],
        ':em' => $_POST['email'],
        ':ag' => $_POST['age'],
        ':dogs_id' => $_POST['dogs_id']));
    $_SESSION['success'] = 'Record edited';
    header( 'Location: index.php' ) ;
    return;
}

// Guardian: Make sure that dogs_id is present
if ( ! isset($_GET['dogs_id']) ) {
  $_SESSION['error'] = "Missing dogs_id";
  header('Location: index.php');
  return;
}

$stmt = $pdo->prepare("SELECT * FROM dogs where dogs_id = :xyz");
$stmt->execute(array(":xyz" => $_GET['dogs_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ( $row === false ) {
    $_SESSION['error'] = 'Bad value for dogs_id';
    header( 'Location: index.php' ) ;
    return;
}

// Flash pattern
if ( isset($_SESSION['error']) ) {
    echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
    unset($_SESSION['error']);
}

$nm= htmlentities($row['name']);
$br = htmlentities($row['breed']);
$ow = htmlentities($row['owner']);
$em = htmlentities($row['email']);
$ag = htmlentities($row['age']);
$dogs_id = $row['dogs_id'];
?>
<h1>Editing Dogs</h1>
<form method="post">
<p>Name:
<input type="text" name="name" value="<?= $nm ?>"></p>
<p>Breed:
<input type="text" name="breed" value="<?= $br ?>"></p>
<p>Owner Name:
<input type="text" name="owner" value="<?= $ow ?>"></p>
<p>Owner Email:
<input type="text" name="email" value="<?= $em ?>"></p>
<p>Age:
<input type="text" name="age" value="<?= $ag ?>"></p>

<input type="hidden" name="dogs_id" value="<?= $dogs_id ?>">

<input type="submit" value="Save"/>
<input type="submit" value="Cancel"/></p>
</form>
