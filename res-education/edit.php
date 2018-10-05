<?php
// Make the database connection and leave it in the variable $pdo
require_once "pdo.php";
require_once "util.php";
session_start();

// If the user is not logged in, then redirect back to index.php with an error
if ( isset($_POST['cancel']) ) {
  header('Location: index.php');
  return;
}

if ( ! isset($_REQUEST['profile_id']) ) {
    $_SESSION['error'] = "Missing profile_id";
    header("Location: index.php");
    return;
  }

$stmt = $pdo->prepare('SELECT * FROM Profile WHERE
  profile_id = :prof AND user_id = :uid');
$stmt->execute(array(':prof'=>$_REQUEST['profile_id'],
':uid'=>$_SESSION['user_id']));
$profile = $stmt->fetch(PDO::FETCH_ASSOC);
if ($profile === false) {
  $_SESSION['error'] = "Could not load profile";
  header("Location: index.php");
  return;
}

if (isset($_POST['first_name']) && isset($_POST['first_name']) &&
isset($_POST['last_name']) && isset($_POST['email']) &&
isset($_POST['headline']) && isset($_POST['summary'])) {
  $msg = validateProfile();
  if (is_string($msg)) {
    $_SESSION['error'] = $msg;
    header("Location: edit.php?profile_id=".$_REQUEST['profile_id']);
    return;
  }
  $msg = validatePos();
  if (is_string($msg)) {
    $_SESSION['error'] = $msg;
    header("Location: edit.php?profile_id=".$_REQUEST['profile_id']);
    return;
  }

  $sql = "UPDATE Profile SET first_name = :fn,
    last_name = :lm, email = :em,
    headline = :he, summary=:su
    WHERE profile_id = :pid
    AND user_id = :uid";
  $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        ':fn' => $_POST['first_name'],
        ':lm' => $_POST['last_name'],
        ':em' => $_POST['email'],
        ':he' => $_POST['headline'],
        ':su' => $_POST['summary'],
        ':pid' => $_REQUEST['profile_id'],
        ':uid' => $_SESSION['user_id'])
      );
      // Clear out the old position entries
      $stmt = $pdo->prepare('DELETE FROM Position
        WHERE profile_id = :pid');
      $stmt->execute(array(':pid' => $_REQUEST['profile_id']));

      $rank = 1;
      for($i=1; $i<=9; $i++) {
        if ( ! isset($_POST['year'.$i]) ) continue;
        if ( ! isset($_POST['desc'.$i]) ) continue;
        $year = $_POST['year'.$i];
        $desc = $_POST['desc'.$i];

        $stmt = $pdo->prepare('INSERT INTO Position
        (profile_id, rank, year, description) VALUES (:pid,
        :rank, :year, :desc)');
        $stmt->execute(array(
          ':pid' => $_REQUEST['profile_id'],
          ':rank' => $rank,
          ':year' => $year,
          ':desc' => $desc)
        );
        $rank++;
      }
      // Clear out the old education entries
      $stmt = $pdo->prepare('DELETE FROM Education
      WHERE profile_id = :pid');
      $stmt->execute(array(':pid' => $_REQUEST['profile_id']));

      $rank = 1;
      for ($i=1; $i<=9; $i++) {
        if (! isset($_POST['edu_year'.$i]) ) continue;
        if (! isset($_POST['edu_school'.$i]) ) continue;
        $year = $_POST['edu_year'.$i];
        $school = $_POST['edu_school'.$i];

        $institution_id = false;
        $stmt = $pdo->prepare('SELECT institution_id FROM Institution WHERE name = :name');
        $stmt->execute(array(':name' => $school));
        $profile = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($profile !== false) $institution_id = $profile['institution_id'];

        if ($institution_id === false){
            $stmt = $pdo->prepare('INSERT INTO Institution (name) VALUES (:name)');
            $stmt->execute(array(':name'=>$school));
        $institution_id = $pdo->lastInsertId();
      }

      $stmt = $pdo->prepare('INSERT INTO Education(profile_id, rank, year, institution_id)
      VALUES (:pid, :rank, :year, :iid)');
      $stmt->execute(array(
        ':pid' => $_REQUEST['profile_id'],
        ':rank' => $rank,
        ':year' => $year,
        ':iid' => $institution_id)
      );
      $rank++;
    }
    $_SESSION['success'] = 'Profile Saved';
    header("Location: index.php");
    return;
}

// Load up the position and education rows
$positions = loadPos($pdo, $_REQUEST['profile_id']);
$schools = loadEdu($pdo, $_REQUEST['profile_id']);

$stmt = $pdo->prepare('SELECT * FROM Profile WHERE
  profile_id = :xyz');
$stmt->execute(array(':xyz'=>$_GET['profile_id']));
$profile = $stmt->fetch(PDO::FETCH_ASSOC);
if ( $profile === false ) {
  $_SESSION['error'] = 'Bad value for profile_id';
  header('Location: index.php');
  return;
}

$first = htmlentities($profile['first_name']);
$last = htmlentities($profile['last_name']);
$email = htmlentities($profile['email']);
$headline = htmlentities($profile['headline']);
$summary = htmlentities($profile['summary']);
$profile_id = htmlentities($profile['profile_id']);
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Banny's Resume Registry - Edit Profile</title>
    <?php require_once "head.php"; ?>
    <meta http-equiv="X-UA-Compatible" content="IE=9; IE=8; IE=7; IE=EDGE"/>
    <meta charset="utf-8">
  </head>
  <body>
    <div class="container">
    <h1>Editing Profile for UMSI</h1>
    <?php flashMesssages(); ?>
    <form method="post">
      <input type="hidden" name="profile_id" value="<?= $profile_id ?>"/>
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

<?php
    $countPos = 0;
    echo('<p>Position: <input type="button" id="addPos" name="addPos" value=" + ">'."\n");
    echo('<div id="position_fields">'."\n");
    if (count($positions) >= 0) {
      foreach ($positions as $position) {
        $countPos ++;
        echo('<div class="position" id="position'.$countPos.'">');
        echo('<p>Year: <input type="text" name="year'.$countPos.'" value="'.$position['year'].'"/>
        <input type="button" value="-" onclick="$(\'#position'.$countPos.'\').remove();return false;"></p>');
        echo('<textarea name="desc'.$countPos.'" rows="8" cols="80">'."\n");
        echo(htmlentities($position['description'])."\n");
        echo("\n</textarea>\n</div>\n");
      }
    }
    echo("</div></p>\n");

    $countEdu = 0;
    echo('<p>Education: <input type="button" id="addEdu" name="addEdu" value=" + ">'."\n");
    echo('<div id="edu_fields">'."\n");
    if (count($schools) >= 0) {
      foreach ($schools as $school) {
        $countEdu ++;
        echo('<div id="edu'.$countEdu.'">');
        echo '<p>Year: <input type="text" name="edu_year'.$countEdu.'" value="'.$school['year'].'"/>
        <input type="button" value="-" onclick="$(\'#edu'.$countEdu.'\').remove();return false;"></p>';
        echo('<p>School: <input type="text" size="80" name="edu_school'.$countEdu.'" class="school" value="'.htmlentities($school['name']).'"/></p>');
        echo ("\n</div>\n");
      }
    }
    echo("</div></p>\n");
?>

    <p><input type="submit" name="submit" value="Save"/>
    <input type="button" name="submit" value="Cancel" onclick="location.href='index.php'"/></p>
    </form>
    <script type="text/javascript">
    countPos = <?= $countPos ?>;
    countEdu = <?= $countEdu ?>;

      $(document).ready(function(){
        window.console && console.log('Document ready called');
        $('#addPos').click(function(event){
          event.preventDefault();
          if(countPos >= 9){
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
          event.preventDefault();
          if(countEdu >= 9) {
            alert("Maximum of nine education entries exceeded");
            return;
          }
          countEdu++;
          window.console && console.log('Adding education '+countEdu);

          // Grab some HTML with hot spots and insert into the DOM
          var source = $("#edu-template").html();
          $('#edu_fields').append(source.replace(/@COUNT@/g, countEdu));

          // Add the even handler to the new ones
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
