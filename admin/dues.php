<?php
require_once '../config/db.php';
require_once '../includes/auth.php';
require_login();

$pageTitle = 'Dues / Fees';
$activeNav = 'dues';

$month = (int)($_GET['month'] ?? date('n'));
$year  = (int)($_GET['year']  ?? date('Y'));

// Toggle paid/unpaid
if (isset($_GET['toggle'])) {
    $memberId = (int)$_GET['toggle'];
    $s = $pdo->prepare("SELECT * FROM dues WHERE member_id=? AND due_month=? AND due_year=?");
    $s->execute([$memberId,$month,$year]);
    $existing = $s->fetch();
    if ($existing) {
        $newStatus = $existing['status']==='Paid' ? 'Unpaid' : 'Paid';
        $paidDate  = $newStatus==='Paid' ? date('Y-m-d') : null;
        $pdo->prepare("UPDATE dues SET status=?,paid_date=? WHERE id=?")
            ->execute([$newStatus,$paidDate,$existing['id']]);
    } else {
        $s2 = $pdo->prepare("SELECT monthly_due FROM members WHERE id=?");
        $s2->execute([$memberId]);
        $amount = $s2->fetchColumn() ?: 0;
        $pdo->prepare("INSERT INTO dues (member_id,due_month,due_year,amount,status,paid_date) VALUES (?,?,?,?,'Paid',?)")
            ->execute([$memberId,$month,$year,$amount,date('Y-m-d')]);
    }
    header("Location: dues.php?month=$month&year=$year");
    exit;
}

$rows = $pdo->prepare(
    "SELECT m.id,m.full_name,m.category,m.monthly_due,
            d.status AS due_status, d.paid_date
     FROM members m
     LEFT JOIN dues d ON d.member_id=m.id AND d.due_month=? AND d.due_year=?
     WHERE m.status='Active'
     ORDER BY m.full_name ASC"
);
$rows->execute([$month,$year]);
$rows = $rows->fetchAll();

$paidCount   = count(array_filter($rows, fn($r) => $r['due_status']==='Paid'));
$unpaidCount = count($rows) - $paidCount;

$months = [1=>'January',2=>'February',3=>'March',4=>'April',5=>'May',6=>'June',
           7=>'July',8=>'August',9=>'September',10=>'October',11=>'November',12=>'December'];

require '_sidebar.php';
?>

<div class="admin-topbar">
  <h1>Dues / Fees — <?= $months[$month] ?> <?= $year ?></h1>
</div>

<form method="GET" class="flex gap-10 mb-20" style="align-items:flex-end;flex-wrap:wrap;">
  <div class="form-group" style="margin-bottom:0;">
    <label>Month</label>
    <select name="month" style="width:150px;">
      <?php foreach ($months as $num => $name): ?>
        <option value="<?= $num ?>" <?= $num===$month?'selected':'' ?>><?= $name ?></option>
      <?php endforeach; ?>
    </select>
  </div>
  <div class="form-group" style="margin-bottom:0;">
    <label>Year</label>
    <input type="number" name="year" value="<?= $year ?>" style="width:100px;">
  </div>
  <button type="submit" class="btn btn-purple">View</button>
</form>

<div class="grid grid-2 mb-20">
  <div class="stat-card paid">
    <div class="stat-icon">✅</div>
    <div class="stat-number"><?= $paidCount ?></div>
    <div class="stat-label">Members Paid this month</div>
  </div>
  <div class="stat-card unpaid">
    <div class="stat-icon">⏳</div>
    <div class="stat-number"><?= $unpaidCount ?></div>
    <div class="stat-label">Members Unpaid this month</div>
  </div>
</div>

<div style="overflow-x:auto;">
  <table>
    <thead>
      <tr><th>#</th><th>Member Name</th><th>Category</th><th>Amount Due</th><th>Status</th><th>Paid On</th><th>Action</th></tr>
    </thead>
    <tbody>
      <?php foreach ($rows as $i => $r): ?>
        <?php $status = $r['due_status'] ?? 'Unpaid'; ?>
        <tr>
          <td><?= $i+1 ?></td>
          <td><strong><?= htmlspecialchars($r['full_name']) ?></strong></td>
          <td><?= htmlspecialchars($r['category']) ?></td>
          <td><?= number_format($r['monthly_due'],0) ?></td>
          <td><span class="badge badge-<?= strtolower($status) ?>"><?= $status ?></span></td>
          <td><?= $r['paid_date'] ? date('d M Y',strtotime($r['paid_date'])) : '-' ?></td>
          <td>
            <a href="dues.php?toggle=<?= $r['id'] ?>&month=<?= $month ?>&year=<?= $year ?>"
               class="btn btn-sm <?= $status==='Paid'?'btn-danger':'btn-success' ?>">
              Mark as <?= $status==='Paid'?'Unpaid':'Paid' ?>
            </a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<?php require '_footer.php'; ?>
