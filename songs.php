<?php
require_once 'config/db.php';
$pageTitle  = 'Our Songs — NARADA CHOIR AT EMLR';
$activePage = 'songs';

$songs = $pdo->query("SELECT * FROM songs ORDER BY uploaded_at DESC")->fetchAll();

require 'includes/header.php';
?>

<section class="hero" style="padding:50px 24px 80px;">
  <h1>Our <span class="gold-text">Song Library</span></h1>
  <p>Listen to songs performed and recorded by our choir.</p>
</section>

<section class="section container">
  <?php if (empty($songs)): ?>
    <p class="text-center text-muted" style="font-size:18px;">
      No songs have been uploaded yet. Please check back soon! 🎵
    </p>
  <?php else: ?>
    <div class="grid grid-3">
      <?php foreach ($songs as $song): ?>
        <div class="card song-card">
          <h3><?= htmlspecialchars($song['title']) ?></h3>
          <div class="meta">
            <?php if ($song['composer']): ?>
              by <?= htmlspecialchars($song['composer']) ?> &middot;
            <?php endif; ?>
            <?= date('M Y', strtotime($song['uploaded_at'])) ?>
          </div>
          <?php if ($song['category']): ?>
            <span class="tag"><?= htmlspecialchars($song['category']) ?></span>
          <?php endif; ?>
          <audio controls>
            <source src="uploads/songs/<?= htmlspecialchars($song['file_path']) ?>" type="audio/mpeg">
            Your browser does not support the audio element.
          </audio>
          <?php if ($song['lyrics']): ?>
            <details>
              <summary style="cursor:pointer;color:var(--soft-purple);font-weight:600;margin-top:4px;">
                📄 View Lyrics
              </summary>
              <p style="white-space:pre-line;font-size:14px;color:var(--gray-text);margin-top:8px;">
                <?= htmlspecialchars($song['lyrics']) ?>
              </p>
            </details>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</section>

<?php require 'includes/footer.php'; ?>
