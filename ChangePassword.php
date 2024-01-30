<!DOCTYPE html>
<html>
<?php
if(isset($_POST["sendcode"])){
    include_once(User.php);
    $user = new User($_SESSION['user'], null, null, null, null, null, null, null);
    $user->getDetails();
    if($user->changePasswordEmail()){
        echo "Email sent";
    }
}
if (isset($_POST["verify"])) 
    {
      if(!empty(trim($_POST['token'])))
      {
        include_once(User.php);
        $user = new User($_SESSION['user'], null, null, null, null, null, null, null);
        if($user->verifyToken($_POST['token']))
        {
            // show reset password screen
        }
        else{
            echo "Incorrect token";
        }
      }
    }
    if (isset($_POST["changepass"])) 
    {
      if(!empty(trim($_POST['password'])))
      {
        include_once(User.php);
        $user = new User($_SESSION['user'], password_hash($_POST['password']), null, null, null, null, null, null);
        if($user->updatePassword()){
            echo "password updated";
        }
      }
    }
?>
<body>
</body>
</html>