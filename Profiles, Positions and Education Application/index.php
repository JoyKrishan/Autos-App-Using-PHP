<?php
  session_start();
  ?>

<html>
<head>
  <title>Joy Krishan Das</title>
  <?php require_once "bootstrap.php";
        require_once "utility.php"; ?>
</head>
<body style="margin-left:5%">
  <h1>Joy Krishan's Resume Registry</h1>
  <?php
  if (! isset($_SESSION['user_id'])){
  echo "<p><a href='login.php'>Please log in</a></p>";

  flashmessage();

  require_once "pdo.php";
  $sql="SELECT * from profile";
  $stmt=$pdo->query($sql);
  $row_check=$stmt->fetch(PDO::FETCH_ASSOC);
  if ($row_check !== false){
    echo "<table border='2'> <tr style='font-weight:bold'> <td>Name</td> <td>Headline</td></tr>";
    $stmt=$pdo->query($sql);
    while ($row=$stmt->fetch(PDO::FETCH_ASSOC)) {
      echo "<tr><td>"."<a href='view.php?profile_id=".$row['profile_id']."'>".htmlentities($row['first_name'])."</td><td>".htmlentities($row['headline'])."</td></tr>";
  }
  echo "</table>";
}
  else{
    echo "<p> No Profile Found </p>";
    echo "<p><a href='add.php'>Add New Entry</a></p>";
  }
}
// WHEN YOU ARE LOGGED IN
else {
  echo "<p><a href='logout.php'/>Logout</a></p>";
  flashmessage(); //Flash message function defined in the utility.php

  require_once "pdo.php";
  $sql="SELECT * from profile";
  $stmt=$pdo->query($sql);
  $row_check=$stmt->fetch(PDO::FETCH_ASSOC);
  if ($row_check !== false){
    echo "<table border='2'> <tr style='font-weight:bold'> <td>Name</td> <td>Headline</td>  <td>Action</td> </tr>";
    $stmt=$pdo->query($sql);
    while ($row=$stmt->fetch(PDO::FETCH_ASSOC)) {
      echo "<tr><td>"."<a href='view.php?profile_id=".$row['profile_id']."'>".$row['first_name']."</a></td><td>".$row['headline']."</td>";
      echo "<td>"."<a href='edit.php?profile_id=".$row['profile_id']."'>Edit</a> | ";
      echo "<a href='delete.php?profile_id=".$row['profile_id']."'>Delete</a></td>";
  }
  echo "</table>"."\n";

  echo "<p><a href='add.php'>Add New Entry</a></p>";
}
  else{
    echo "<p> No Profile Found </p>";
    echo "<p><a href='add.php'>Add New Entry</a></p>";
  }


}
  ?>
</body>
</html>
