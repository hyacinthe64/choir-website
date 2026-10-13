<?php
require_once 'config/db.php';
$pageTitle  = 'Our Committee — NARADA CHOIR AT EMLR';
$activePage = 'committee';

$committee = $pdo->query("SELECT * FROM committee ORDER BY display_order ASC")->fetchAll();

require 'includes/header.php';
?>

<section class="hero" style="padding:50px 24px 80px;">
  <h1>Meet Our <span class="gold-text">Committee</span></h1>
  <p>The dedicated team that leads and organises our choir family.</p>
</section>

<section class="section container">
  <?php if (empty($committee)): ?>
    <p class="text-center text-muted" style="font-size:18px;">
      Committee information will be added soon.
    </p>
  <?php else: ?>
    <div class="grid grid-3">
      <?php foreach ($committee as $person): ?>
        <div class="card profile-card">
          <img class="avatar"
               src="<?= $person['photo']
                 ? 'uploads/photos/' . htmlspecialchars($person['photo'])
                 : 'https://ui-avatars.com/api/?name=' . urlencode($person['full_name']) . '&background=4a2c7a&color=fff&size=110' ?>"
               alt="<?= htmlspecialchars($person['full_name']) ?>">
          <h3><?= htmlspecialchars($person['full_name']) ?></h3>
          <div class="role"><?= htmlspecialchars($person['role']) ?></div>
          <p><?= htmlspecialchars($person['bio'] ?? '') ?></p>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</section>

<?php require 'includes/footer.php'; ?>
