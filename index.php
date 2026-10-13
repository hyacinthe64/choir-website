<?php
require_once 'config/db.php';
$pageTitle  = 'NARADA CHOIR AT EMLR — Home';
$activePage = 'home';

$totalMembers = $pdo->query("SELECT COUNT(*) FROM members WHERE status='Active'")->fetchColumn();
$totalSongs   = $pdo->query("SELECT COUNT(*) FROM songs")->fetchColumn();

require 'includes/header.php';
?>

<section class="hero">
  <h1>Welcome to <span class="gold-text">NARADA CHOIR AT EMLR</span></h1>
  <p>Lifting voices, building community, and sharing the gift of song. Explore our music,
     meet our leadership team, and get to know our family of singers.</p>
  <div>
    <a href="songs.php"     class="btn btn-primary">🎵 Listen to Our Songs</a>
    <a href="committee.php" class="btn btn-outline">👥 Meet the Committee</a>
  </div>
</section>

<section class="section container">
  <div class="grid grid-3">
    <div class="card text-center">
      <div style="font-size:36px;">🎤</div>
      <h3><?= (int)$totalMembers ?></h3>
      <p class="text-muted">Active Choir Members</p>
    </div>
    <div class="card text-center">
      <div style="font-size:36px;">🎵</div>
      <h3><?= (int)$totalSongs ?></h3>
      <p class="text-muted">Songs in Our Library</p>
    </div>
    <div class="card text-center">
      <div style="font-size:36px;">🙏</div>
      <h3>Every Week</h3>
      <p class="text-muted">Rehearsals &amp; Services</p>
    </div>
  </div>
</section>

<section class="section container">
  <h2 class="section-title">About Our Choir</h2>
  <p class="section-subtitle" style="max-width:720px;margin:0 auto;">
    NARADA CHOIR AT EMLR is a community of men, women, boys and girls united by a love for
    music and worship. We rehearse regularly, perform at services and events, and proudly share
    our songs with everyone who wishes to listen. Browse our song library, get to know our
    leadership committee, and feel free to reach out to us.
  </p>
</section>

<?php require 'includes/footer.php'; ?>
