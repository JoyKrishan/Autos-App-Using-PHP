<?php
  session_start();
  if( ! isset($_SESSION['name'])){
    die('ACCESS DENIED');
  }
  ?>

<!--view-->

<html>
<head>
  <title>Joy Krishan Das</title>
  <?php require_once "bootstrap.php"; ?>
</head>
<body style="margin-left:5%">
  <h1>Welcome to the Automobiles Database</h1>
  <?php if (isset($_SESSION['success'])){
    echo "<p style='color:green'>".$_SESSION['success']."</p>";
    unset($_SESSION['success']);
  }
  else if (isset($_SESSION['error'])){
    echo "<p style='color:red'>".$_SESSION['error']."</p>";
    unset($_SESSION['error']);
  }
  ?>
  <p>
    <?php
    require_once "pdo.php";
    $sql="SELECT * FROM autos";
    $stmt=$pdo->query($sql);
    $row_check=$stmt->fetch(PDO::FETCH_ASSOC);
    if ($row_check === false){
      echo "<p style='font-weight:bold'> No rows found </p>";
    }
    else{
      $stmt=$pdo->query($sql);
      echo "<table border='2'><tr style='font-weight:bold'> <td>Make</td> <td>Model</td> <td>Year</td> <td>Mileage</td> <td>Action</td> </tr>";
      while( $row=$stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr> <td>".htmlentities($row['make'])."</td>
                    <td>".htmlentities($row['model'])."</td>
                    <td>".htmlentities($row['year'])."</td>
                    <td>".htmlentities($row['mileage'])."</td>
                    <td> <a href='edit.php?autos_id="."$row[autos_id]'".">Edit</a> /
                    <a href='delete.php?autos_id="."$row[autos_id]'".">Delete</a> </td> </tr>";}
      echo "</table>";
    }
      ?>
      <p><a href="add.php">Add New Entry</a></p>
      <p><a href="logout.php">Logout</a></p>
  </p>

</body>
</html>
