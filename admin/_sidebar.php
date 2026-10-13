<?php
/* Shared admin layout — include after require_login().
   Caller must set $pageTitle and $activeNav before including. */
$activeNav = $activeNav ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($pageTitle ?? 'Admin') ?> — NARADA CHOIR Admin</title>
  <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<div class="admin-wrap">

  <!-- SIDEBAR -->
  <div class="sidebar">
    <div class="brand">🎵 NARADA CHOIR Admin</div>
    <a href="dashboard.php"       class="<?= $activeNav==='dashboard' ?'active':'' ?>">📊 Dashboard</a>
    <a href="members.php"         class="<?= $activeNav==='members'   ?'active':'' ?>">👥 Members</a>
    <a href="attendance.php"      class="<?= $activeNav==='attendance'?'active':'' ?>">📅 Attendance</a>
    <a href="dues.php"            class="<?= $activeNav==='dues'      ?'active':'' ?>">💰 Dues / Fees</a>
    <a href="songs_admin.php"     class="<?= $activeNav==='songs'     ?'active':'' ?>">🎵 Songs</a>
    <a href="committee_admin.php" class="<?= $activeNav==='committee' ?'active':'' ?>">🧑‍💼 Committee</a>
    <a href="../index.php">🌐 View Public Site</a>
    <a href="logout.php" style="color:#f1b3a8;">🚪 Logout (<?= htmlspecialchars($_SESSION['admin_name'] ?? 'Admin') ?>)</a>
  </div>

  <!-- MAIN CONTENT AREA -->
  <div class="admin-main">
