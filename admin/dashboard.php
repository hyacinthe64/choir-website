<?php
require_once '../config/db.php';
require_once '../includes/auth.php';
require_login();

$pageTitle = 'Dashboard';
$activeNav = 'dashboard';

// Member counts by category
$counts = ['Man'=>0,'Woman'=>0,'Boy'=>0,'Girl'=>0];
foreach ($pdo->query("SELECT category,COUNT(*) AS c FROM members WHERE status='Active' GROUP BY category")->fetchAll() as $r) {
    $counts[$r['category']] = (int)$r['c'];
}
$totalMembers = array_sum($counts);

// This month's dues
$month = (int)date('n');
$year  = (int)date('Y');
$s = $pdo->prepare("SELECT COUNT(*) FROM dues WHERE due_month=? AND due_year=? AND status='Paid'");
$s->execute([$month,$year]); $paidCount = (int)$s->fetchColumn();
$s = $pdo->prepare("SELECT COUNT(*) FROM dues WHERE due_month=? AND due_year=? AND status='Unpaid'");
$s->execute([$month,$year]); $unpaidCount = (int)$s->fetchColumn();

// Today's attendance
$s = $pdo->prepare("SELECT COUNT(*) FROM attendance WHERE attendance_date=? AND status='Present'");
$s->execute([date('Y-m-d')]); $presentToday = (int)$s->fetchColumn();

$totalSongs = (int)$pdo->query("SELECT COUNT(*) FROM songs")->fetchColumn();

require '_sidebar.php';
?>

<div class="admin-topbar">
  <h1>Welcome back, <?= htmlspecialchars($_SESSION['admin_name'] ?? 'Admin') ?> 👋</h1>
  <span class="text-muted"><?= date('l, d F Y') ?></span>
</div>

<h3 class="mb-20">Choir Members Overview</h3>
<div class="grid grid-4 mb-20">
  <div class="stat-card total">
    <div class="stat-icon">🎤</div>
    <div class="stat-number"><?= $totalMembers ?></div>
    <div class="stat-label">Total Active Members</div>
  </div>
  <div class="stat-card men">
    <div class="stat-icon"></div>
    <div class="stat-number"><?= $counts['Man'] ?></div>
    <div class="stat-label">Men</div>
  </div>
  <div class="stat-card women">
    <div class="stat-icon"></div>
    <div class="stat-number"><?= $counts['Woman'] ?></div>
    <div class="stat-label">Women</div>
  </div>
  <div class="stat-card boys">
    <div class="stat-icon"></div>
    <div class="stat-number"><?= $counts['Boy'] ?></div>
    <div class="stat-label">Boys</div>
  </div>
</div>

<div class="grid grid-4 mb-20">
  <div class="stat-card girls">
    <div class="stat-icon"></div>
    <div class="stat-number"><?= $counts['Girl'] ?></div>
    <div class="stat-label">Girls</div>
  </div>
  <div class="stat-card paid">
    <div class="stat-icon"></div>
    <div class="stat-number"><?= $paidCount ?></div>
    <div class="stat-label">Dues Paid (<?= date('F') ?>)</div>
  </div>
  <div class="stat-card unpaid">
    <div class="stat-icon"></div>
    <div class="stat-number"><?= $unpaidCount ?></div>
    <div class="stat-label">Dues Unpaid (<?= date('F') ?>)</div>
  </div>
  <div class="stat-card songs">
    <div class="stat-icon"></div>
    <div class="stat-number"><?= $totalSongs ?></div>
    <div class="stat-label">Songs Uploaded</div>
  </div>
</div>

<div class="grid grid-2">
  <div class="card">
    <h3> Today's Attendance</h3>
    <p class="text-muted">Marked present today: <strong><?= $presentToday ?></strong> of <?= $totalMembers ?> active members</p>
    <a href="attendance.php" class="btn btn-purple btn-sm">Go to Attendance →</a>
  </div>
  <div class="card">
    <h3>⚡ Quick Actions</h3>
    <div class="flex gap-10" style="flex-wrap:wrap;margin-top:8px;">
      <a href="member_add.php"      class="btn btn-primary btn-sm">+ Add Member</a>
      <a href="songs_admin.php"     class="btn btn-primary btn-sm">+ Upload Song</a>
      <a href="dues.php"            class="btn btn-primary btn-sm">Manage Dues</a>
      <a href="committee_admin.php" class="btn btn-primary btn-sm">Edit Committee</a>
    </div>
  </div>
</div>

<?php require '_footer.php'; ?>
