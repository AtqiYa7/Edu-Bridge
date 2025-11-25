<?php
require_once __DIR__ . '/../inc/connection.inc.php';

ob_start();
session_start();

if (!isset($_SESSION['admin_id'])) {
	header('Location: login.php');
	exit();
}

$adminName = $_SESSION['admin_name'] ?? 'Administrator';

function fetchCount(mysqli $con, string $query): int {
	$result = $con->query($query);
	if ($result && $row = $result->fetch_assoc()) {
		return (int) ($row['total'] ?? 0);
	}
	return 0;
}

$stats = [
	'students' => fetchCount($con, "SELECT COUNT(*) AS total FROM user WHERE type='student'"),
	'teachers' => fetchCount($con, "SELECT COUNT(*) AS total FROM user WHERE type='teacher'"),
	'posts' => fetchCount($con, "SELECT COUNT(*) AS total FROM post"),
	'pendingApplications' => fetchCount($con, "SELECT COUNT(*) AS total FROM applied_post WHERE tution_cf='0'")
];

$recentUsers = [];
$recentPosts = [];
$pendingApplications = [];

$recentUsersResult = $con->query("SELECT fullname, email, type, last_logout FROM user ORDER BY id DESC LIMIT 6");
if ($recentUsersResult) {
	while ($row = $recentUsersResult->fetch_assoc()) {
		$recentUsers[] = $row;
	}
}

$recentPostsResult = $con->query("SELECT post.id, post.subject, post.location, post.post_time, user.fullname AS author
	FROM post 
	INNER JOIN user ON user.id = post.postby_id 
	ORDER BY post.post_time DESC 
	LIMIT 6");
if ($recentPostsResult) {
	while ($row = $recentPostsResult->fetch_assoc()) {
		$recentPosts[] = $row;
	}
}

$pendingApplicationsResult = $con->query("SELECT applied_post.id, applied_post.applied_time, user.fullname AS applicant, post.subject 
	FROM applied_post 
	INNER JOIN user ON user.id = applied_post.applied_by
	INNER JOIN post ON post.id = applied_post.post_id
	WHERE applied_post.tution_cf='0'
	ORDER BY applied_post.applied_time DESC
	LIMIT 6");
if ($pendingApplicationsResult) {
	while ($row = $pendingApplicationsResult->fetch_assoc()) {
		$pendingApplications[] = $row;
	}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Admin Dashboard | Edu Bridge</title>
	<link rel="stylesheet" type="text/css" href="../css/admin.css">
</head>
<body class="admin-body">
	<header class="admin-header">
		<div class="brand">
			<h1>Edu Bridge Admin</h1>
			<p>Monitor activities, users and posts from one place.</p>
		</div>
		<div class="admin-session">
			<span>Signed in as <strong><?php echo htmlspecialchars($adminName); ?></strong></span>
			<a class="admin-btn ghost" href="logout.php">Logout</a>
		</div>
	</header>

	<div class="admin-wrapper">
		<aside class="admin-sidebar">
			<nav>
				<a href="#stats" class="active">Overview</a>
				<a href="#recent-users">Recent Users</a>
				<a href="#recent-posts">Recent Posts</a>
				<a href="#pending-applications">Pending Applications</a>
			</nav>
		</aside>

		<main class="admin-main">
			<section id="stats">
				<h2>Key Metrics</h2>
				<div class="stat-grid">
					<div class="stat-card">
						<p>Total Students</p>
						<h3><?php echo number_format($stats['students']); ?></h3>
					</div>
					<div class="stat-card">
						<p>Total Teachers</p>
						<h3><?php echo number_format($stats['teachers']); ?></h3>
					</div>
					<div class="stat-card">
						<p>Live Posts</p>
						<h3><?php echo number_format($stats['posts']); ?></h3>
					</div>
					<div class="stat-card">
						<p>Pending Applications</p>
						<h3><?php echo number_format($stats['pendingApplications']); ?></h3>
					</div>
				</div>
			</section>

			<section id="recent-users">
				<div class="section-header">
					<h2>Newest Users</h2>
					
				</div>
				<div class="admin-card">
					<table>
						<thead>
							<tr>
								<th>Name</th>
								<th>Role</th>
								<th>Email</th>
								<th>Last Active</th>
							</tr>
						</thead>
						<tbody>
							<?php if (empty($recentUsers)): ?>
								<tr>
									<td colspan="4" class="empty-state">No users found.</td>
								</tr>
							<?php else: ?>
								<?php foreach ($recentUsers as $user): ?>
									<tr>
										<td><?php echo htmlspecialchars($user['fullname']); ?></td>
										<td><span class="badge <?php echo $user['type'] === 'teacher' ? 'warn' : 'info'; ?>">
											<?php echo ucfirst(htmlspecialchars($user['type'])); ?>
										</span></td>
										<td><?php echo htmlspecialchars($user['email']); ?></td>
										<td><?php echo $user['last_logout'] ? date('M d, Y H:i', strtotime($user['last_logout'])) : '—'; ?></td>
									</tr>
								<?php endforeach; ?>
							<?php endif; ?>
						</tbody>
					</table>
				</div>
			</section>

			<section id="recent-posts">
				<div class="section-header">
					<h2>Recent Tuition Posts</h2>
					
				</div>
				<div class="admin-card">
					<table>
						<thead>
							<tr>
								<th>#</th>
								<th>Subject</th>
								<th>Location</th>
								<th>Posted By</th>
								<th>Created</th>
							</tr>
						</thead>
						<tbody>
							<?php if (empty($recentPosts)): ?>
								<tr>
									<td colspan="5" class="empty-state">No posts available.</td>
								</tr>
							<?php else: ?>
								<?php foreach ($recentPosts as $post): ?>
									<tr>
										<td>#<?php echo (int) $post['id']; ?></td>
										<td><?php echo htmlspecialchars($post['subject']); ?></td>
										<td><?php echo htmlspecialchars($post['location']); ?></td>
										<td><?php echo htmlspecialchars($post['author']); ?></td>
										<td><?php echo date('M d, Y H:i', strtotime($post['post_time'])); ?></td>
									</tr>
								<?php endforeach; ?>
							<?php endif; ?>
						</tbody>
					</table>
				</div>
			</section>

			<section id="pending-applications">
				<div class="section-header">
					<h2>Pending Applications</h2>
					
				</div>
				<div class="admin-card">
					<table>
						<thead>
							<tr>
								<th>ID</th>
								<th>Applicant</th>
								<th>Post</th>
								<th>Applied At</th>
							</tr>
						</thead>
						<tbody>
							<?php if (empty($pendingApplications)): ?>
								<tr>
									<td colspan="4" class="empty-state">No pending applications.</td>
								</tr>
							<?php else: ?>
								<?php foreach ($pendingApplications as $application): ?>
									<tr>
										<td>#<?php echo (int) $application['id']; ?></td>
										<td><?php echo htmlspecialchars($application['applicant']); ?></td>
										<td><?php echo htmlspecialchars($application['subject']); ?></td>
										<td><?php echo date('M d, Y H:i', strtotime($application['applied_time'])); ?></td>
									</tr>
								<?php endforeach; ?>
							<?php endif; ?>
						</tbody>
					</table>
				</div>
			</section>
		</main>
	</div>
</body>
</html>

