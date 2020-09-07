<?php
  session_start();
  require_once "pdo.php";
  if (! isset($_SESSION['name'])){
    die("ACCESS DENIED");
  }
  if  ( isset($_POST['make']) && isset($_POST['model']) && isset($_POST['mileage']) && isset($_POST['year']) && isset($_POST['autos_id']) ){
    if ( strlen($_POST['make'])<1 || strlen($_POST['model'])<1 || strlen($_POST['mileage'])<1 || strlen($_POST[year])<1 ){
      $_SESSION['error']='All fields are required';
      header("Location:edit.php?autos_id=".$_REQUEST['autos_id']);
      return;
      }
      else if ( !is_numeric($_POST['mileage'])  || !is_numeric($_POST['year'])  ){
        $_SESSION['error']='Year must be an integer';
        header("Location:edit.php?autos_id=".$_REQUEST['autos_id']);
        return;
      }
      else {
        $sql="UPDATE autos SET make= :make, model= :model, mileage=:mileage, year=:year WHERE autos_id=:autos_id";
        $stmt=$pdo->prepare($sql);
        $stmt->execute(array(':make' => $_POST['make'] , ':model' => $_POST['model'] ,
                              ':mileage' => $_POST['mileage'], ':year' => $_POST['year'],
                              ':autos_id' => $_POST['autos_id'] ) ) ;
        $_SESSION['success']="Record edited";
        header("Location:main.php");
        return;
      }
  }
  ?>

<html>
<title>Joy Krishan Das</title>
<?php require_once "bootstrap.php" ?>
<body style="margin-left:5%">
  <h1>Editing Automobile</h1>
  <p style="color:red"><?php $error=isset($_SESSION['error'])? $_SESSION['error']:false;
                              echo $error;
                              unset($_SESSION['error']);?></p>
  <?php
  $sql="SELECT * FROM autos WHERE autos_id=:xyz";
  $stmt=$pdo->prepare($sql);
  $stmt->execute(array(':xyz' => $_GET['autos_id']));
  $row=$stmt->fetch(PDO::FETCH_ASSOC);
  if($row===false){
    $_SESSION['error']="Bad value for autos_id";
    header("Location:edit.php");
    return;
  }
  //echo "Nono";
  //print_r($row);
  $id=$row['autos_id'];
  $make=$row['make'];
  $model=$row['model'];
  $mileage=$row['mileage'];
  $year=$row['year'];

  ?>
  <form method="post">
  <p>Make:
    <label for="id1"/>
    <input type="text" name="make" value="<?=htmlentities($make)?>"/>
  </p>
  <p>Model:
    <label for="id4"/>
    <input type="text" name="model" value="<?=htmlentities($model)?>"/>
  </p>
  <p>Mileage:
    <label for="id2"/>
    <input type="text" name="mileage" value="<?=htmlentities($mileage)?>"/>
  </p>
  <p>Year:
    <label for="id3"/>
    <input type="text" name="year" value="<?=htmlentities($year)?>"/>
  </p>
    <input type="hidden" name="autos_id" value="<?=htmlentities($id)?>"/>
  <input type="submit" value="Save" />
  <a href="main.php"> Cancel</a>
</body>
</html>
