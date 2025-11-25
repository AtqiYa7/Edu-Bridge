<?php
require_once __DIR__ . '/../inc/connection.inc.php';

ob_start();
session_start();

if (isset($_SESSION['admin_id'])) {
	header('Location: dashboard.php');
	exit();
}

$fullname = '';
$email = '';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$fullname = trim($_POST['fullname'] ?? '');
	$email = trim($_POST['email'] ?? '');
	$password = $_POST['password'] ?? '';
	$confirmPassword = $_POST['confirm_password'] ?? '';

	if ($fullname === '') {
		$errors[] = 'Full name is required.';
	}

	if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
		$errors[] = 'A valid email address is required.';
	}

	if ($password === '') {
		$errors[] = 'Password is required.';
	} elseif (strlen($password) < 6) {
		$errors[] = 'Password must be at least 6 characters.';
	}

	if ($password !== $confirmPassword) {
		$errors[] = 'Passwords do not match.';
	}

	if (empty($errors)) {
		$stmt = $con->prepare("SELECT id FROM admin WHERE email = ? LIMIT 1");
		if ($stmt) {
			$stmt->bind_param('s', $email);
			$stmt->execute();
			$stmt->store_result();
			if ($stmt->num_rows > 0) {
				$errors[] = 'An account with this email already exists.';
			}
			$stmt->close();
		} else {
			$errors[] = 'Unable to process your request right now.';
		}
	}

	if (empty($errors)) {
		$passwordHash = md5($password);
		$stmt = $con->prepare("INSERT INTO admin (fullname, email, password) VALUES (?, ?, ?)");
		if ($stmt) {
			$stmt->bind_param('sss', $fullname, $email, $passwordHash);
			if ($stmt->execute()) {
				$newId = $stmt->insert_id;
				$_SESSION['admin_id'] = $newId;
				$_SESSION['admin_name'] = $fullname;
				header('Location: dashboard.php');
				exit();
			} else {
				$errors[] = 'Failed to create account. Please try again.';
			}
			$stmt->close();
		} else {
			$errors[] = 'Unable to process your request right now.';
		}
	}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Admin Registration | Edu Bridge</title>
	<link rel="stylesheet" type="text/css" href="../css/style.css">
	<link rel="stylesheet" type="text/css" href="../css/reg.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
	<style>
		body.admin-body {
			background: #f4f5f9;
			min-height: 100vh;
			display: flex;
			align-items: center;
			justify-content: center;
			padding: 40px 16px;
			font-family: 'Open Sans', 'Segoe UI', sans-serif;
		}

		.admin-register-card {
			width: 100%;
			max-width: 540px;
			background: #fff;
		}

		.admin-register-card h1 {
			margin-top: 32px;
		}

		.admin-register-subtitle {
			color: #6b7280;
			margin-bottom: 18px;
			font-size: 15px;
		}

		.admin-register-card form {
			margin-top: 0;
		}

		.admin-register-card input[type="text"],
		.admin-register-card input[type="email"],
		.admin-register-card input[type="password"] {
			width: 100%;
			margin: 14px 0 0;
			border-radius: 4px;
		}

		.admin-register-card .admin-submit {
			float: none;
			width: 100%;
			margin: 24px 0 0;
			height: 44px;
			font-size: 16px;
			background-color: #2563eb;
			border-radius: 5px;
		}

		.admin-register-card .admin-submit:hover {
			background-color: #1d4ed8;
		}

		.signup_error_msg.admin-error {
			background: #fee2e2;
			color: #b91c1c;
			border-radius: 6px;
			margin: 0 30px 18px;
			padding: 12px;
			border: 1px solid #fecaca;
			font-size: 14px;
		}

		.signup_success_msg {
			margin: 0 30px 18px;
		}

		.accounttype {
			display: flex;
			gap: 12px;
			justify-content: center;
			margin: 18px 0;
		}

		.admin-pill {
			padding: 8px 18px;
			border-radius: 20px;
			border: 1px solid #2563eb;
			color: #1d4ed8;
			font-weight: 600;
			display: inline-flex;
			align-items: center;
			gap: 6px;
			font-size: 14px;
			background: rgba(37, 99, 235, 0.1);
		}

		.admin-pill i {
			color: #2563eb;
		}

		.admin-pill.muted {
			border-color: #d1d5db;
			background: #f8fafc;
			color: #6b7280;
		}

		.admin-pill.muted i {
			color: #9ca3af;
		}

		.admin-register-footer,
		.admin-terms {
			margin-top: 20px;
			font-size: 14px;
			text-align: center;
			color: #4b5563;
		}

		.admin-register-footer a,
		.admin-terms a {
			color: #2563eb;
			text-decoration: none;
			font-weight: 600;
		}
	</style>
</head>
<body class="admin-body">
	<div class="testbox admin-register-card">
		<h1>Registration</h1>

		<hr>
		<div class="accounttype single">
			<div class="admin-pill active">
				<i class="fa fa-check-circle"></i> As an Admin
			</div>
		</div>
		<hr>

		<?php if (!empty($errors)): ?>
			<div class="signup_error_msg admin-error">
				<ul>
					<?php foreach ($errors as $error): ?>
						<li><?php echo htmlspecialchars($error); ?></li>
					<?php endforeach; ?>
				</ul>
			</div>
		<?php endif; ?>

		<form method="POST" action="">
			<input type="text" id="fullname" name="fullname" placeholder="Full Name" value="<?php echo htmlspecialchars($fullname); ?>" required>
			<input type="email" id="email" name="email" placeholder="Email" value="<?php echo htmlspecialchars($email); ?>" required>
			<input type="password" id="password" name="password" placeholder="Password" required>
			<input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm Password" required>
			<p class="admin-terms">By clicking Create Admin, you agree on our <a href="#">terms and condition</a>.</p>
			<input type="submit" class="sub_button admin-submit" value="Create Admin">
		</form>
		<div class="admin-register-footer">
			Already have access? <a href="login.php">Sign in</a>
		</div>
	</div>
</body>
</html>

