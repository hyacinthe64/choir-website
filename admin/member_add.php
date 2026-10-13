<?php
require_once '../config/db.php';
require_once '../includes/auth.php';
require_login();

$pageTitle = 'Add Member';
$activeNav = 'members';
$error = '';

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

    if ($full_name==='' || $gender==='' || $category==='') {
        $error = 'Full name, gender and category are required.';
    } else {
        $photoName = null;
        if (!empty($_FILES['photo']['name'])) {
            $ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
            if (in_array($ext,['jpg','jpeg','png','webp'])) {
                $photoName = 'member_'.time().'_'.rand(100,999).'.'.$ext;
                move_uploaded_file($_FILES['photo']['tmp_name'], '../uploads/photos/'.$photoName);
            }
        }
        $stmt = $pdo->prepare(
            "INSERT INTO members (full_name,gender,category,phone,email,location,voice_part,photo,joined_date,monthly_due)
             VALUES (?,?,?,?,?,?,?,?,?,?)"
        );
        $stmt->execute([$full_name,$gender,$category,$phone,$email,$location,$voice_part,$photoName,$joined_date?:null,$monthly_due]);
        header('Location: members.php?added=1');
        exit;
    }
}

require '_sidebar.php';
?>

<div class="admin-topbar"><h1>Add New Member</h1></div>

<div class="card" style="max-width:660px;">
  <?php if ($error): ?><div class="alert alert-error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
  <form method="POST" enctype="multipart/form-data">

    <div class="form-group">
      <label>Full Name *</label>
      <input type="text" name="full_name" placeholder="e.g. Jean Mukiza" required>
    </div>

    <div class="grid grid-2">
      <div class="form-group">
        <label>Gender *</label>
        <select name="gender" required>
          <option value="">-- Select --</option>
          <option value="Male">Male</option>
          <option value="Female">Female</option>
        </select>
      </div>
      <div class="form-group">
        <label>Category *</label>
        <select name="category" required>
          <option value="">-- Select --</option>
          <option value="Man">Man</option>
          <option value="Woman">Woman</option>
          <option value="Boy">Boy</option>
          <option value="Girl">Girl</option>
        </select>
      </div>
    </div>

    <div class="grid grid-2">
      <div class="form-group">
        <label>Phone</label>
        <input type="text" name="phone" placeholder="0788 000 000">
      </div>
      <div class="form-group">
        <label>Email</label>
        <input type="email" name="email" placeholder="name@email.com">
      </div>
    </div>

    <div class="grid grid-2">
      <div class="form-group">
        <label>Location / Address</label>
        <input type="text" name="location" placeholder="e.g. Kicukiro, Kigali">
      </div>
      <div class="form-group">
        <label>Voice Part</label>
        <input type="text" name="voice_part" placeholder="Soprano / Alto / Tenor / Bass">
      </div>
    </div>

    <div class="grid grid-2">
      <div class="form-group">
        <label>Joined Date</label>
        <input type="date" name="joined_date">
      </div>
      <div class="form-group">
        <label>Monthly Due Amount</label>
        <input type="number" step="0.01" name="monthly_due" value="1000">
      </div>
    </div>

    <div class="form-group">
      <label>Photo (jpg, png, webp)</label>
      <input type="file" name="photo" accept="image/*">
    </div>

    <button type="submit" class="btn btn-primary">Save Member</button>
    <a href="members.php" class="btn" style="background:#eee;color:#333;border:none;">Cancel</a>
  </form>
</div>

<?php require '_footer.php'; ?>
