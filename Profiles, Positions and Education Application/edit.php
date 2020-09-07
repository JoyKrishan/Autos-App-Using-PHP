<?php
  session_start();
  require_once "pdo.php";
  require_once "utility.php";

  if (! isset($_SESSION['user_id'])){
    die("Not logged in");
  }
  if( isset($_POST['cancel'])){
    header("Location:index.php");
    return;
  }
  if ( isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['email']) && isset($_POST['headline']) && isset($_POST['summary']) && isset($_POST['profile']) ){
    if ( strlen($_POST['first_name'])<1 && strlen($_POST['last_name'])<1 && strlen($_POST['email'])<1
          && strlen($_POST['headline'])<1 && strlen($_POST['summary'])<1 ){
            $_SESSION['error']='All fields are required';
            header("Location:edit.php?profile_id=".$_REQUEST['profile']);
            return;
          }
    else if ( strpos($_POST['email'], '@' ) === false ){
      $_SESSION['error']='Email address must contain @';
      header("Location:edit.php?profile_id=".$_REQUEST['profile']);
      return;
    }
    else {
      $validation=validatePos();
      if ($validation===false){
        header("Location:edit.php?profile_id=".$_REQUEST['profile']);
        return;
      }
      $validation_2=validateEdu();
      if( is_string($validation_2)){
        $_SESSION['error']=$validation_2;
        header("Location:edit.php?profile_id=".$_REQUEST['profile']);
        return;
      }
      $sql='UPDATE profile SET first_name=:fname , last_name=:lname, email=:mail, headline=:head, summary=:summary WHERE profile_id=:proid';
      $stmt=$pdo->prepare($sql);
      $stmt->execute(array( ':proid' => $_POST['profile'],
                            ':fname' => $_POST['first_name'],
                            ':lname'=> $_POST['last_name'],
                            ':mail'=> $_POST['email'],
                            ':head'=> $_POST['headline'],
                            ':summary'=>$_POST['summary'])
                          );
      $profile_id=$_REQUEST['profile'];
      echo $profile_id;

      $stmt = $pdo->prepare('DELETE FROM Position WHERE profile_id=:pid');
      $stmt->execute(array( ':pid' => $profile_id));

     // Insert the position entries
     $rank=1;
     for ($i=0; $i<9; $i++) {
         if( ! isset($_POST['year'.$i] ) && ! isset($_POST['desc'.$i]) ) {
           continue;
         }
         $year=$_POST['year'.$i];
         $desc=$_POST['desc'.$i];
         $sql='INSERT INTO position (profile_id, rank ,year, description) VALUES (:pro_id , :rk, :yr, :des)';
         $stmt=$pdo->prepare($sql);
         $stmt->execute(array(':pro_id'=>$_REQUEST['profile'],
                               ':rk' => $rank,
                               ':yr' => $year,
                               ':des' => $desc ));
         $rank++;
         }

         //Deleting from the Education table
         $stmt=$pdo->prepare('DELETE FROM education WHERE profile_id=:pid');
         $stmt->execute(array(':pid'=> $profile_id));

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
      }


      $_SESSION['success']='Profile Edited';
      header("Location:index.php");
      return;
    }
    ?>

  <html>
  <head>
  <title>Joy Krishan Das</title>
  <?php require_once "bootstrap.php";
        require_once "utility.php" ?>
  </head>
  <body style="margin-left:5%">
    <h1>Editing Automobile for <?=$_SESSION['name']?></h1>
    <?php
    flashmessage();

    $sql="SELECT * FROM profile WHERE profile_id=:xyz";
    $stmt=$pdo->prepare($sql);
    $stmt->execute(array(':xyz' => $_GET['profile_id']));
    $row=$stmt->fetch(PDO::FETCH_ASSOC);
    if($row===false){
      $_SESSION['error']="Could not find profile";
      header("Location:index.php");
      return;
    }
    //echo "Nono";
    //print_r($row);
    $id=$row['profile_id'];
    $fname=$row['first_name'];
    $lname=$row['last_name'];
    $email=$row['email'];
    $headline=$row['headline'];
    $summary=$row['summary'];
    ?>
    <form method="post">
    <p>First Name:
      <input type="text" name="first_name" value="<?=htmlentities($fname)?>" size='60'/>
    </p>
    <p>Last Name:
      <input type="text" name="last_name" value="<?=htmlentities($lname)?>" size='60'/>
    </p>
    <p>Email:
      <input type="text" name="email" value="<?=htmlentities($email)?>" size='60'/>
    </p>
    <p>Headline:
      <input type="text" name="headline" value="<?=htmlentities($headline)?>" size='80'/>
    </p>
    <p>Summary:</br>
      <textarea name="summary" cols="80" rows="8" ><?=htmlentities($summary)?></textarea>
    </p>
    <input type="hidden" name="profile" value="<?=htmlentities($id)?>"/>

    <p>Education: <input id="addedu" type="submit" value="+"/></p>
    <div id="education_fields"></div>
    <?php
     $sql="SELECT institution.name, year FROM education JOIN institution ON education.institution_id=institution.institution_id WHERE profile_id=:pro_id";
     $stmt=$pdo->prepare($sql);
     $stmt->execute(array( ':pro_id'=>$id ));
     $educount=0;
     while($row=$stmt->fetch(PDO::FETCH_ASSOC)){

       echo "<div id='edu".$educount."'>
             <p> Year: <input type='text' name='edu_year".$educount."' value=".htmlentities($row['year']).">
             <input type='button' value='-' onclick='$(#edu$educount).remove(); return false;'>"."</p>"."\n";
       echo "<p>School: <input type='text' size='80'  name='edu_school$educount' class='school' value='".htmlentities($row['name'])."'/></div>
             </textarea></div>"."\n";
       echo "</br>";
       $educount++;
     }
    ?>

    <p>Position: <input id="addpos" type="submit" value="+" /></p>
    <!--FOR THE Position fields-->
    <div id="position_fields"></div>
    <?php
    $sql="SELECT * FROM position WHERE profile_id=:pro_id";
    $stmt=$pdo->prepare($sql);
    $stmt->execute(array(":pro_id"=>$id));
    $count=0;
    while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
      echo "<div id='position".$count."'>
            <p> Year: <input type='text' name='year".$count."' value=".htmlentities($row['year']).">
            <input type='button' value='-' onclick='$(#postion$count).remove(); return false;'>"."</p>"."\n";
      echo "<textarea name='desc$count' rows='8' cols='80'>".htmlentities($row['description'])."
            </textarea></div>"."\n";
      echo "</br>";
      $count++;
    } ?>
    <input type="submit" value="Save"  />
    <input type="submit" value="Cancel" name="cancel"/>
  </form>
<script type="text/javascript">
  count=<?=$count?>;
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

edu_count=<?=$educount?>;
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
</html>
