<?php
require_once '../config/db.php';
require_once '../includes/auth.php';
require_login();

$pageTitle = 'Attendance';
$activeNav = 'attendance';

$date = $_GET['date'] ?? date('Y-m-d');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $date     = $_POST['date'];
    $statuses = $_POST['status'] ?? [];
    foreach ($statuses as $memberId => $status) {
        $pdo->prepare(
            "INSERT INTO attendance (member_id,attendance_date,status) VALUES (?,?,?)
             ON DUPLICATE KEY UPDATE status=?"
        )->execute([$memberId,$date,$status,$status]);
    }
    header('Location: attendance.php?date='.urlencode($date).'&saved=1');
    exit;
}

$members = $pdo->query("SELECT * FROM members WHERE status='Active' ORDER BY full_name ASC")->fetchAll();

$existing = [];
$s = $pdo->prepare("SELECT member_id,status FROM attendance WHERE attendance_date=?");
$s->execute([$date]);
foreach ($s->fetchAll() as $r) { $existing[$r['member_id']] = $r['status']; }

$presentCount = count(array_filter($existing, fn($v) => $v==='Present'));
$absentCount  = count(array_filter($existing, fn($v) => $v==='Absent'));
$lateCount    = count(array_filter($existing, fn($v) => $v==='Late'));

require '_sidebar.php';
?>

<div class="admin-topbar"><h1>Mark Attendance</h1></div>

<?php if (isset($_GET['saved'])): ?>
  <div class="alert alert-success">✅ Attendance saved for <?= htmlspecialchars($date) ?>.</div>
<?php endif; ?>

<form method="GET" class="mb-20">
  <div class="form-group" style="max-width:220px;">
    <label>Select Date</label>
    <input type="date" name="date" value="<?= htmlspecialchars($date) ?>" onchange="this.form.submit()">
  </div>
</form>

<?php if (!empty($existing)): ?>
<div class="grid grid-3 mb-20">
  <div class="stat-card paid"><div class="stat-icon">✅</div><div class="stat-number"><?= $presentCount ?></div><div class="stat-label">Present</div></div>
  <div class="stat-card unpaid"><div class="stat-icon">❌</div><div class="stat-number"><?= $absentCount ?></div><div class="stat-label">Absent</div></div>
  <div class="stat-card girls"><div class="stat-icon">⏰</div><div class="stat-number"><?= $lateCount ?></div><div class="stat-label">Late</div></div>
</div>
<?php endif; ?>

<form method="POST">
  <input type="hidden" name="date" value="<?= htmlspecialchars($date) ?>">
  <div style="overflow-x:auto;">
    <table>
      <thead>
        <tr><th>#</th><th>Member Name</th><th>Category</th><th>Voice Part</th><th>Status</th></tr>
      </thead>
      <tbody>
        <?php foreach ($members as $i => $m): ?>
          <?php $cur = $existing[$m['id']] ?? 'Present'; ?>
          <tr>
            <td><?= $i+1 ?></td>
            <td><strong><?= htmlspecialchars($m['full_name']) ?></strong></td>
            <td><?= htmlspecialchars($m['category']) ?></td>
            <td><?= htmlspecialchars($m['voice_part']??'-') ?></td>
            <td>
              <select name="status[<?= $m['id'] ?>]" style="width:130px;">
                <option value="Present" <?= $cur==='Present'?'selected':'' ?>>Present</option>
                <option value="Absent"  <?= $cur==='Absent' ?'selected':'' ?>>Absent</option>
                <option value="Late"    <?= $cur==='Late'   ?'selected':'' ?>>Late</option>
              </select>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <button type="submit" class="btn btn-primary mt-20">💾 Save Attendance for <?= htmlspecialchars($date) ?></button>
</form>

<?php require '_footer.php'; ?>
