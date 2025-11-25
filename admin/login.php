<?php
require_once __DIR__ . '/../inc/connection.inc.php';

ob_start();
session_start();

if (isset($_SESSION['admin_id'])) {
	header('Location: dashboard.php');
	exit();
}

$email = '';
$error_message = '';
$user = "";
$utype_db = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$email = trim($_POST['email'] ?? '');
	$password = $_POST['password'] ?? '';

	if ($email === '' || $password === '') {
		$error_message = 'Email and password are required.';
	} else {
		$password_hash = md5($password);
		$stmt = $con->prepare("SELECT id, fullname FROM admin WHERE email = ? AND password = ? LIMIT 1");
		if ($stmt) {
			$stmt->bind_param('ss', $email, $password_hash);
			$stmt->execute();
			$result = $stmt->get_result();
			if ($result && $result->num_rows === 1) {
				$admin = $result->fetch_assoc();
				$_SESSION['admin_id'] = $admin['id'];
				$_SESSION['admin_name'] = $admin['fullname'];
				header('Location: dashboard.php');
				exit();
			} else {
				$error_message = 'Invalid email or password.';
			}
			$stmt->close();
		} else {
			$error_message = 'Unable to process your request right now.';
		}
	}
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Edu Bridge | Admin Login</title>
	<link rel="stylesheet" type="text/css" href="../css/style.css">
	<link href="../css/footer.css" rel="stylesheet" type="text/css" media="all" />
	<link href="../css/reg.css" rel="stylesheet" type="text/css" media="all" />
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
	<style>
		body.admin-login-body {
			font-family: 'Open Sans', 'Segoe UI', sans-serif;
			background: #f4f5f9;
		}

		.testbox.admin-login-card {
			max-width: 480px;
			width: 100%;
			padding-bottom: 24px;
		}

		.testbox.admin-login-card h1 {
			margin-top: 24px;
			font-size: 32px;
		}

		.testbox.admin-login-card input[type="text"],
		.testbox.admin-login-card input[type="password"] {
			width: 100%;
			margin: 14px 0 0;
			border-radius: 4px;
		}

		.admin-login-btn {
			width: 100%;
			margin: 24px 0 0;
			height: 40px;
			float: none;
			background-color:#2563eb;
		}

		.admin-login-btn:hover {
			background-color:#2563eb;
		}

		.admin-login-options {
			display: flex;
			justify-content: space-between;
			align-items: center;
			margin-top: 12px;
		}
		.admin-login-options label {
			display: flex;
			align-items: center;
			gap: 8px;
			color: #4b5563;
			font-size: 14px;
		}
		.admin-forgot-link {
			color: #2563eb;
			text-decoration: none;
			font-weight: 600;
			font-size: 14px;
		}

		.admin-support-links {
			padding: 12px 0 0;
			text-align: center;
		}

		.admin-support-links p {
			margin: 6px 0;
			font-size: 14px;
			color: #4b5563;
		}

		.admin-support-links a {
			color: #2563eb;
			font-weight: 600;
			margin-left: 4px;
			text-decoration: none;
		}
	</style>
</head>
<body class="body1 admin-login-body">
<?php $basePath = '../'; ?>
<div>
	<header class="header">
		<div class="header-cont">
			<?php include '../inc/banner.inc.php'; ?>
		</div>
	</header>
	<div class="topnav">
		<a class="navlink" href="../index.php" style="margin: 0px 0px 0px 100px;">Newsfeed</a>
		<a class="navlink" href="../search.php">Search Tutor</a>
		<?php 
		if($utype_db != "teacher") {
			echo '<a class="navlink" href="../postform.php">Post</a>';
		}
		?>
		<a class="navlink" href="#contact">Contact</a>
		<a class="navlink" href="#about">About</a>
		<div style="float: right;">
			<table>
				<tr>
					<td><a class="navlink" href="../login.php">Login</a></td>
					<td><a class="navlink" href="../registration.php">Register</a></td>
				</tr>
			</table>
		</div>
	</div>
</div>

<div class="nbody" style="margin: 0px 100px; overflow: hidden;">
	<div class="nfeedleft" style="background-color: unset;">
		<div class="testbox admin-login-card">
			<h1>Login</h1>
			<form action="" method="post">
				<hr>
				<input type="text" name="email" placeholder="Email" value="<?php echo htmlspecialchars($email); ?>" required/>
				<input type="password" name="password" placeholder="Password" required/>
				<div class="admin-login-options">
					<label><input type="checkbox" name="remember" /> Remember me</label>
					<a href="#" class="admin-forgot-link">Forgot password?</a>
				</div>
				<input type="submit" class="sub_button admin-login-btn" name="login" value="Sign In"/>
				<div class="admin-support-links">
					<p>Don't have an account?<a href="register.php">Sign up now</a></p>
				</div>
				<?php
				if ($error_message !== '') {
					echo '<div class="signup_error_msg" style="color: #A92A2A;">'.htmlspecialchars($error_message).'</div>';
				}
				?>
			</form>
		</div>
	</div>
	<div class="nfeedright">
	</div>
</div>

<div>
	<?php include '../inc/footer.inc.php'; ?>
</div>

</body>
</html>

