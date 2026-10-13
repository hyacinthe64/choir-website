<?php
require_once '../config/db.php';
require_once '../includes/auth.php';
require_login();

$pageTitle = 'Edit Member';
$activeNav = 'members';
$error = '';

$id   = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM members WHERE id = ?");
$stmt->execute([$id]);
$member = $stmt->fetch();
if (!$member) { header('Location: members.php'); exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name   = trim($_POST['full_name']   ?? '');
    $gender      = $_POST['gender']      ?? '';
    $category    = $_POST['category']    ?? '';
    $phone       = trim($_POST['phone']       ?? '');
    $email       = trim($_POST['email']       ?? '');
    $location    = trim($_POST['location']    ?? '');
    $voice_part  = trim($_POST['voice_part']  ?? '');
    $joined_date = $_POST['joined_date'] ?? null;
    $monthly_due = $_POST['monthly_due'] ?? 1000;
    $status      = $_POST['status']      ?? 'Active';

    if ($full_name==='' || $gender==='' || $category==='') {
        $error = 'Full name, gender and category are required.';
    } else {
        $photoName = $member['photo'];
        if (!empty($_FILES['photo']['name'])) {
            $ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
            if (in_array($ext,['jpg','jpeg','png','webp'])) {
                $photoName = 'member_'.time().'_'.rand(100,999).'.'.$ext;
                move_uploaded_file($_FILES['photo']['tmp_name'], '../uploads/photos/'.$photoName);
            }
        }
        $stmt = $pdo->prepare(
            "UPDATE members SET full_name=?,gender=?,category=?,phone=?,email=?,location=?,
             voice_part=?,photo=?,joined_date=?,monthly_due=?,status=? WHERE id=?"
        );
        $stmt->execute([$full_name,$gender,$category,$phone,$email,$location,
                        $voice_part,$photoName,$joined_date?:null,$monthly_due,$status,$id]);
        header('Location: members.php?updated=1');
        exit;
    }
}

require '_sidebar.php';
?>

<div class="admin-topbar"><h1>Edit Member</h1></div>

<div class="card" style="max-width:660px;">
  <?php if ($error): ?><div class="alert alert-error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
  <form method="POST" enctype="multipart/form-data">

    <div class="form-group">
      <label>Full Name *</label>
      <input type="text" name="full_name" value="<?= htmlspecialchars($member['full_name']) ?>" required>
    </div>

    <div class="grid grid-2">
      <div class="form-group">
        <label>Gender *</label>
        <select name="gender" required>
          <option value="Male"   <?= $member['gender']==='Male'  ?'selected':'' ?>>Male</option>
          <option value="Female" <?= $member['gender']==='Female'?'selected':'' ?>>Female</option>
        </select>
      </div>
      <div class="form-group">
        <label>Category *</label>
        <select name="category" required>
          <?php foreach (['Man','Woman','Boy','Girl'] as $c): ?>
            <option value="<?= $c ?>" <?= $member['category']===$c?'selected':'' ?>><?= $c ?></option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>

    <div class="grid grid-2">
      <div class="form-group">
        <label>Phone</label>
        <input type="text" name="phone" value="<?= htmlspecialchars($member['phone']??'') ?>">
      </div>
      <div class="form-group">
        <label>Email</label>
        <input type="email" name="email" value="<?= htmlspecialchars($member['email']??'') ?>">
      </div>
    </div>

    <div class="grid grid-2">
      <div class="form-group">
        <label>Location / Address</label>
        <input type="text" name="location" value="<?= htmlspecialchars($member['location']??'') ?>">
      </div>
      <div class="form-group">
        <label>Voice Part</label>
        <input type="text" name="voice_part" value="<?= htmlspecialchars($member['voice_part']??'') ?>">
      </div>
    </div>

    <div class="grid grid-2">
      <div class="form-group">
        <label>Joined Date</label>
        <input type="date" name="joined_date" value="<?= htmlspecialchars($member['joined_date']??'') ?>">
      </div>
      <div class="form-group">
        <label>Monthly Due Amount</label>
        <input type="number" step="0.01" name="monthly_due" value="<?= htmlspecialchars($member['monthly_due']) ?>">
      </div>
    </div>

    <div class="form-group">
      <label>Status</label>
      <select name="status">
        <option value="Active"   <?= $member['status']==='Active'  ?'selected':'' ?>>Active</option>
        <option value="Inactive" <?= $member['status']==='Inactive'?'selected':'' ?>>Inactive</option>
      </select>
    </div>

    <div class="form-group">
      <label>Photo (leave empty to keep current)</label>
      <input type="file" name="photo" accept="image/*">
    </div>

    <button type="submit" class="btn btn-primary">Update Member</button>
    <a href="members.php" class="btn" style="background:#eee;color:#333;border:none;">Cancel</a>
  </form>
</div>

<?php require '_footer.php'; ?>
