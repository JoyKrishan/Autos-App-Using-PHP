<!--model-->
<?php
  require_once "pdo.php";
  session_start();
  if (isset($_POST['email']) && isset($_POST['pass']) ){
    $salt='XyZzy12*_';
    $check=hash('md5', $salt.$_POST['pass']);
    $_SESSION['check']=$check;
    $stmt = $pdo->prepare('SELECT * FROM users WHERE email=:em AND password=:pw');
    $stmt->execute(array( ':em' => $_POST['email'], ':pw' => $check));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row !== false){
      $_SESSION['user_id']=$row['user_id'];
      $_SESSION['name']=$row['name'];
      header("Location:index.php");
      return;
    }else{
      $_SESSION['error']="Invalid password";
      header("Location:login.php");
      return;
    }

  }
?>
<!--view-->

<html>
<head><title>Joy Krishan Das</title><head>
  <?php require_once "bootstrap.php";
        require_once "utility.php"; ?>
<body style="margin-left:5%">
  <h1>Please Log In </h1>

  <form method="post">
    <label for="id1">Email</label>
    <input type="text" name='email' id="mail"/></br>
    <label for="id2">Password</label>
    <input type="text" name="pass" id="pass"/></br>
    <input type="submit" value="Log In" onclick="return doValidate();"/>
    <a href="login.php">Cancel</a>
  </form>
  <script>
    function doValidate(){
      console.log("Validating.........");
      try{
        var pass=document.getElementById('pass').value;
        var email=document.getElementById('mail').value;
        console.log("Validating " +pass + "and " + email );
        if (pass == null || pass == "" || email == "" || email==null){
          alert("Both the fields must be filled");
          return false;
        }
        else if (! email.includes('@')) {
            alert("Invalid email address");
            return false;
          }
          return true;
        } catch (e){
          return false;
        }
      return false;
    }
  </script>
</body>
</html>
