<?php
require_once "pdo.php";
session_start();

if ( isset($_POST['name']) && isset($_POST['email'])
     && isset($_POST['house']) && isset($_POST['wand']) && isset($_POST['age']) ) {

    // Data validation
    if ( strlen($_POST['name']) < 1 || strlen($_POST['email']) < 1 || strlen($_POST['house']) < 1
    || strlen($_POST['wand']) < 1 || strlen($_POST['age']) < 1 ) {
        $_SESSION['error'] = 'All fields are required';
        header("Location: edit.php?wizard_id=".$_POST['wizard_id']);
        return;
    }

    $sql = "UPDATE wizard SET name = :nm,
            email = :em, house = :hs, wand = :wd, age = :ag
            WHERE wizard_id = :wizard_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        ':nm' => $_POST['name'],
        ':em' => $_POST['email'],
        ':hs' => $_POST['house'],
        ':wd' => $_POST['wand'],
        ':ag' => $_POST['age'],
        ':wizard_id' => $_POST['wizard_id']));
    $_SESSION['success'] = 'Record edited';
    header( 'Location: index.php' ) ;
    return;
}

// Guardian: Make sure that wizard_id is present
if ( ! isset($_GET['wizard_id']) ) {
  $_SESSION['error'] = "Missing wizard_id";
  header('Location: index.php');
  return;
}

$stmt = $pdo->prepare("SELECT * FROM wizard where wizard_id = :xyz");
$stmt->execute(array(":xyz" => $_GET['wizard_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ( $row === false ) {
    $_SESSION['error'] = 'Bad value for wizard_id';
    header( 'Location: index.php' ) ;
    return;
}

// Flash pattern
if ( isset($_SESSION['error']) ) {
    echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
    unset($_SESSION['error']);
}

$nm= htmlentities($row['name']);
$em = htmlentities($row['email']);
$hs = htmlentities($row['house']);
$wd = htmlentities($row['wand']);
$ag = htmlentities($row['age']);
$wizard_id = $row['wizard_id'];
?>
<h1>Editing Wizard Registry</h1>
<form method="post">
<p>Name:
<input type="text" name="name" value="<?= $nm ?>"></p>
<p>Email:
<input type="text" name="email" value="<?= $em ?>"></p>
<p>House:
<input type="text" name="house" value="<?= $hs ?>"></p>
<p>Wand:
<input type="text" name="wand" value="<?= $wd ?>"></p>
<p>Age:
<input type="text" name="age" value="<?= $ag ?>"></p>

<input type="hidden" name="wizard_id" value="<?= $wizard_id ?>">

<input type="submit" value="Save"/>
<input type="submit" value="Cancel"/></p>
</form>
