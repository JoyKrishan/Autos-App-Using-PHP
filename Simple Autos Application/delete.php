<?php
  require_once "pdo.php";
  session_start();
  if (! isset($_SESSION['name']) ){
    die('ACCESS DENIED');
  }
  if ( isset($_POST['autos_id']) && isset($_POST['delete']) ){
    $sql="DELETE FROM autos WHERE autos_id=:xyz";
    $stmt=$pdo->prepare($sql);
    $stmt->execute(array(":xyz" => $_GET['autos_id']));
    $_SESSION['success']="Record deleted";
    header("Location:main.php");
    return;
  }
?>
<html>
<head>
<title>Joy Krishan Das </title>
<?php require_once "bootstrap.php";?>
</head>
<body>
  <h1>Confirm: Deleting
    <?php
     $sql="SELECT * FROM autos WHERE autos_id=:xyz";
     $stmt=$pdo->prepare($sql);
     $stmt->execute(array(':xyz' => $_GET['autos_id']));
     $row=$stmt->fetch(PDO::FETCH_ASSOC);
     if ($row === false){
       $_SESSION['error']="Bad value for autos_id";
       header("Location:main.php");
       return;
     }
     $make=$row['make'];
     echo htmlentities($make)."\n";
     ?>
   </h1>
   <p>
     <form method="post">
       <input type="hidden" name="autos_id" value="<?=htmlentities($row['autos_id'])?>"/>
       <input type="submit" value="Delete" name="delete"/>
       <a href="main.php"> Cancel</a>
     </form>
   </p>

</body>
</html>
