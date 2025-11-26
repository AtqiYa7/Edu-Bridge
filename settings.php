<?php
include("inc/connection.inc.php");

ob_start();
session_start();
if (!isset($_SESSION['user_login'])) {
	$user = "";
}
else {
	$user = $_SESSION['user_login'];
	$result = $con->query("SELECT * FROM user WHERE id='$user'");
		$get_user_name = $result->fetch_assoc();
			$uname_db = $get_user_name['fullname'];
			$email_db = $get_user_name['email'];
			$pro_pic_db = $get_user_name['user_pic'];
			$ugender_db = $get_user_name['gender'];
			$utype_db = $get_user_name['type'];

			if($pro_pic_db == ""){
				if($ugender_db == "male"){
					$pro_pic_db = "malepic.png";
				}else if($ugender_db == "female"){
					$pro_pic_db = "femalepic.png";

				}
			}
}

$error = "";

$senddata = @$_POST['changesettings'];
//password variable
$oldpassword = strip_tags(@$_POST['opass']);
$newpassword = strip_tags(@$_POST['npass']);
$repear_password = strip_tags(@$_POST['npass1']);
$email = strip_tags(@$_POST['email']);
$oldpassword = trim($oldpassword);
$newpassword = trim($newpassword);
$repear_password = trim($repear_password);
//update pass
if ($senddata) {
	//if the information submited
	$password_query = $con->query("SELECT * FROM user WHERE id='$user'");
	while ($row = mysqli_fetch_assoc($password_query)) {
		$db_password = $row['pass'];
		$db_email = $row['email'];
		//try to change MD5 pass
		$oldpassword_md5 = md5($oldpassword);
		if ($oldpassword_md5 == $db_password) {
			if ($newpassword == $repear_password) {
				//Awesome.. Password match.
				$newpassword_md5 = md5($newpassword);
				if (strlen($newpassword) <= 3) {
					$error = "<p class='error_echo'>Sorry! But your new password must be 3 or more then 5 character!</p>";
				}else {
				$confirmCode   = substr( rand() * 900000 + 100000, 0, 6 );
				$password_update_query = $con->query("UPDATE user SET pass='$newpassword_md5', confirmcode='$confirmCode', email='$email' WHERE id='$user'");
				$error = "<p class='succes_echo'>Success! Your settings updated.</p>";
			}
		}else {
				$error = "<p class='error_echo'>Two new password don't match!</p>";
			}
	}else {
			$error = "<p class='error_echo'>The old password is incorrect!</p>";
		}
}
}else {
	$error = "";
}

?>



<!-- homemenu tab script -->
<!-- <script  src="js/homemenu.js"></script> -->

<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<link rel="stylesheet" type="text/css" href="css/reg.css">
	<link href="css/footer.css" rel="stylesheet" type="text/css" media="all" />

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
	
</head>
<body class="body1">
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
								$resultnoti = $con->query("SELECT * FROM applied_post WHERE post_by='$user' AND student_ck='no'");
								$resultnoti_cnt = $resultnoti->num_rows;
								if($resultnoti_cnt == 0){
									$resultnoti_cnt = "";
								}else{
									$resultnoti_cnt = '('.$resultnoti_cnt.')';
								}
								echo '<td>
							<a class="navlink" href="notification.php">Notification'.$resultnoti_cnt.'</a>
						</td>
						<td>
							<a class="active navlink" href="profile.php?uid='.$user.'">'.$uname_db.'</a>
						</td>
						<td>
							<a class="navlink" href="logout.php">Logout</a>
						</td>';
							}else{
								echo '<td>
							<a class="navlink" href="login.php">Login</a>
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

	<!-- newsfeed -->
	<div style="margin: 20px; overflow: hidden;">
		<div style="width: 1000px; margin: 0 auto;">
		
			<ul>
				<li style="float: left;">
					<div class="settingsleftcontent">
						<ul>
							<li><?php echo '<a href="profile.php?uid='.$user.'" >Post</a>'; ?></li>
							<li><?php echo '<a href="aboutme.php?uid='.$user.'">About</a>'; ?></li>
							<li><?php echo '<a href="settings.php" style=" background-color: #2563eb; border-radius: 4px; color: #fff;">Settings</a>'; ?></li>
						</ul>
					</div>
				</li>
				<li style="float: right;">
					<div class="testbox">
						<form action="" method="POST" class="registration">
								<div class="signup_error_msg">
									<?php echo $error; ?>
								</div>
								<div style="text-align: center;font-size: 20px;color: #aaa;margin: 0 0 5px 0;">
									<td >Change Password:</td>
								</div>
								<div>
									<td><input class="" type="password" name="opass" placeholder="Old Password"></td>
								</div>
								<div>
									<td><input class="" type="password" name="npass" placeholder="New Password"></td>
								</div>
								<div>
									<td><input class="" type="password" name="npass1" placeholder="Repeat Password"></td>
								</div>
								<div style="text-align: center;font-size: 20px;color: #aaa;margin: 0 0 5px 0;">
									<td >Change Email:</td>
								</div>
								<div>
									<td><?php echo '<input class="" required type="email" name="email" placeholder="New Email" value="'.$email_db.'">'; ?></td>
								</div>
								<div>
									<td><input class="sub_button" type="submit" name="changesettings" value="Update Settings"></td>
								</div>
						</form>
					</div>
				</li>
			</ul>
		</div>
	</div>
	<!-- footer -->
	<div>
	<?php
		include 'inc/footer.inc.php';
	?>
	</div>
</div>
<!-- homemenu script removed -->
</body>
</html>