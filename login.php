<?php
include("inc/connection.inc.php");
ob_start();
session_start();
if (!isset($_SESSION['user_login'])) {
	$utype_db = "";
	$user = "";
}
else {
	header("location: index.php");
}
$emails = "";
$passs = "";
if (isset($_POST['login'])) {
	if (isset($_POST['email']) && isset($_POST['password'])) {
		//$user_login = mysql_real_escape_string($_POST['email']);
		$user_login = $_POST['email'];
		$user_login = mb_convert_case($user_login, MB_CASE_LOWER, "UTF-8");	
		//$password_login = mysql_real_escape_string($_POST['password']);		
		$password_login = $_POST['password'];
		$password_login_md5 = md5($password_login);
		$result = $con->query("SELECT * FROM user WHERE (email='$user_login') AND pass='$password_login_md5'");
		$num = mysqli_num_rows($result);
		$get_user_email = $result->fetch_assoc();
			$get_user_uname_db = $get_user_email['id'];
			$get_user_type_db = $get_user_email['type'];
		if (mysqli_num_rows($result)>0) {
			$_SESSION['user_login'] = $get_user_uname_db;
			setcookie('user_login', $user_login, time() + (365 * 24 * 60 * 60), "/");
			$online = 'yes';
			$result = $con->query("UPDATE user SET online='$online' WHERE id='$get_user_uname_db'");
			if($_SESSION['u_post'] == "post")
			{
				//if (isset($_REQUEST['ono'])) {
			//	$ono = mysql_real_escape_string($_REQUEST['ono']);
			//	header("location: orderform.php?poid=".$ono."");
			//}else {
				if($get_user_type_db == "teacher"){
					$_REQUEST['teacher'] = "logastchr";
					header('location: checking.php?teacher=logastchr');
				}else{
					header('location: postform.php');
				}
				
			//}
			}elseif($_REQUEST['pid'] != ""){
				header('location: viewpost.php?pid='.$_REQUEST['pid'].'');
			}else{
				header('location: index.php');
			}
			exit();
		}
		else {
			header('Location: login.php');
			
		}
	}

}
$acemails = "";
$acccode = "";
if(isset($_POST['activate'])){
	if(isset($_POST['actcode'])){
		$user_login = $con->real_escape_string($_POST['acemail']);
		$user_login = mb_convert_case($user_login, MB_CASE_LOWER, "UTF-8");    
		$user_acccode = $con->real_escape_string($_POST['actcode']);
		$stmt = $con->prepare("SELECT * FROM user WHERE email=? AND confirmCode=?");
		$stmt->bind_param("ss", $user_login, $user_acccode);
		$stmt->execute();
		$result2 = $stmt->get_result();
		$num3 = $result2->num_rows;
		echo $user_login;
		if ($num3 > 0) {
			$get_user_email = $result2->fetch_assoc();
			$get_user_uname_db = $get_user_email['id'];
			$_SESSION['user_login'] = $get_user_uname_db;
			setcookie('user_login', $user_login, time() + (365 * 24 * 60 * 60), "/");
			$stmt = $con->prepare("UPDATE user SET confirmCode='0', activation='yes' WHERE email=?");
			$stmt->bind_param("s", $user_login);
			$stmt->execute();
			if (isset($_REQUEST['ono'])) {
				$ono = $con->real_escape_string($_REQUEST['ono']);
				header("location: orderform.php?poid=".$ono."");
			} else {
				header('location: index.php');
			}
			exit();
		}else {
			$emails = $user_login;
			$error_message = '<br><br>
				<div class="maincontent_text" style="text-align: center; font-size: 18px;">
				<font face="bookman">Code not matched!<br>
				</font></div>';
		}
	}else {
		$error_message = '<br><br>
				<div class="maincontent_text" style="text-align: center; font-size: 18px;">
				<font face="bookman">Activation code not matched!<br>
				</font></div>';
	}

}

?>


<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<link href="css/footer.css" rel="stylesheet" type="text/css" media="all" />
	<link href="css/reg.css" rel="stylesheet" type="text/css" media="all" />
	
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
	<style>
		body.global-login-body {
			font-family: 'Open Sans', 'Segoe UI', sans-serif;
			background: #f4f5f9;
		}

		.global-login-wrapper {
			display: flex;
			justify-content: center;
			align-items: center;
			min-height: calc(100vh - 220px);
			padding: 40px 16px 80px;
		}

		.testbox.global-login-card {
			width: 100%;
			max-width: 480px;
			padding-bottom: 24px;
		}

		.testbox.global-login-card h1 {
			margin-top: 24px;
			font-size: 32px;
		}

		.testbox.global-login-card input[type="text"],
		.testbox.global-login-card input[type="password"] {
			width: 100%;
			margin: 14px 0 0;
			border-radius: 4px;
		}

		.global-login-btn {
			width: 100%;
			margin: 24px 0 0;
			height: 40px;
			float: none;
			background-color: #2563eb;
		}

		.global-login-btn:hover {
			background-color: #1d4ed8;
		}

		.global-login-options {
			display: flex;
			justify-content: space-between;
			align-items: center;
			margin-top: 12px;
		}
		.global-login-options label {
			display: flex;
			align-items: center;
			gap: 8px;
			color: #4b5563;
			font-size: 14px;
		}
		.global-forgot-link {
			color: #2563eb;
			text-decoration: none;
			font-weight: 600;
			font-size: 14px;
		}

		.global-support-links {
			padding: 12px 0 0;
			text-align: center;
		}

		.global-support-links p {
			margin: 6px 0;
			font-size: 14px;
			color: #4b5563;
		}

		.global-support-links a {
			color: #2563eb;
			font-weight: 600;
			margin-left: 4px;
			text-decoration: none;
		}
	</style>
</head>
<body class="global-login-body">
<div>
<div>
		<header class="header">

			<div class="header-cont">

				<?php
					include 'inc/banner.inc.php';
				?>

			</div>
		</header>
		
		<div class="topnav">
			<a class="navlink" href="index.php" style="margin: 0px 0px 0px 100px;">Newsfeed</a>
			<a class="navlink" href="search.php">Search Tutor</a>
			<?php 
			if($utype_db == "teacher")
				{

				}else {
					echo '<a class="navlink" href="postform.php">Post</a>';
				}

			 ?>

			<a class="navlink" href="#about">About</a>
			<div style="float: right;" >
				<table>
					<tr>
						<?php
							if($user != ""){
								echo '<td>
							<a class="active navlink" href="profile.php?uid='.$user.'">'.$uname_db.'</a>
						</td>
						<td>
							<a class="navlink" href="logout.php">Logout</a>
						</td>';
							}else{
								echo '<td>
							<a class="active navlink" href="login.php">Login</a>
						</td>
						<td>
							<a class="navlink" href="registration.php">Register</a>
						</td>';
							}
						?>
						
					</tr>
				</table>
			</div>
		</div>
	</div>
	<div class="global-login-wrapper">
		<div class="testbox global-login-card">
			<h1>Login</h1>
			<form action="" method="post">
				<hr>
				<input type="text" name="email" id="name" placeholder="Email" required/>
				<input type="password" name="password" id="name" placeholder="Password" required/>
				<div class="global-login-options">
					<label><input type="checkbox" name="remember" /> Remember me</label>
					<a href="#" class="global-forgot-link">Forgot password?</a>
				</div>
				<input type="submit" class="sub_button global-login-btn" name="login" id="name" value="Sign In"/>
				<div class="global-support-links">
					<p>Don't have an account?<a href="registration.php">Sign up now</a></p>
				</div>
				<?php if (!empty($error_message)) { echo $error_message; } ?>
			</form>
		</div>
	</div>

	<div>
	<?php
		include 'inc/footer.inc.php';
	?>
	</div>
	</div>

</body>
</html>