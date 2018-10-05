<?php
require_once "pdo.php";
session_start();

if ( isset($_SESSION['edit_error']) ) {
    echo '<p style="color:red">'.$_SESSION['edit_error']."</p>\n";
    unset($_SESSION['edit_error']);
}

if ( isset($_POST['make']) && isset($_POST['year']) && isset($_POST['mileage']) && isset($_POST['auto_id'])) {
    if (strlen($_POST['make']) === 0) {
        $_SESSION['edit_error'] = "All fields are required";
        header("Location: edit.php?auto_id=".$_REQUEST['auto_id']);
        return;
    }
    elseif (!is_numeric($_POST['year']) || !is_numeric($_POST['mileage'])){
        $_SESSION['edit_error'] = "Mileage and year must be numeric";
        header("Location: edit.php?auto_id=".$_REQUEST['auto_id']);
        return;
    }
    else {
        $sql = "UPDATE autos SET make = :make, year = :year, mileage = :mileage
            WHERE auto_id = :auto_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(
            ':make' => $_POST['make'],
            ':year' => $_POST['year'],
            ':mileage' => $_POST['mileage'],
            ':auto_id' => $_POST['auto_id']));
        $_SESSION['success'] = 'Record edited';
        header('Location: index.php');
        return;
    }
}

$stmt = $pdo->prepare("SELECT * FROM autos where auto_id = :xyz");
$stmt->execute(array(":xyz" => $_GET['auto_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ( $row === false ) {
    $_SESSION['error'] = 'Bad value for auto_id';
    header( 'Location: index.php' ) ;
    return;
}

$m = htmlentities($row['make']);
$y = htmlentities($row['year']);
$mi = htmlentities($row['mileage']);
$auto_id = $row['auto_id'];
?>
<p>Editing Automobile</p>
<form method="post">
    <p>Make:
        <input type="text" name="make" value="<?= $m ?>"></p>
    <p>Year:
        <input type="text" name="year" value="<?= $y ?>"></p>
    <p>Mileage:
        <input type="text" name="mileage" value="<?= $mi ?>"></p>
    <input type="hidden" name="auto_id" value="<?= $auto_id ?>">
    <p><input type="submit" value="Save"/>
        <a href="index.php">Cancel</a></p>
</form>
