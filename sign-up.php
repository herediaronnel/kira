<?php
session_start();
require_once('class.user.php');
$user = new USER();

if($user->is_loggedin()!="")
{
	$user->redirect('home.php');
}

if(isset($_POST['btn-signup']))
{
	$ufname = strip_tags($_POST['txt_ufname']);
	$ulname = strip_tags($_POST['txt_ulname']);
	$umiscode = strip_tags($_POST['txt_umiscode']);
	$uname = strip_tags($_POST['txt_uname']);
	$umail = strip_tags($_POST['txt_umail']);
	$upass = strip_tags($_POST['txt_upass']);	

	if ($ufname=="") {
		$error[] = "provide Firstname !";
	}

	elseif ($ulname=="") {
		$error[] = "provide Lastname !";
	}
	elseif ($umiscode=="") {
		$error[] = "Provide MIS CODE !";
	}
	
	 elseif($uname=="")	{
		$error[] = "provide username !";	
	}
	else if($umail=="")	{
		$error[] = "provide email id !";	
	}
	else if(!filter_var($umail, FILTER_VALIDATE_EMAIL))	{
	    $error[] = 'Please enter a valid email address !';
	}
	else if($upass=="")	{
		$error[] = "provide password !";
	}
	else if(!preg_match("/[a-z]/", $upass) ||
			!preg_match("/[A-Z]/", $upass) ||
			!preg_match("/[0-9]/", $upass) ||
			strlen($upass) < 6){
		$error[] = "Password must be atleast 6 characters and require 1 each of a-z, A-Z and 0-9 ";	
	}
	
	else
	{
		try
		{
			$stmt = $user->runQuery("SELECT  user_name, user_email FROM users WHERE user_name=:uname OR user_email=:umail");
			$stmt->execute(array(':uname'=>$uname, ':umail'=>$umail));
			$row=$stmt->fetch(PDO::FETCH_ASSOC);
				
			if($row['user_name']==$uname) {
				$error[] = "sorry username already taken !";
		}
			else if($row['user_email']==$umail) {
				$error[] = "sorry email id already taken !";
			}
			else
			{
				if($user->register($ufname,$ulname,$umiscode,$uname,$umail,$upass)){	
					$user->redirect('sign-up.php?joined');
				}
			}
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}	
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>E-Class Record System : Sign up</title>
<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
<link href="bootstrap/css/bootstrap-theme.min.css" rel="stylesheet" media="screen">
<link rel="stylesheet" href="style.css" type="text/css"  />
</head>
<body>

<div class="signin-form">

<div class="container">
    	
        <form method="post" class="form-signin">
            <h2 class="form-signin-heading">Sign up.</h2><hr />
            <?php
			if(isset($error))
			{
			 	foreach($error as $error)
			 	{
					 ?>
                     <div class="alert alert-danger">
                        <i class="glyphicon glyphicon-warning-sign"></i> &nbsp; <?php echo $error; ?>
                     </div>
                     <?php
				}
			}
			else if(isset($_GET['joined']))
			{
				 ?>
                 <div class="alert alert-info">
                      <i class="glyphicon glyphicon-log-in"></i> &nbsp; Successfully registered <a href='index.php'>login</a> here
                 </div>
                 <?php
			}
			?>
			<div class="form-group">
            <input type="text" class="form-control" name="txt_ufname" placeholder="Enter your Firstname" value="<?php if(isset($error)){echo $ufname;}?>" />
            </div>
            <div class="form-group">
            <input type="text" class="form-control" name="txt_ulname" placeholder="Enter your Lastname" value="<?php if(isset($error)){echo $ulname;}?>" />
            </div>
            <div class="form-group">
            <input type="text" class="form-control" name="txt_umiscode" placeholder="Enter MIS Code" value="<?php if(isset($error)){echo $umiscode;}?>" />
            </div>


            <div class="form-group">
            <input type="text" class="form-control" name="txt_uname" placeholder="Enter Username" value="<?php if(isset($error)){echo $uname;}?>" />
            </div>
            <div class="form-group">
            <input type="text" class="form-control" name="txt_umail" placeholder="Enter E-Mail ID" value="<?php if(isset($error)){echo $umail;}?>" />
            </div>
            <div class="form-group">
            	<input type="password" class="form-control" name="txt_upass" placeholder="Enter Password" />
            </div>
            <div class="clearfix"></div><hr />
            <div class="form-group">
            	<button type="submit" class="btn btn-info" name="btn-signup">
                	<i class="glyphicon glyphicon-open-file"></i>&nbsp;SIGN UP
                </button>
            </div>
            <br />
            <label>have an account ! <a class="btn btn-success" href="index.php">Login Now!</a></label>
        </form>
       </div>
</div>

</div>

</body>
</html>