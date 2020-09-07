<?php
//Function to generate flashmessages
function flashmessage(){
  if ( isset($_SESSION['error'])){
    echo "<p style='color:red'>".$_SESSION['error']."</p>";
    unset($_SESSION['error']);
  }
  if (isset($_SESSION['success'])){
    echo "<p style='color:green'>".$_SESSION['success']."</p>";
    unset($_SESSION['success']);
  }
}
//Function to validate all description and year
function validatePos(){
  for ($i=0; $i<9; $i++){
    if( isset($_POST['year'.$i]) && isset($_POST['desc'.$i]) ) {
      if ( strlen ($_POST['year'.$i])<1 || strlen($_POST['desc'.$i])<1 ){
        $_SESSION['error']='All fields are required';
        return false;
      }
      else if( ! is_numeric($_POST['year'.$i])) {
        $_SESSION['error']="Year must be numeric";
        return false;
      }
    }
  }
  return true;
  }

function validateEdu(){
  for ($i=0; $i<9; $i++){
    if( !isset($_POST['edu_year'.$i]) ) continue;
    if( !isset($_POST['edu_school'.$i]) ) continue;
    $year=$_POST['edu_year'.$i];
    $school=$_POST['edu_school'.$i];
    if( strlen($year)<1 || strlen($school)<1 ){
      return 'All field are required';
    }
    else if( ! is_numeric($year)){
      return 'Year must be numeric';
    }
  }
  return false;
}

 ?>
