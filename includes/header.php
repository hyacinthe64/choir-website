<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$pageTitle  = $pageTitle  ?? 'NARADA CHOIR AT EMLR';
$activePage = $activePage ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($pageTitle) ?></title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="navbar">
  <div class="brand">
    <span class="note">🎵</span> NARADA CHOIR AT EMLR
  </div>
  <nav>
    <a href="index.php"     class="<?= $activePage==='home'      ? 'active':'' ?>">Home</a>
    <a href="songs.php"     class="<?= $activePage==='songs'     ? 'active':'' ?>">Songs</a>
    <a href="committee.php" class="<?= $activePage==='committee' ? 'active':'' ?>">Committee</a>
    <?php if (isset($_SESSION['admin_id'])): ?>
      <a href="admin/dashboard.php" class="btn-login">Dashboard</a>
    <?php else: ?>
      <a href="admin/login.php" class="btn-login">Admin Login</a>
    <?php endif; ?>
  </nav>
</div>
