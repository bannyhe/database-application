<?php
require_once "pdo.php";
require_once "util.php";

session_start();

if ( isset($_POST['add'])) {
  if ( strlen($_POST['first_name']) === 0 || strlen($_POST['last_name']) === 0
  || strlen($_POST['email']) === 0 || strlen($_POST['headline']) === 0 || strlen($_POST['summary'] === 0 )) {
    $_SESSION['error'] = "All fields are required";
  }elseif(!is_numeric($_POST['edu_year'])) {
    $_SESSION['error'] = "Year must be numeric";
  }

  $msg = validateProfile();
  if ( is_string($msg) ) {
    $_SESSION['error'] = $msg;
    header("Location: add.php");
    return;
  }
  $msg = validatePos();
  if ( is_string($msg) ) {
    $_SESSION['error'] = $msg;
    header("Location: add.php");
    return;
  }
  $msg = validateEdu();
  if ( is_string($msg) ) {
    $_SESSION['error'] = $msg;
    header("Location: add.php");
    return;
  }

//Data is valid - time to insert
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
  $profile_id = $pdo->lastInsertId();

// Insert the position entries
  $rank = 1;
  for($i=1; $i<=9; $i++) {
    if ( ! isset($_POST['year'.$i]) ) continue;
    if ( ! isset($_POST['desc'.$i]) ) continue;
    $year = $_POST['year'.$i];
    $desc = $_POST['desc'.$i];

    $stmt = $pdo->prepare('INSERT INTO Position
      (profile_id, rank, year, description)
      VALUES ( :pid, :rank, :year, :desc )');
    $stmt->execute(array(
      ':pid' => $profile_id,
      ':rank' => $rank,
      ':year' => $year,
      ':desc' => $desc));
    $rank++;
  }
  // Insert the position entries
    $rank = 1;
    for($i=1; $i<=9; $i++) {
      if ( ! isset($_POST['edu_year'.$i]) ) continue;
      if ( ! isset($_POST['edu_school'.$i]) ) continue;
      $year = $_POST['edu_year'.$i];
      $school = $_POST['edu_school'.$i];

      $institution_id = false;
      $stmt = $pdo->prepare('SELECT institution_id
        FROM Institution WHERE name = :name');
      $stmt->execute(array(
        ':name' => $school));
      $profile = $stmt->fetch(PDO::FETCH_ASSOC);
      if ($profile !== false)
        $institution_id = $profile['institution_id'];
      if ($institution_id === false) {
        $stmt = $pdo->prepare('INSERT INTO Institution (name) VALUES (:name)');
        $stmt->execute(array(':name'=>$school));
        $institution_id = $pdo->lastInsertId();
      }
      $stmt = $pdo->prepare('INSERT INTO Education (profile_id, rank, year, institution_id) VALUES (
        :pid, :rank, :year, :iid)');
      $stmt->execute(array(
        ':pid' => $profile_id,
        ':rank' => $rank,
        ':year' => $year,
        ':iid' => $institution_id)
      );
      $rank++;
    }

    $_SESSION['success'] = "Profile Added";
    header("Location: index.php");
    return;
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Banny's Profile Add</title>
<meta http-equiv="X-UA-Compatible" content="IE=9; IE=8; IE=7; IE=EDGE"/>
<meta charset="UTF-8">
<?php require_once "head.php"; ?>
</head>
<body>
<div class="container">
<h1>Adding Profile for UMSI</h1>
<?php flashMesssages(); ?>

<form method="post">
  <p>First Name:
    <input type="text" name="first_name" size="60"></p>
  <p>Last Name:
    <input type="text" name="last_name" size="60"></p>
  <p>Email:
    <input type="text" name="email" size="30"></p>
  <p>Headline:<br/>
    <input type="text" name="headline" size="90"></p>
  <p>Summary:<br/>
    <textarea name="summary" rows="8" cols="90"></textarea></p>
  <p>Education:
    <input type="button" id="addEdu" name="addEdu" value=" + ">
    <div id="edu_fields"></div></p>
  <p>Position:
    <input type="button" id="addPos" name="addPos" value=" + ">
    <div id="position_fields"></div></p>
  <p><input type="submit" name="add" value="Add"></p>
  <p><input type="button" name="cancel" value="Cancel" onclick="location.href='index.php'"></p>
</form>
<script type="text/javascript">
  $(document).ready(function(){
    window.console && console.log('Document ready called');
    $('#addPos').click(function(event){
      countPos = 0;
      event.preventDefault();
      if (countPos >= 9) {
        alert("Maximum of nine position entries exceeded");
        return;
      }
      countPos++;
      window.console && console.log('Adding position '+countPos);
      $('#position_fields').append(
        '<div id="position'+countPos+'"> \
        <p>Year: <input type="text" name="year'+countPos+'" value="" /> \
        <input type="button" value="-" \
        onclick="$(\'#position'+countPos+'\').remove();return false;"></p> \
        <textarea name="desc'+countPos+'" rows="8" cols="80"></textarea>\
        </div>');
      });
      $('#addEdu').click(function(event){
        countEdu = 0;
        event.preventDefault();
        if(countEdu >= 9){
          alert("Maximum of nine education entries exceeded");
          return;
        }
        countPos++;
        window.console && console.log('Adding education '+countEdu);
        var source = $("#edu-template").html();
        $('#edu_fields').append(source.replace(/@COUNT@/g, countEdu));
        $('.school').autocomplete({
          source: "school.php"
        });
      });
      $('.school').autocomplete({
        source: "school.php"
    });
  });
</script>
<script id="edu-template" type="text">
    <div id="edu@COUNT@">
    <p>Year: <input type="text" name="edu_year@COUNT@" value="" />
    <input type="button" value="-" onclick="$('#edu@COUNT@').remove();return false;"><br>
    <p>School: <input type="text" size="80" name="edu_school@COUNT@" class="school" value="" />
    </p>
    </div>
</script>
</body>
</html>
