<?php
require_once '../config/db.php';
require_once '../includes/auth.php';

if (is_logged_in()) {
    header('Location: dashboard.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = ?");
    $stmt->execute([$username]);
    $admin = $stmt->fetch();

    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['admin_id']   = $admin['id'];
        $_SESSION['admin_name'] = $admin['full_name'];
        header('Location: dashboard.php');
        exit;
    } else {
        $error = 'Invalid username or password.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Login — NARADA CHOIR AT EMLR</title>
  <link rel="stylesheet" href="../css/style.css">
</head>
<body style="background:linear-gradient(135deg,var(--deep-purple),var(--royal-purple));min-height:100vh;">

<div class="form-card" style="margin-top:80px;">
  <div class="text-center mb-20">
    <div style="font-size:42px;">🎵</div>
    <h2>NARADA CHOIR Admin</h2>
    <p class="text-muted">Sign in to manage members, attendance, dues &amp; songs.</p>
  </div>

  <?php if ($error): ?>
    <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <form method="POST">
    <div class="form-group">
      <label>Username</label>
      <input type="text" name="username" required autofocus>
    </div>
    <div class="form-group">
      <label>Password</label>
      <input type="password" name="password" required>
    </div>
    <button type="submit" class="btn btn-purple" style="width:100%;margin:0;">Login</button>
  </form>

  <p class="text-center mt-20" style="font-size:13px;">
    <a href="../index.php" style="color:var(--soft-purple);">&larr; Back to public site</a>
  </p>
</div>

</body>
</html>
