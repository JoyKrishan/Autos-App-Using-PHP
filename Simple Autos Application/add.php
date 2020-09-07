<?php
  require_once "pdo.php";
  session_start();
  if( ! isset($_SESSION['name'])){
    die("ACCESS DENIED");
  }
  if( isset($_POST['cancel'])){
    header("Location:main.php");
    return;
  }
  if  ( isset($_POST['make']) && isset($_POST['model']) && isset($_POST['mileage']) && isset($_POST['year']) ){
    if ( strlen($_POST['make'])<1 || strlen($_POST['model'])<1 || strlen($_POST['mileage'])<1 || strlen($_POST[year])<1 ){
      $_SESSION['error']='All fields are required';
      header("Location:add.php");
      return;
      }
      else if ( (! is_numeric($_POST['mileage']) ) && ( ! is_numeric($_POST['year']) ) ){
        $_SESSION['error']='Year must be an integer';
        header("Location:add.php");
        return;
      }
      else {
        $sql="INSERT INTO autos (make, model, mileage, year) VALUES (:make, :model, :mileage, :year)";
        $stmt=$pdo->prepare($sql);
        $stmt->execute(array(':make' => $_POST['make'] , ':model' => $_POST['model'] ,
                              ':mileage' => $_POST['mileage'], ':year' => $_POST['year']) ) ;
        $_SESSION['success']="Record added";
        header("Location:main.php");
        return;
      }
  }
  ?>

<!--view-->
<html>
<head>
  <title>Joy Krishan Das</title>
  <?php   require_once "bootstrap.php"; ?>
</head>

<body style="margin-left:5%">
  <h1>Tracking Automobiles for <?= $_SESSION['name']?></h1>
  <p><?php if( isset ($_SESSION['error']) ) {
            echo "<p style='color:red'>".$_SESSION['error']."</p>";
            unset($_SESSION['error']);
          }
        ?></p>

  <form method="post">
  <p>Make:
    <label for="id1"/>
    <input type="text" name="make"/>
  </p>
  <p>Model:
    <label for="id4"/>
    <input type="text" name="model"/>
  </p>
  <p>Mileage:
    <label for="id2"/>
    <input type="text" name="mileage"/>
  </p>
  <p>Year:
    <label for="id3"/>
    <input type="text" name="year"/>
  </p>
  <input type="submit" value="Add" />
  <input type="submit" value="Cancel" name="cancel"/>
</form>

</body>
</head>
</html>
