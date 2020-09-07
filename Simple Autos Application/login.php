<?php
  session_start();
  $stored_hash='218140990315bb39d948a523d61549b4';
  if( isset($_POST['email'])  && isset($_POST['pass']) ){
    if( strlen($_POST['email'])<1 || strlen($_POST['pass'])<1){
      $_SESSION['error']='User name and password are required';
      header("Location:login.php");
      return;
    }
    else if ( strpos($_POST['email'] , '@') ===false ){
      $_SESSION['error']='Email should have an @-sign';
      header("Location:login.php");
      return;
    }
    else{
      $check=hash('md5',$_POST['pass']);
      if ( $check == $stored_hash) {
        $_SESSION['name']=$_POST['email'];
        header("Location:main.php");
        return;
      }
      else{
        $_SESSION['error']='Incorrect password';
        header("Location:login.php");
        return;
      }
  }
}

?>
<!--view-->
<html>
<head>
  <title> Joy Krishan Das</title>
  <?php require_once "bootstrap.php" ?>
</head>
<body style="margin-left:5%">
  <h1>Please Log in</h1>
  <p style="color:red"><?php $error=isset($_SESSION['error'])? $_SESSION['error']:false;
                          echo $error;
                          unset($_SESSION['error']);
                          ?></p>
  <form method='post'>
    <label for="id1">User Name</label>
    <input type="text" name="email"/></br>
    <label for="id2">Password</label>
    <input type="text" name="pass"/></br>
    <input type="submit" value="Log In"/>  <a href="index.php">Cancel</a>
  </form>

</body>
</html>
