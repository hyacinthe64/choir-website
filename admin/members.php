<?php
require_once '../config/db.php';
require_once '../includes/auth.php';
require_login();

$pageTitle = 'Members';
$activeNav = 'members';

$search = trim($_GET['q'] ?? '');
if ($search !== '') {
    $stmt = $pdo->prepare("SELECT * FROM members WHERE full_name LIKE ? OR email LIKE ? OR location LIKE ? ORDER BY full_name ASC");
    $stmt->execute(["%$search%","%$search%","%$search%"]);
} else {
    $stmt = $pdo->query("SELECT * FROM members ORDER BY full_name ASC");
}
$members = $stmt->fetchAll();

require '_sidebar.php';
?>

<div class="admin-topbar">
  <h1>Choir Members</h1>
  <a href="member_add.php" class="btn btn-primary">+ Add New Member</a>
</div>

<form method="GET" class="mb-20" style="max-width:380px;">
  <input type="text" name="q" placeholder="Search by name, email or location..." value="<?= htmlspecialchars($search) ?>">
</form>

<div style="overflow-x:auto;">
  <table>
    <thead>
      <tr>
        <th>#</th><th>Name</th><th>Category</th><th>Voice Part</th>
        <th>Phone</th><th>Email</th><th>Location</th>
        <th>Monthly Due</th><th>Status</th><th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php if (empty($members)): ?>
        <tr><td colspan="10" class="text-center text-muted" style="padding:30px;">No members found.</td></tr>
      <?php endif; ?>
      <?php foreach ($members as $i => $m): ?>
        <tr>
          <td><?= $i+1 ?></td>
          <td><strong><?= htmlspecialchars($m['full_name']) ?></strong></td>
          <td><?= htmlspecialchars($m['category']) ?></td>
          <td><?= htmlspecialchars($m['voice_part'] ?? '-') ?></td>
          <td><?= htmlspecialchars($m['phone'] ?? '-') ?></td>
          <td><?= htmlspecialchars($m['email'] ?? '-') ?></td>
          <td><?= htmlspecialchars($m['location'] ?? '-') ?></td>
          <td><?= number_format($m['monthly_due'],0) ?></td>
          <td><span class="badge badge-<?= strtolower($m['status']) ?>"><?= $m['status'] ?></span></td>
          <td>
            <a href="member_edit.php?id=<?= $m['id'] ?>" class="btn btn-purple btn-sm">Edit</a>
            <a href="member_delete.php?id=<?= $m['id'] ?>" class="btn btn-danger btn-sm"
               onclick="return confirm('Delete <?= htmlspecialchars(addslashes($m['full_name'])) ?>? This removes ALL their attendance and dues records too.');">Delete</a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<?php require '_footer.php'; ?>
