<?php
  require_once "pdo.php";
  session_start();
  if (! isset($_SESSION['name']) ){
    die('ACCESS DENIED');
  }
  if ( isset($_POST['profile_id']) && isset($_POST['delete']) ){
    $sql="DELETE FROM profile WHERE profile_id=:xyz";
    $stmt=$pdo->prepare($sql);
    $stmt->execute(array(":xyz" => $_GET['profile_id']));
    $_SESSION['success']="Record deleted";
    header("Location:index.php");
    return;
  }
?>
<html>
<head>
<title>Joy Krishan Das </title>
<?php require_once "bootstrap.php";?>
</head>
<body style="margin-left:5%">
  <h1>Deleting Profile</h1>
    <?php
     $sql="SELECT * FROM profile WHERE profile_id=:xyz";
     $stmt=$pdo->prepare($sql);
     $stmt->execute(array(':xyz' => $_GET['profile_id']));
     $row=$stmt->fetch(PDO::FETCH_ASSOC);
     if ($row === false){
       $_SESSION['error']="Could not find profile";
       header("Location:index.php");
       return;
     }
     $fname=$row['first_name'];
     $lname=$row['last_name'];
     echo "<p>First Name: ".htmlentities($fname)."</p>";
     echo "<p>Last Name: ".htmlentities($lname)."</p>";
     ?>
   <p>
     <form method="post">
       <input type="hidden" name="profile_id" value="<?=htmlentities($row['profile_id'])?>"/>
       <input type="submit" value="Delete" name="delete"/>
       <a href="index.php"> Cancel</a>
     </form>
   </p>

</body>
</html>
