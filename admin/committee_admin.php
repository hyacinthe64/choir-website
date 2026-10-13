<?php
require_once '../config/db.php';
require_once '../includes/auth.php';
require_login();

$pageTitle = 'Manage Committee';
$activeNav = 'committee';
$error = '';

// Add
if ($_SERVER['REQUEST_METHOD']==='POST' && ($_POST['action']??'')==='add') {
    $full_name = trim($_POST['full_name'] ?? '');
    $role      = trim($_POST['role']      ?? '');
    $bio       = trim($_POST['bio']       ?? '');
    $order     = (int)($_POST['display_order'] ?? 0);

    if ($full_name==='' || $role==='') {
        $error = 'Name and role are required.';
    } else {
        $photoName = null;
        if (!empty($_FILES['photo']['name'])) {
            $ext = strtolower(pathinfo($_FILES['photo']['name'],PATHINFO_EXTENSION));
            if (in_array($ext,['jpg','jpeg','png','webp'])) {
                $photoName = 'committee_'.time().'_'.rand(100,999).'.'.$ext;
                move_uploaded_file($_FILES['photo']['tmp_name'], '../uploads/photos/'.$photoName);
            }
        }
        $pdo->prepare("INSERT INTO committee (full_name,role,photo,bio,display_order) VALUES (?,?,?,?,?)")
            ->execute([$full_name,$role,$photoName,$bio,$order]);
        header('Location: committee_admin.php?added=1');
        exit;
    }
}

// Delete
if (isset($_GET['delete'])) {
    $pdo->prepare("DELETE FROM committee WHERE id=?")->execute([(int)$_GET['delete']]);
    header('Location: committee_admin.php?deleted=1');
    exit;
}

$committee = $pdo->query("SELECT * FROM committee ORDER BY display_order ASC")->fetchAll();

require '_sidebar.php';
?>

<div class="admin-topbar"><h1>Manage Committee</h1></div>

<?php if (isset($_GET['added'])): ?>
  <div class="alert alert-success">✅ Committee member added.</div>
<?php endif; ?>
<?php if (isset($_GET['deleted'])): ?>
  <div class="alert alert-success">Committee member removed.</div>
<?php endif; ?>

<div class="grid grid-2">

  <!-- ADD FORM -->
  <div class="card">
    <h3>Add Committee Member</h3>
    <?php if ($error): ?><div class="alert alert-error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
    <form method="POST" enctype="multipart/form-data">
      <input type="hidden" name="action" value="add">
      <div class="form-group">
        <label>Full Name *</label>
        <input type="text" name="full_name" required>
      </div>
      <div class="form-group">
        <label>Role / Position *</label>
        <input type="text" name="role" placeholder="e.g. Choir Director, Secretary, Treasurer" required>
      </div>
      <div class="form-group">
        <label>Short Bio</label>
        <textarea name="bio" rows="3"></textarea>
      </div>
      <div class="form-group">
        <label>Display Order <small class="text-muted">(1 = first on public page)</small></label>
        <input type="number" name="display_order" value="0" style="width:100px;">
      </div>
      <div class="form-group">
        <label>Photo (jpg, png, webp)</label>
        <input type="file" name="photo" accept="image/*">
      </div>
      <button type="submit" class="btn btn-primary">Add to Committee</button>
    </form>
  </div>

  <!-- CURRENT COMMITTEE -->
  <div class="card">
    <h3>Current Committee (<?= count($committee) ?> members)</h3>
    <?php if (empty($committee)): ?>
      <p class="text-muted">No committee members added yet.</p>
    <?php else: ?>
      <?php foreach ($committee as $p): ?>
        <div class="flex-between" style="border-bottom:1px solid #eee;padding:14px 0;gap:10px;">
          <div class="flex gap-10" style="align-items:center;">
            <img src="<?= $p['photo']
              ? '../uploads/photos/'.htmlspecialchars($p['photo'])
              : 'https://ui-avatars.com/api/?name='.urlencode($p['full_name']).'&background=4a2c7a&color=fff&size=48' ?>"
                 style="width:48px;height:48px;border-radius:50%;object-fit:cover;border:2px solid var(--gold);">
            <div>
              <strong><?= htmlspecialchars($p['full_name']) ?></strong>
              <div class="text-muted" style="font-size:13px;"><?= htmlspecialchars($p['role']) ?></div>
            </div>
          </div>
          <a href="committee_admin.php?delete=<?= $p['id'] ?>" class="btn btn-danger btn-sm"
             onclick="return confirm('Remove <?= htmlspecialchars(addslashes($p['full_name'])) ?>?');">Remove</a>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
</div>

<?php require '_footer.php'; ?>
