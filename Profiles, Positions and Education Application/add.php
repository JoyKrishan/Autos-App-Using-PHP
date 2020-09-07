<?php
  session_start();
  require_once 'pdo.php';
  require_once "utility.php";
  if ( ! isset($_SESSION['user_id'])) {
    die("Not Logged In");
  }
  if ( isset($_POST['cancel'])){
    header("Location:index.php");
    return;
  }
  if ( isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['email']) && isset($_POST['headline']) && isset($_POST['summary'])){
    if ( strlen($_POST['first_name'])<1 && strlen($_POST['last_name'])<1 && strlen($_POST['email'])<1
          && strlen($_POST['headline'])<1 && strlen($_POST['summary'])<1 ){
            $_SESSION['error']='All fields are required';
            header("Location:add.php");
            return;
          }
    else if ( strpos($_POST['email'], '@' ) === false ){
      $_SESSION['error']='Email address must contain @';
      header("Location:add.php");
      return;
    }
    else {
      $validation=validatePos();
      if ($validation===false){
        header("Location:add.php");
        return;
      }
      $validation_2=validateEdu();
      if( is_string($validation_2)){
        $_SESSION['error']=$validation_2;
        header("Location:add.php");
        return;
      }

      $sql='INSERT INTO profile (user_id, first_name, last_name, email, headline, summary) VALUES (:id, :fname, :lname, :mail, :head, :summary)';
      $stmt=$pdo->prepare($sql);
      $stmt->execute(array( ':id' => $_SESSION['user_id'],
                            ':fname' => $_POST['first_name'],
                            ':lname'=> $_POST['last_name'],
                            ':mail'=> $_POST['email'],
                            ':head'=> $_POST['headline'],
                            ':summary'=>$_POST['summary']));
      $profile_id=$pdo->lastInsertId();

      $rank=1;
      for ($i=0; $i<9; $i++) {
          if( ! isset($_POST['year'.$i] ) && ! isset($_POST['desc'.$i]) ) {
            continue;
          }
          $year=$_POST['year'.$i];
          $desc=$_POST['desc'.$i];
          $sql='INSERT INTO position (profile_id, rank ,year, description) VALUES (:pro_id , :rk, :yr, :des)';
          $stmt=$pdo->prepare($sql);
          $stmt->execute(array(':pro_id'=>$profile_id,
                                ':rk' => $rank,
                                ':yr' => $year,
                                ':des' => $desc ));
          $rank++;
          }

        //INSERTING Institution
        $rank=1;
        for ($i=0; $i<9; $i++){
          if( ! isset($_POST['edu_year'.$i] ) && ! isset($_POST['edu_school'.$i]) ) {
            continue;
          }
          $year=$_POST['edu_year'.$i];
          $school=$_POST['edu_school'.$i];
          $sql='SELECT * FROM Institution WHERE name LIKE :prefix';
          $stmt=$pdo->prepare($sql);
          $stmt->execute(array(':prefix'=> "%".$school."%"));
          $row=$stmt->fetch(PDO::FETCH_ASSOC);
          if($row===false){
            $stmt=$pdo->prepare('INSERT INTO Institution (name) VALUES (:school)');
            $stmt->execute(array(':school'=> $school));
            $institute_id=$pdo->lastInsertId();
          }else{
            $institute_id=$row['institution_id'];
          }
          $sql='INSERT INTO education (profile_id, institution_id, rank, year) VALUES (:proid, :ins_id, :rank, :year )';
          $stmt=$pdo->prepare($sql);
          $stmt->execute(array( ':proid'=> $profile_id,
                                ':ins_id'=> $institute_id,
                                ':rank'=>$rank,
                                ':year'=>$year));
                              }
      //Inserting into education table

        $_SESSION['success']='Profile Added';
        header("Location:index.php");
        return;
    }
  }

?>
<!--view-->
<html>
<head>
  <title>Joy Krishan Das</title>
  <?php   require_once "bootstrap.php";
          ?>

</head>

<body style="margin-left:5%">
  <h1>Adding Profile for <?= $_SESSION['name']?></h1>

  <?php flashmessage(); ?>


  <form method="post">
  <p>First Name:
    <input type="text" name="first_name" size='60'/>
  </p>
  <p>Last Name:
    <input type="text" name="last_name" size='60'/>
  </p>
  <p>Email:
    <input type="text" name="email" size='60'/>
  </p>
  <p>Headline:
    <input type="text" name="headline" size='80'/>
  </p>
  <p>Summary:</br>
    <textarea name="summary" cols="80" rows="8"></textarea>
  </p>
  <p>Education: <input id="addedu" type="submit" value="+"/></p>
  <div id="education_fields"></div>
  <p>Position: <input id="addpos" type="submit" value="+" /></p>
  <div id="position_fields"></div>
  <input type="submit" value="Add" />
  <input type="submit" value="Cancel" name="cancel"/></br>
</form>

<script type="text/javascript">
count=0;
$("#addpos").click(function(event){
  console.log("Position Clicked");
  event.preventDefault();
  if( count>=9){
    alert("Maximum of nine position entries exceeded");
    return;
  }
  console.log("Adding Position");
  $('#position_fields').append(
            '<div id="position'+count+'"> \
            <p>Year: <input type="text" name="year'+count+'" value="" /> \
            <input type="button" value="-" \
                onclick="$(\'#position'+count+'\').remove();return false;"></p> \
            <textarea name="desc'+count+'" rows="8" cols="80"></textarea>\
            </div>');
  count++;
});
edu_count=0;
$("#addedu").click(function(event){
  console.log("Education Clicked");
  event.preventDefault();
  if (edu_count>=9){
    alert('Maximum nine Education entries exceeded');
  }
  console.log("Adding Education");
  $("#education_fields").append(
    '<div id="edu'+edu_count+'"> \
    <p>Year: <input type="text" name="edu_year'+edu_count+'" value="" /> \
    <input type="button" value="-" \
        onclick="$(\'#edu'+edu_count+'\').remove();return false;"></p> \
    <p>School: <input type="text" size="80" name="edu_school'+edu_count+'" class="school" value="" /> \
    </div>');
    edu_count++;
});
$(".school").autocomplete({source:'school.php'});


</script>

</body>
</head>
</html>
