<?php
  require_once "pdo.php";
  $term=$_GET['term'];
  $sql="SELECT name FROM Institution WHERE name LIKE :prefix";
  $stmt=$pdo->prepare($sql);
  $stmt->execute(array( ':prefix'=> $term.'%')) ;
  $name=array();
  while ( $row=$stmt->fetch(PDO::FETCH_ASSOC)){
    $name[]=$row['name'];
  };

echo json_encode($name , JSON_PRETTY_PRINT);

  ?>
