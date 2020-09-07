<?php
  session_start();
  ?>
<html>
<head>
<title>Joy Krishan Das</title>
<?php   require_once "bootstrap.php"; ?>
</head>
<body style="margin-left:5%">
  <h1>Profile Information</h1>
  <?php require_once "pdo.php";
        $sql= 'SELECT * FROM profile WHERE profile_id=:xyz';
        $stmt=$pdo->prepare($sql);
        $stmt->execute(array( ':xyz'=> $_GET['profile_id']));
        $row=$stmt->fetch(PDO::FETCH_ASSOC);
        if( $row === false){
          $_SESSION['error']='Could not load profile';
          header("Location:index.php");
          return;
        }
        $id=$row['profile_id'];
        $fname=$row['first_name'];
        $lname=$row['last_name'];
        $email=$row['email'];
        $headline=$row['headline'];
        $summary=$row['summary'];
        echo "<p>First Name:  ".htmlentities($fname)."</p>";
        echo "<p>Last Name:  ".htmlentities($lname)."</p>";
        echo "<p>Email:  ".htmlentities($email)."</p>";
        echo "<p>Headline:  ".htmlentities($headline)."</p>";
        echo "<p>Summary:  ".htmlentities($summary)."</p>";

        //for education
        $sql_edu="SELECT institution.name, year FROM education JOIN institution ON education.institution_id=institution.institution_id WHERE profile_id=:pro_id";
        $stmt_edu=$pdo->prepare($sql_edu);
        $stmt_edu->execute(array('pro_id'=>$id));
        $row_edu=$stmt_edu->fetch(PDO::FETCH_ASSOC);
        if($row_edu!==false){
          echo "<p>Education <ul>";
          $stmt_edu->execute(array('pro_id'=>$id));
          while($row_edu=$stmt_edu->fetch(PDO::FETCH_ASSOC)){
            echo "<li>".$row_edu['year'].":".$row_edu['name']."</li>";
          }
          echo "</ul>";
        }

        //for position
        $sql="SELECT * FROM position WHERE profile_id=:pro_id";
        $stmt=$pdo->prepare($sql);
        $stmt->execute(array(":pro_id"=>$id));
        $row_check=$stmt->fetch(PDO::FETCH_ASSOC);
        if($row_check!==false){
          echo "<p> Position <ul>";
          $stmt->execute(array(":pro_id"=>$id));
          while ($row=$stmt->fetch(PDO::FETCH_ASSOC)){
              echo "<li>".$row['year'].":".$row['description']."</li>";
        }
        echo "</ul>";
      }
        ?>

        <a href="index.php">Done</a>

</body>
</html>
